<?php
include "constraint_functions.php";
if(!isset($_GET['download'])) { intruder(); }
$db = dbConnect($db_config);


	if(isset($_GET['page'])):
		$page = clean($_GET['page']);
	else:
		$page = 1;
	endif;
	
	$show_all_links = selectLinks($db, $page);


if(isset($_GET['download'])):
	$csv_data = getCsvQuery($db);
	processCsvQuery($csv_data); // generates constraint_analysis.txt
	downloadFile('constraint_analysis.txt');
endif;