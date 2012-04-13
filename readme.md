AI Constraint Analysis tool

NOTE (12-12-2011):  The 3rd party service used for this application now limits screenshots to three concurrent image requests from the same IP address.  This makes the Constraint Analysis tool very awkward to use.  I'm working on a version that uses wkhtmltopdf instead.

NOTE (12-13-2011): Updated to work with wkhtmltopdf.

Requirements: 

* PHP 5.1+, 
* Command Line PHP access
* JavaScript enabled browser,
* whkthmltopdf (download version for your OS at http://http://code.google.com/p/wkhtmltopdf and follow the installation directions for your OS at http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltopdf-0.9.9-doc.html.  See http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltoimage_0.10.0_rc2-doc.html for command line options for wkhtmltoimage.)
* Any database for which there is a PDO driver, 
* If PDO is not enabled you'll need to enable it for your database type in your PHP setup.  The SQL file provided is in the MySQL SQL format.

Configuration:
Open file "constraint_functions.php"
	In function dbConnect()
	<ol>
		<li>1. Add your username where it says "your_username."</li>
		<li>Add your password where it says "your_password."</li>
		<li>Add your databases's name where it says "your_db_name."</li>
		<li>Change you MySQL path, if it's not localhost.</li>
	</ol>
	
	In function upload()
		
		1. Line 237 - Change dev_path_here to your development environment web server path.</li>
		2. Line 237 - Change production_path_here to your production environment web server path.</li>
		3. Line 241 - Change "path_to_upload_folder" to the name of your application.  The rest of the path should stay as it is.</li>
		
	
	If there is no "screenshots" directory you'll need to create it at the root of your main "constraint_analysis" directory.
    
Open file "cli_image.php"
	Change the specified example path, C:\"Program Files"\wkhtmltopdf\wkhtmltoimage.exe, to whatever your path is to wkhtmltopdf.  You can also change the argument settings or add others.  See http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltoimage_0.10.0_rc2-doc.html for the available options.
	
	There is no admin interface.  You'll need to manually create one or more users in the users table.  The password need to be an md5 hash to work correctly.

	If you need to genrate the PHP script to run is as basic as follows.
	<?php
		echo md5('my_password_here');
		
	OR in MySQL
	INSERT INTO users ('username', 'password') VALUES('your_username', MD5('your_password'));
Notes:

<p xmlns:dct="http://purl.org/dc/terms/" xmlns:vcard="http://www.w3.org/2001/vcard-rdf/3.0#">
  <a rel="license"
     href="http://creativecommons.org/publicdomain/zero/1.0/">
    <img src="http://i.creativecommons.org/p/zero/1.0/80x15.png" style="border-style: none;" alt="CC0" />
  </a>
  <br />
  To the extent possible under law,
  <span rel="dct:publisher" resource="[_:publisher]">the person who associated CC0</span>
  with CINCH has waived all copyright and related or neighboring rights to
  <span property="dct:title">CINCH</span>.
This work is published from the:
<span property="vcard:Country" datatype="dct:ISO3166"
      content="US" about="[_:publisher]">
  United States</span>.
</p>

This code is very rough and pretty basic.  There aren't many comments.  Hopefully, it's fairly self explanatory.

Urls in your lists should have formatting similar to this: digital.ncdcr.gov.  Leave off the "http(s)://"
 

Basic Functionality:

After logging in a user is taken to the URL listings page.  If they're aren't any or they'd like to start a new analysis they can click 'Upload Constraint List.'  You can then upload your .txt or .csv file which contains one URL per line.  The upload can take awhile and it's better to use smaller files as the upload can time out depending on your server settings.  NOTE: Anytime you upload a new list of urls it overwrites and deletes all previous lists.

Open a command prompt and run cli_image.php.
	Example (Windows):
	 Go to start->run->type cmd and hit enter
	 Navigate to your ai_constraint folder and run c:\"path to php"\php.exe cli_image.php.

This will create your screenshots.  It will undoubtedly take awhile.
wkthmltopdf on Windows is prone to the occasional crash.  Just close the error message and it should continue.  If the script freezes on a url you can stop it by hitting Ctrl-C or whatever the escape sequence is on your OS.  If you start the script again it should pick up where it left off.

Once your URL list has been processed and the screenshots returned the user can click 'URL Listings' to process the listings as they see fit.  100 images are displayed per page.  Each field is saved as it moved through or by hittng the Save button.

Once your satisfied with your results you can click 'Download Contraint List' to downloat a .txt file of your uploaded file list and the changes you made.  The following fields are included for each URL:  url, constrain, seed).

 