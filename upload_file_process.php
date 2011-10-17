<?php
if(isset($_POST)):
	ini_set('max_execution_time', '43200'); // max time allowed on BlueHost, as this could take awhile
endif;

if(isset($_POST['submit_urls'])):
	$file_path = upload();
	$clearDb = clearDb($db);
	
	if($clearDb == true):
		clearScreenshots();
		processUpload($db, $file_path);
	endif;
endif;

if(isset($_POST['check_images'])): // get final images if they're queued up and return processing default image
	$final_images = retryScreenshotQuery($db);
	
	while($row = $final_images->fetch(PDO::FETCH_ASSOC)):
		getScreenshot($row['url'], true);
	endwhile;
	echo '<p class="upload_forms_message">Screenshots updated</p>';
endif;