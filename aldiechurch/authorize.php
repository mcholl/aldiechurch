<?php

	require_once('login_funcs.php');
	
	/* See if the user if logged in */
	$is_authorized = false;
	if(is_logged_in()) {
		//TODO: For now, everyone who logs in is considered authoirzed. Eventually, I'll want to narrow that.
		$is_authorized = true;
	};
	
	if( $is_authorized ) {
		//Continue 
	} else {
		/* Unauthorized user must authenticate */
		//$realm = 'restricted';
		//header('WWW-Authenticate: Basic realm="$realm"');
    		header('HTTP/1.0 401 Unauthorized');
    		die( 'This page is restricted to authorized users. Please login by clicking <a href="aldiechurch/login.php">here</a>.');		
	}
	
	//continue on to whatever page included this function...
?>