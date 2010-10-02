<?php
	############## Connection to MySql stuff #########
	#require_once("authorize.php");
	//$dbc = mysql_connect('localhost', 'API', 'Vulcan EBF-25') or die('Unable to connect: ' . mysql_error());
	//$dbc = mysql_connect('localhost', 'root', 'BetterThenEver') or die('Unable to connect: ' . mysql_error());
	$dbc = mysql_connect('localhost', DB_USER, DB_PASSWORD) or die('Unable to connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Unable to select ' . DB_NAME . ': ' . mysql_error());
	##################################################
	
	############# ERROR REPORTING ####################
	ini_set('display_errors','1');
	ini_set('display_startup_errors','1');
	error_reporting (E_ALL);
	##################################################


?>