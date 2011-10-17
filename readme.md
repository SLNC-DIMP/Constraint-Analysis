AI Constraint Analysis tool

Requirements: 

* PHP 5.1+, 
* JavaScript enabled browser,
* Any database for which there is a PDO driver, 
* If PDO is not enabled you'll need to enable it for your database type in your PHP setup.  The SQL file provided is in the MySQL SQL format.

Configuration:
<code>
Open file "constraint_functions.php"
	In function dbConnect()
		1. Add your username where it says "your_username."
		2. Add your password where it says "your_password."
		3. Add your databases's name where it says "your_db_name."
		4. Change you MySQL path, if it's not localhost.
	
	In function upload()
		1. Line 237 - Change dev_path_here to your development environment web server path.
		2. Line 237 - Change production_path_here to your production environment web server path.
		3. Line 241 - Change "path_to_upload_folder" to the name of your application.  The rest of the path should stay as it is.
		
	There is no admin interface.  You'll need to manually create one or mor users in the users table.  The password need to be an md5 hash to work correctly.

	If you need to genrate the PHP script to run is as basic as follows.
	<?php
		echo md5('my_password_here');
		
	OR in MySQL
	INSERT INTO users ('username', 'password') VALUES('your_username', MD5('your_password'));
</code>
Notes:

This code is very rough and pretty basic.  There aren't many comments.  Hopefully, it's fairly self explanatory.

Basic Functionality:

After logging in a user is taken to the URL listings page.  If they're aren't any or they'd like to start a new analysis they can click 'Upload Constraint List.'  You can then upload your .txt or .csv file which contains one URL per line.  The upload can take awhile and it's better to use smaller files as the upload can time out depending on your server settings.  NOTE: Anytime you upload a new list of urls it overwrites and deletes all previous lists.

Each URL is then sent to a free 3rd party screenshot site and the image retrieved.  This can also take awhile as the URLs are queued for processing on the remote server.

Once your URL list has been processed and the screenshots returned the user can click 'URL Listings' to process the listings as they see fit.  100 images are displayed per page.  Each field is saved as it moved through or by hittng the Save button.

Once your satisfied with your results you can click 'Download Contraint List' to downloat a .txt file of your uploaded file list and the changes you made.  The following fields are included for each URL:  url, constrain, seed).

 