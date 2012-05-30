<?php
include 'constraint_functions.php';

$db = dbConnect();
	
$query = "SELECT id, url FROM links WHERE screenshot_path IS NULL";
$urls = $db->query($query);
	
if(empty($urls)) { 
	echo 'There are no links to process'; 
	exit;
}
	
foreach($urls as $url) {
	$link = trim($url['url']);
	$file_path = "screenshots/" . $link . ".jpg";
		
	passthru($path_config['wkhtmltopdf'] . ' --zoom .75 --load-error-handling ignore --stop-slow-scripts --height 600 --width 450 http://' . $link . ' ' . $file_path);
			
	$query = "UPDATE links SET screenshot_path = ? WHERE id = ?";
	$prepare = $db->prepare($query);
	$run = $prepare->execute(array($file_path, $url['id']));
}