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
Create a MySQL database and import the constraints.sql file.

Open file "configuration.php"
	Fill in the fields as outlined.  The configuration username/password are to connect to the database not the username and password you create for the user table.
	
	There is no admin interface.  You'll need to manually create one or more users in the users table.  The password need to be an md5 hash to work correctly.

	If you need to genrate the PHP script to run is as basic as follows.
	<?php
		echo md5('my_password_here');
		
	OR in MySQL
	INSERT INTO users ('username', 'password') VALUES('your_username', MD5('your_password'));
Notes:

<hr />

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

<hr />

This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a compiled
binary, for any purpose, commercial or non-commercial, and by any
means.

In jurisdictions that recognize copyright laws, the author or authors
of this software dedicate any and all copyright interest in the
software to the public domain. We make this dedication for the benefit
of the public at large and to the detriment of our heirs and
successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights to this
software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <http://unlicense.org/>

<p>3rd party tools used, such as wkhtmltopdf remain under their original licenses.</p>

 