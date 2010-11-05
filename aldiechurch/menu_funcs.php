<?php
	function absolute_url( $pgtitle ) {
		return 'http://' . $_SERVER['SERVER_NAME'] . '/' ; // . '/aldiechurch/'. $pgtitle;
	}
	
	function secured_url( $pgtitle ) {
		if(is_logged_in()) {
			return absolute_url( $pgtitle );
		}	
		return absolute_url('login.php');
	}
?>