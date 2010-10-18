<?php

	if(isset($_GET['swap_guid'])) {
		accept_swap_request($_GET['swap_guid']);
	}
	
	function generate_swap_request($requestor_pid, $responder_pid, $role_id, $date_id) {
	
		$qry = "INSERT INTO swap_requests SET ";
		
		$qry .= "swap_guid = UUID() ";
		$qry .= ", requestor_pid=" . mysql_real_escape_string($requestor_pid);
		$qry .= ", responder_pid= " . mysql_real_escape_string($responder_pid);
		$qry .= ", role_id= " . mysql_real_escape_string($role_pid);
		$qry .= ", date_id= " . mysql_real_escape_string($date_pid);

		$res = mysql_query($qry) or die($qry . ": " . mysql_error());
		$row = mysql_fetch_assoc($res);
		
		echo $row['swap_guid'];
	}
	
	function accept_swap_request($swap_guid) {
		
		$qry = "SELECT * FROM swap_requests WHERE swap_guid =" . mysql_real_escape_string($swap_guid);
		$res = mysql_query($qry) or die($qry . ": " . mysql_error());
		if(mysql_affected_rows($res) == 0) {
			//Guid not found
		} 

		$row = mysql_fetch_assoc($res);
		if($row['completed'] == true) {
			//already done.
		}
		
		
		$qry_accept = "UPDATE cel_signups SET pid=" . $row['responder_pid'] . " WHERE pid=" . $row['requestor_pid'] . " AND date_id= " . $row['date_id'] . " AND role_id = " . $row['role_id'];
		$res = mysql_query($qry_accept) or die($qry_accept . ": " . mysql_error());
		if(mysql_affected_rows($res) == 0) {
			//Swap Request already filled by someone else, but not marked as updated
		}
		
		$qry_fil = "UPDATE swap_requests SET completed=TRUE WHERE swap_guid=" . mysql_real_escape_string($swap_guid) ;
		mysql_query($qry) or die($qry . ": " . mysql_error());
		
		//Check for any other swap requests that match this date and requestor
		$res = mysql_query(  "SELECT * FROM  swap_requests WHERE swap_guid="  . mysql_real_escape_string($swap_guid) ) or die(mysql_error());
		while( $row = mysql_fetch_assoc($res) ) {
						
			$requestor_pid = $row['requestor_pid'];
			$date_id = $row['date_id'];
			$role_id = $row['role_id'];
			
			$qry = "UPDATE swap_requests SET completed=true WHERE requestor_pid=$requestor_pid AND date_id = $date_id AND role_id = $role_id"; 
			mysql_query($qry) or die($qry . ": " . mysql_error());
		}
		echo "OK";

	}
		 