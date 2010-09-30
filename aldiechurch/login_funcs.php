<?php
	session_start();
	if (isset($_GET['FUNCTION'])) {
		$requested_function = $_GET['FUNCTION'];
		call_user_func($requested_function);
	}


	function is_logged_in() {
		if(isset($_SESSION['auth_info'])) {
			$auth_info = $_SESSION['auth_info'];
			return ($auth_info['stat'] == 'ok');
		} else if(isset($_COOKIE['aldiechurch.com'])) {
			$raw_json = $_COOKIE['aldiechurch.com'];
			$auth_info = json_decode($raw_json, true);
			$_SESSION['auth_info'] = $auth_info;
			return ($auth_info['stat'] == 'ok');
		}
		
		return false;
	}
	
	function logout() {
		if(isset($_SESSION['auth_info'])) {
			unset($_SESSION['auth_info']);
		}
		
		 if(isset($_COOKIE['aldiechurch.com'])) {
			 setcookie ("aldiechurch.com", "", time() - 3600);
		}
		
		header('Location: /home.php');
	}
?>