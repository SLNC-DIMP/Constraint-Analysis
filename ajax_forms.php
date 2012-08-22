<?php
include 'constraint_functions.php';

if(isset($_POST['id'])):
	$db = dbConnect($db_config);
	processLink($db);
endif;