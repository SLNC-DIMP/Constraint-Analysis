<?php 
ob_start();
$page = strrchr($_SERVER['REQUEST_URI'], '/');
if($page != '/index.php' || '/') { session_start(); } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
<meta name="robots" content="noindex,nofollow" />
<title>Constraint Analysis</title>
<link href="constraint.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="jquery_constraint.js"></script>
</head>
<body>