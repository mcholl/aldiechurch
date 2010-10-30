<?php
	############## Connection to MySql stuff #########
		if(!defined('DB_USER')) {
		//To aid in debugging - otherwise, I'd need to include wp-config.php...
		define('DB_USER', 'doverkn1_aldie');
		define('DB_PASSWORD', 'JCIMSAISTI');
		define('DB_NAME', 'doverkn1_wrdp2');
	}
	

	$dbc = mysql_connect('localhost', DB_USER, DB_PASSWORD) or die('Unable to connect: ' . mysql_error());
	mysql_select_db(DB_NAME) or die('Unable to select ' . DB_NAME . ': ' . mysql_error());
	##################################################
	
	############# ERROR REPORTING ####################
	ini_set('display_errors','1');
	ini_set('display_startup_errors','1');
	error_reporting (E_ALL);
	##################################################

?>