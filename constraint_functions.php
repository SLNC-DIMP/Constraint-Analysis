<?php
function dbConnect() {
	try {
		$user = 'your_username';
		$pass = 'your_password';
		$dsn ='mysql:host=localhost;dbname=your_db_name';
		$db = new PDO($dsn, $user, $pass);
	} catch(PDOException $e) {
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$error = $db->errorInfo();
		if($error[0] != "") {
		//  print "<p>Could not connect to the database.</p>";
		  print_r($error);
		}
	}
	
	return $db;
}

function clean($data, $submit = false) {
	if($submit == true):
		$remove_submit = array_pop($_POST);
	endif;
	
	if(is_array($data)):
		foreach($data as $key => $field):
			$clean_data[$key] = strip_tags(trim($field));
		endforeach;
	else:
		$clean_data = strip_tags(trim($data));
	endif;
	
	return $clean_data;
}

/*
*
* login/off functions
*
*/
function login($db) {
	$values = clean($_POST);
	
	$password = md5($values['user_pass']);
	$user_info = array($values['username'], $password);
	
	$num_users = "SELECT COUNT(*) FROM users WHERE username = ? AND password = ?";
	$login = $db->prepare($num_users);
	$login->execute($user_info);
		
	if($login->fetchColumn() == 1):
		session_start();
			
		$login_query = "SELECT username FROM users WHERE username = ? AND password = ?";
		$login = $db->prepare($login_query);
		$login->execute($user_info);
		$good_login = $login->fetchAll(PDO::FETCH_ASSOC);
			
		foreach($good_login as $user_cred):
			$_SESSION['username'] = $user_cred['username'];
			$_SESSION['salt'] = mt_rand();
		endforeach;
			
		header("Location: constraint.php");
		exit;
	else:
		echo '<p>Your login was not successful.  Please try again.</p>';
	endif;
	
}

function logOff() {
	// Unset all of the session variables.
	$_SESSION = array();
		
	// From PHP manual
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	session_destroy();
	header("Location: index.php");
	exit;
}

function intruder() {
	if(!isset($_SESSION['username']) || !isset($_SESSION['salt'])):
		logOff();
	endif;
}

/*
*
* constraint analysis functions
*
*/

// Ids of links will rarely start at one so need to get actual listings
function getIds($db) {
	$query = "SELECT id FROM links ORDER BY id ASC";
	$id_list = $db->query($query);
	
	return $id_list;
}

function getPageRanges($id_list) {
	while($row = $id_list->fetch(PDO::FETCH_ASSOC)):
		$list[] = $row['id'];
	endwhile;
	$range = array_chunk($list, 100); // returns multi-dimensional array
	
	return $range;
}

function selectLinks($db, $page = 1) {
	$id_list = getIds($db);
	$range = getPageRanges($id_list);
	$page_key = $page - 1; // pages always one behind the array key
	$range_start = $range[$page_key][0];
	$range_end = end($range[$page_key]);
	
	$query = "SELECT * FROM links 
	WHERE id
	BETWEEN $range_start
	AND $range_end
	ORDER BY id ASC";
	$run = $db->query($query);
	
	return $run;
}

function paginateLinks($db, $css_id) {
	$num_links = dbRows($db);
	$pages = ceil($num_links / 100);
	
	echo '<ul id="' . $css_id . '">';
	echo '<li>Pages: </li>';
	for($i = 1; $i <= $pages; $i++):
		echo '<li><a href="constraint.php?page=' . $i . '">' . $i . '</a></li>';
	endfor;
	echo '</ul>';
}

function showCheckedRadio($radioValue, $checkedValue) {
	if($radioValue == $checkedValue):
		echo 'checked="checked"';
	endif;
}

function processLink($db) {
	$data = clean($_POST);
	$data['url'] = (!empty($data['url'])) ? $data['url'] : "NULL"; 
	
	$query = "UPDATE links SET constrain = ?, seed = ?, url = ? WHERE id = ?";
	$prepare = $db->prepare($query);
	$run = $prepare->execute(array_values($data));
	
	echo json_encode($run);
}

/*
*
* File download functions
*
*/
function getCsvQuery($db) {
	$query = "SELECT constrain, seed, url FROM links";
	$dbCsv = $db->query($query);
	
	return $dbCsv;
}

function processCsvQuery($dbCsv) {
	$fh = fopen("constraint_analysis.txt", "wb");
	fwrite($fh, "URL \t Constrain\t Seed\r\n");
	foreach($dbCsv as $row):
		$constrain = ($row['constrain'] == 0) ? "No" : "Yes";
		$seed = ($row['seed'] == 0) ? "No" : "Yes";
		fwrite($fh, $row['url'] . "\t" . $constrain . "\t" . $seed . "\r\n");
	endforeach;
	fclose($fh);
}
 
