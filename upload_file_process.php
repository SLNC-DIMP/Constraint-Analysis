<?php
ini_set('max_execution_time', '43200'); // max time allowed on BlueHost, as this could take awhile

if(isset($_POST['submit_urls'])) {
	$file_path = upload();
	$clearDb = clearDb($db);
	
	if($clearDb == true) {
		clearScreenshots();
		processUpload($db, $file_path);
	}
}