<?php
/**
* Configure the database
*
* $db_config['host'] - This can usually be left as localhost unless you've specifically changed it
* $db_config['username'] - The username for the constraint database.
* $db_config['password'] - The password for the constraint database.
* $db_config['db_name'] - The name of the constraint database you created.
*/
$db_config['host']     = 'localhost';
$db_config['username'] = 'root';
$db_config['password'] = '';
$db_config['db_name']  = '';

/**
* Configure file paths
*
* $path_config['wkhtmltopdf'] - Path to wkhtmltopdf binary.  Be sure to leave the double-quotes around "Program Files" if file is located there. :) 
*/
$path_config['wkhtmltopdf'] = 'C:\"Program Files"\wkhtmltopdf\wkhtmltoimage.exe';