<?php
ob_start();
include_once 'constraint_functions.php';

if(isset($_POST['loginSubmit'])):
	$db = dbConnect();
	login($db);
endif;

if(isset($_GET['logoff'])):
	logOff();
endif;