// courtesy of phpnet at holodyn dot com http://us3.php.net/manual/en/function.header.php
function downloadFile($fullPath) {
  // Must be fresh start
  if( headers_sent() ) {
    die('Headers Sent');
  }
  
  // Required for some browsers
  if(ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', 'Off');
  }
  
  // File Exists?
  if(file_exists($fullPath)):
    // Parse Info / Get Extension
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ctype="text/csv";
   
    header("Pragma: public"); // required
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false); // required for certain browsers
    header("Content-Type: $ctype");
    header("Content-Disposition: attachment; filename=\"" . basename($fullPath) . "\";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . $fsize);
    ob_clean();
    flush();
    readfile($fullPath);

  else:
    die('File Not Found');
  endif;
} 

/*
*
* File upload functions
*
*/

// Uploads txt and cvs files to be parsed and have its list of urls downloaded
// Thanks to Beginning PHP and MySQL by Jason Gilmore.  It's been awhile since I've done file uploads
function upload() {
	$file_type = $_FILES['upload']['type'];
	$message_start = '<p class="upload_forms_message">';
	
	// development or production environment
	$web_folder = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'dev_path_here' : 'production_path here';
		
	if(is_uploaded_file($_FILES['upload']['tmp_name']) 
	&& ($file_type == 'text/plain' || $file_type == 'text/comma-separated-values')): 
		$moved_file_path = $web_folder . '/path_to_upload_folder/uploads/constraint_urls.txt';
		$file = move_uploaded_file(strip_tags(trim($_FILES['upload']['tmp_name'])), $moved_file_path);
			
		if($file == true):
			echo $message_start. 'File successfully uploaded. ';
			return $moved_file_path;
		else:
			echo $message_start. 'File not uploaded correctly.  Please delete the file from the file list and try again.</p>';
		endif;
	else:
		switch($_FILES['upload']['error']):
			case 1:
			case 2:
				echo $message_start. 'File is too large to upload.  Try reducing the file size and upload again.</p>';
				break;
			case 3:
				echo $message_start. 'File was not completely uploaded.  Please try uploading the file again.</p>';
				break;
			case 4:
				echo $message_start. 'No file was selected for upload.  Please select a file to upload.</p>';
				break;
			default:
				echo $message_start. 'Possible invalid file type.  Please check that your file is a .txt or .cvs file.  
				Then try uploading the file again.</p>';	
				break;
		endswitch;
		exit;
	endif;
}

function dbRows($db) {
	$query = "SELECT COUNT(*) FROM links";
	$count = $db->query($query);
	$row_count = $count->fetchColumn();
	
	return $row_count;
}

function clearDb($db) {
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$query = "DELETE FROM links";
	$affected_row_count = $db->exec($query);
	
	$row_count = dbRows($db);
	
	if($row_count == 0):
		return true;
	else:
		echo "<p class='upload_forms_message'>Database could not be cleared correctly</p>";
		exit;
	endif;
}

function clearScreenshots() {
	$dir = 'screenshots';
	$screenshots = scandir($dir);
	
	foreach($screenshots as $screenshot):
		if(is_dir($screenshot)):
			unset($screenshot);
		endif;
		@$delete = unlink($dir . '/' . $screenshot); // suppress warning message on failure
		
		if($delete == false):
			echo '<p class="upload_forms_message">' . $screenshot . ' could not be deleted from the file system</p>';
		endif;
	endforeach;
}

function processUpload($db, $moved_file_path) {
	$ready_for_db = file($moved_file_path, FILE_SKIP_EMPTY_LINES);
	$query = "INSERT INTO links(url) VALUES(?)";
	
	foreach($ready_for_db as $url):
		$url = clean($url);
		filter_var($url, FILTER_SANITIZE_URL);
		$load_url = $db->prepare($query);
		$write_url = $load_url->execute(array($url));
		if($write_url != false):
			$url_id = $db->lastInsertId();
			$screenshot_path = getScreenshot($url);
			writeScreenshotDB($db, $screenshot_path, $url_id);
		else:
			echo '<p class="upload_forms_message">' . $url . ' could not be written to the database</p>';
		endif;
	endforeach;
	
	echo '<p class="upload_forms_message">Uploaded file urls written to the database, any errors should be displayed above</p>';
}

// gets screenshot.  can set retry to true if getting previously queued image
function getScreenshot($url, $retry = false) {
	$file_path = "screenshots/" . $url . ".png";

	if($retry == false || (file_exists($file_path))):
		$ch = curl_init("http://wimg.ca/size_3/http://" . $url);
		$fp = fopen($file_path, "wb");
		
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	endif;
	
	if(file_exists($file_path)):
		return $file_path;
	else:
		echo "<p class='upload_forms_message'>File path not found for: " . $url . "</p>";
		return '';
	endif;
}

// query to get all links to get final image if they've been queued up and return gibberish
function retryScreenshotQuery($db) {
	$query = "SELECT url FROM links";
	$run = $db->query($query);
	
	return $run;	
}

function writeScreenshotDB($db, $screenshot_path, $url_id) {
	$query = "UPDATE links SET screenshot_path = ? WHERE id = ?";
	$prepare = $db->prepare($query);
	$run = $prepare->execute(array($screenshot_path, $url_id));
	
	if($run == false):
		echo "<p class='upload_forms_message'>Screenshot path for " . $screenshot_path . " couldn't be written to the database</p>";
	endif;
}