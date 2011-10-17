<?php
include 'constraint_functions.php';

if(isset($_POST['id'])):
	$db = dbConnect();
	processLink($db);
endif;