<?php

	if(!defined('DB_USER')) {
		//To aid in debugging...
		define('DB_USER', 'doverkn1_aldie');
		define('DB_PASSWORD', '');
		define('DB_NAME', 'doverkn1_wrdp2');
	}
	
	require_once('DbConnect.php');
	require_once('Ajaxify.php');
	class ReturnObject {}

	function get_action_url($action, $object) { 
		echo action_url($action, $object); 
	}
	function action_url($action, $object) {
		/* Given
				$action {edit | show}
				$object {service | role }
			Returns the correct url f the page that will edit according to the preferences. */
			
			$default_role_id = 3;
			
			$url = "EditBy";
			$url .= (strtoupper(substr($object,0,1)) == "S") ? "Service.php" : "Role.php";						
			$url .= (strtoupper(substr($action,0,1)) == "E") ? "?edit=" : "?show=";
			
			if(strtoupper(substr($object,0,1)) == "S") {
				//EditByService.php?edit=Role_id
				$url .= $default_role_id;
			} else {
				//EditByRole.php?edit=date_id
				$url .= get_current_date_id();
			}
			
			return $url;
	}

	function get_roles_as_options() {
		$qry = "SELECT * FROM cel_roles";

		$ret  = new ReturnObject();
		$ret->participants = array();		
		$res = mysql_query($qry) or die( mysql_error() );
		
		while ($row = mysql_fetch_assoc($res)) {
			echo "<OPTION value='" . $row['role_id'] . "' >" . $row['description'] ."</OPTION>\n";
		}		
	}
	
	function get_editbody_editbyrole( $role_id ) {
		/* Given the role that you want to edit (e.g. '3' for LEM)
			Returns a Body that that lets you edit teh participants for each service */
	
		//Build a table of dates
		$qry = "SELECT * FROM cel_dates WHERE service_date >= CURDATE() AND service_date <= ADDDATE( CURDATE(), INTERVAL 12 WEEK)";		
		$res = mysql_query($qry) or die( mysql_error() );
		$opts_role = participant_options_for_role($role_id);
		
		while ($row = mysql_fetch_assoc($res)) {
			echo "<DIV class='date_row' date_id='" . $row['date_id'] ."'>\n";
			echo "	<DIV class='label' >\n";
			echo "		<SPAN class='time'>" . $row['service_offer'] . "</SPAN> ";
			echo  " 		<SPAN class='date'>". $row['service_date'] . "</SPAN>";
			echo "		<SPAN class='descrip'>" . $row['description'] . "</SPAN>";
			echo "	</DIV>\n";
			echo "	<DIV class='answer'>";
			
			echo "		<SELECT id='p_id'>" . $opts_role . "</SELECT>";

			echo "		<LABEL></LABEL>";
			
			echo"	</DIV>\n";
			echo "</DIV>\n";	
		}
	}
	
	function participant_options_for_role($role_id) {
	
		//Get the list of potential role fufillers
		$role_id = mysql_real_escape_string($role_id);
		$qry = "SELECT * FROM vw_volunteers WHERE role_id = $role_id ORDER BY name";
		$res = mysql_query($qry) or die( mysql_error() );
		
		$opts_role = "<OPTION value=''> -- Select participant -- </OPTION>";
		while ($row = mysql_fetch_assoc($res)) {
			$opts_role .= "<OPTION value='" . $row['p_id'] . "' email='" . $row['email'] . "'  ";
			//$opts_role .= ( ($default_pid == $row['p_id']) ? "SELECTED": "");
			$opts_role .=">" . $row['name'] ."</OPTION>\n";
		}
		
		return $opts_role;
	}
	
	function get_default_for_role($role_id) { 
		echo default_for_role($role_id); 
	}
	function default_for_role($role_id) {
		
		$role_id = mysql_real_escape_string($role_id);
		$qry_def = "SELECT default_pid FROM cel_roles WHERE role_id = $role_id";
		$res = mysql_query($qry_def) or die($qry_def . ": " . mysql_error());
		$row = mysql_fetch_array($res);
		return $row[0];
	}
	
	function get_participant_options_for_role($role_id) {
		//Returns a series of <OPTION value='{{p_id}}'>Person's Name</OPTION> for those willing to perform the passed $role
		
		echo participant_options_for_role($role_id);
	}
	
	function get_options_for_dateid($num_weeks, $from_date) {
		$num_weeks = mysql_real_escape_string($num_weeks);
		$from_date = (is_null($from_date) || $from_date=='') ? " CURDATE() " : "'" . mysql_real_escape_string($from_date) . "'";
		$qry = "SELECT * FROM cel_dates WHERE service_date >= $from_date AND service_date <= ADDDATE($from_date, INTERVAL $num_weeks WEEK) ORDER BY service_date, service_offer";
		//echo $qry;
		
		$res = mysql_query($qry) or die( mysql_error() );
		while ($row = mysql_fetch_assoc($res)) {
			echo "<OPTION value='" . $row['date_id'] . "' service='" . $row['service_offer'] ."'>" . $row['service_date'] . " " . $row['service_offer'] . " ( " . $row['description'] . " )</OPTION>\n";
		}						
	}
	
	function get_editbody_editbyservice(  ) {
		/* Returns an empty editbody showing all roles (and a dropdown of suitable participants */
		
		$qry = "SELECT role_id, description FROM cel_roles";
		$res = mysql_query($qry) or die(mysql_error());
		while ($row = mysql_fetch_assoc($res)) {
			echo "<DIV class='job_row' role_id='" . $row['role_id'] ."'>\n";
			echo "	<DIV class='label' >" . $row['description'] . "</DIV>\n";
			echo "	<DIV class='answer'>";
			
			echo "		<SELECT id='p_id'>";
			echo participant_options_for_role($row['role_id']);
			echo "		</SELECT>";

			echo "		<LABEL></LABEL>";

			echo "	</DIV>\n";
			echo "</DIV>";
		}
	}
	
	function get_participants($date_id) {
		/* Returns a JSON object of participants for the passed date_id 
			obj->participants[x]->date_id
										->role_id
										->p_id
						->service_date (e.g. '2010-10-03')
						->service_offer (e.g. '10a')
						->service	(e.g. 'Regular Service')
						->description (role = e.g. Preacher)
						->name (e.g. 'Michael H.')
						->email (e.g. 'mch@dverknoll.com')
		*/
						
		$date_id = mysql_real_escape_string($date_id);
		$ret  = new ReturnObject();
		$ret->participants = array();
		
		$qry = "SELECT * FROM vw_signups WHERE date_id=$date_id";
		$res = mysql_query($qry) or die( mysql_error() );
		
		while ($row = mysql_fetch_assoc($res)) {
			array_push($ret->participants, $row);
		}
		
		echo json_encode($ret);
	}
	
	function get_current_date_id() {
		$qry = "SELECT date_id FROM cel_dates WHERE service_date >= CURDATE() ORDER BY service_date ASC LIMIT 1";
		$res = mysql_query($qry) or die( mysql_error() );
		$row = mysql_fetch_array($res);
		return $row[0];
	}
	
	function get_date_id($from_date, $to_date) {
		/* Given
			optionally a from and/or to date, 
		returns the date ids of services on those dates */
		
		$from_date = mysql_real_escape_string($from_date);
		$to_date = mysql_real_escape_string($to_date);
		
		$qry = "SELECT date_id FROM cel_dates WHERE ";
		if(!is_null($from_date)) $qry .= " service_date >= '$from_date' ";
		if(!is_null($from_date) && !is_null($to_date)) $qry .= " AND ";
		if(!is_null($to_date)) $qry .= " service_date <= '$to_date' ";
		
		$date_ids = array();		
		$res = mysql_query($qry) or die( mysql_error() );
		
		//echo $qry;
		
		while ($row = mysql_fetch_array($res)) {
			//echo $row[0];
			array_push($date_ids, $row[0]);
		}
		
		return $date_ids;
	}
	
	function get_date_ids_in_range($from_date, $to_date) {		
		$ret = new ReturnObject();
		$ret->date_ids = get_date_id($from_date, $to_date);
		echo json_encode($ret);
	}
	
	function get_in_role($role_id, $date_id) {
		/* Given
				$role_id		(e.g. 1 for preacher)
				$date_id	
			Returns an object (see get_participants) containing information about the person filling that role for that sunday.
			
			If $date_id is an array, multiple people may be returned, each a new participants object in the return object. */
		$role_id = mysql_real_escape_string($role_id);
		
		if(is_array($date_id)) {
				$qry = "SELECT * FROM vw_signups WHERE role_id=$role_id AND date_id IN (";
				foreach($date_id as $dt_id) {
					$qry .= $dt_id . ", ";
				}
				$qry .= " -1)";						
		} else {			
			$qry = "SELECT * FROM vw_signups WHERE role_id=$role_id AND date_id=" . mysql_real_escape_string($date_id);
		}
		
		$ret  = new ReturnObject();
		$ret->participants = array();		
		$res = mysql_query($qry) or die( mysql_error() );
		
		while ($row = mysql_fetch_assoc($res)) {
			array_push($ret->participants, $row);
		}
		
		echo json_encode($ret);
	}
	
	function get_in_role_in_range($role_id, $from_date, $to_date) {
		$date_ids = get_date_id($from_date, $to_date);
		get_in_role($role_id, $date_ids);
	}
	
	function get_participants_by_role( $role_id, $date_ids ) {
		$role_id = mysql_real_escape_string($role_id);
		$date_ids = stripslashes($date_ids);
		$data = json_decode($date_ids);
		
		$qry = "SELECT s.date_id, s.role_id, s.p_id, p.name FROM cel_signups s LEFT JOIN cel_participants p ON p.p_id = s.p_id WHERE date_id IN (";
		
		foreach($data as $date_id) {
			$qry .= $date_id . ", ";
		}
		
		$qry .= " -1) AND role_id=$role_id";
		$res = mysql_query($qry) or die ( $qry . ": " . mysql_error() );
		$ret = new ReturnObject();
		$ret->rows = array();
		while ($row = mysql_fetch_assoc($res) ) {
			array_push($ret->rows, $row);
		}
		
		echo json_encode($ret);
	}
	
	function get_participants_by_date( $date_id, $role_ids ) {
		$date_id = mysql_real_escape_string($date_id);
		$role_ids = stripslashes($role_ids);
		$data = json_decode($role_ids);
		
		$qry = "SELECT s.date_id, s.role_id, s.p_id, p.name FROM cel_signups s LEFT JOIN cel_participants p ON p.p_id = s.p_id WHERE role_id IN (";
		
		foreach($data as $role_id) {
			$qry .= $role_id . ", ";
		}
		
		$qry .= " -1) AND date_id=$date_id";
		$res = mysql_query($qry) or die ( $qry . ": " . mysql_error() );
		$ret = new ReturnObject();
		$ret->rows = array();
		while ($row = mysql_fetch_assoc($res) ) {
			array_push($ret->rows, $row);
		}
		
		echo json_encode($ret);
	}

?>