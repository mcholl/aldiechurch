<?php
	
	/* .htaccess restricts all access to the directory, then this file supplies the proxy credentials */
	
	/* Unfortunately, this doesn't work, b/c apache doesn't pass the authorization header to a cgi script.*/
	
	$username = "aldiechurchproxy";
	$password = "JCIMSAIASTI";
	$creds = base64_encode($username . ":" . $password);
	
	header("Authorization: Basic " .$creds);
	header("Location: directory/PhotoDirectory.php");
	
	die(" I passed $creds (that's =>" . base64_decode($creds) . "<=");
		
?>