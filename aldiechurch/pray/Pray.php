<?php

	set_include_path('..');
	require_once('DbConnect.php');
	require_once('Ajaxify.php');
	class ReturnObject {}

	function get_submitter_id($email) {
		//Given an email address, returns the submitter id or "" that corresponds
		$qry = "SELECT submitter_id FROM prayer_submitter WHERE email='" . mysql_real_escape_string($email) ."';";
		$res = mysql_query($qry) or die($qry .": " . mysql_error);
		if(mysql_affected_rows() == 0) {
			return "NEW";
		}
		
		$row = mysql_fetch_array($res);
		return $row[0];
	}
	
	function ajax_get_submitter_id($email) { echo get_submitter_id($email); }
	
	function get_pray_cats_as_options() {
	
			$qry = "SELECT pray_cat, description FROM pray_cats";
			$res = mysql_query($qry) or die($qry . ': ' . mysql_error());
			while($row = mysql_fetch_array($res)) {
				echo "<OPTION value='" . $row[0] . "'>" . $row[1] . "</OPTION>";
			} 
	}
	
	function get_prayer_details($pray_id) {
		/* Returns enough JSON data to populate the ShowPrayer.php page */
		$pray_id = mysql_real_escape_string($pray_id);
		$ret = new ReturnObject();
		
		$qry = "SELECT * FROM vw_prayers p WHERE p.pray_id = $pray_id";
		$res = mysql_query($qry) or die($qry . ": " . mysql_error());
		$ret->row = mysql_fetch_assoc($res);
		
		return $ret;
	}
	
	function ajax_get_prayer_details($pray_id) {
		echo json_encode(get_prayer_details($pray_id));
	}
	
	function ajax_public_prayer_list() {
		echo json_encode(get_prayer_list("true"));
	}
	
	function ajax_private_prayer_list() {
		echo json_encode(get_prayer_list("false"));
	}
	
	function get_prayer_list($is_public) {
	
		$qry = "SELECT pray_id, display, is_member, pray_cat FROM pray_main WHERE is_public=" . mysql_real_escape_string($is_public) . " ORDER BY pray_cat ASC, is_member DESC, display";

		$ret = new ReturnObject();
		$ret->rows = array();
		
		$res = mysql_query($qry) or die($qry . ": " . mysql_error());
		while($row = mysql_fetch_assoc($res)) {
			array_push($ret->rows, $row);
		}
		
		return $ret;
	}
		
	function html_dateseperated_prayerlist($pg, $show_privates) {
		/* Returns html of the public prayer list, seperated into each day of the week.  Output looks like this:
			<DIV class='prayerrow mon ismember' pray_id='1'>
				<a href='$pg?show=1'>Lisa Livermore</a>
			</DIV>
			<DIV class='prayerrow moday' pray_id='2'> etc....
		where up to four classes will be added for each prayer item:
			prayerrow 			(always)
			day of the week (e.g. 'mon', 'tue', 'wed, 'thr', 'fri') for date separation
			praycat_##		where ## is the pray_cat_id of the prayer category	
			ismember			if the person is a church member */
			
		if(!isset($show_privates) | $show_privates=="") {
			$show_privates = false;
		}
			
		//echo $show_privates ? "Showing Privates": "Not";
		
		$qry = "SELECT pray_cat, description FROM pray_cats";
		$res = mysql_query($qry) or die($qry . ": " . mysql_error());
		$pray_cats = array("");
		while($row = mysql_fetch_assoc($res)) {
			array_push($pray_cats, $row["description"]);
		}
		
		$day_classes = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
		$prayer = get_prayer_list( ($show_privates) ? "false":"true" );
		$entries_per_day = ceil(count($prayer->rows)) / count($day_classes);
		//echo "Showing $entries_per_day entries per day";
		$day_count = 0;
		
		$prev_day_of_week = -1;
		$prev_pray_cat = 0;
		
		echo "<ul>\n";
		foreach($prayer->rows as $prayerrow) {
		
			if( $prayerrow["pray_cat"] != $prev_pray_cat) {
				echo "<DIV class='heading pray_cat'>";
				echo $pray_cats[$prayerrow["pray_cat"]];
				echo "</DIV>\n";
			}
			
			
			$day_of_week = floor($day_count / $entries_per_day);
			if($day_of_week != $prev_day_of_week) {
				echo "<DIV class='heading day_of_week'>";
				echo $day_classes[$day_of_week];
				echo "</DIV>\n";
			}
		
			echo "<DIV class=\"prayerrow ";			
			echo strtolower(substr($day_classes[$day_of_week],0,3));
			echo " praycat_" . $prayerrow["pray_cat"] . " ";
			echo (($prayerrow["is_member"] == 1) ? " is_member " : "") . " \">";
			
			echo "<li><a href= \"$pg?show=" .$prayerrow["pray_id"]. "\" >".$prayerrow["display"] . "</a></li>";
			
			echo "</DIV>\n";
			
			$day_count++;
			$prev_day_of_week = $day_of_week;
			$prev_pray_cat =  $prayerrow["pray_cat"];
		}
		
		echo "</ul>";
		
	}
	
	function save_prayers( $save_data ) {
	
		$save_data = json_decode(stripslashes($save_data));
		
		mysql_query("START TRANSACTION") or die(mysql_error());
		
		//Pray_Main
		$clause = "display='" . mysql_real_escape_string($save_data->display) . "', ";
		$clause .= "is_member='" . (($save_data->is_member)  ? "1":"0") . "', ";
		$clause .= "is_public='" . (($save_data->is_public)  ? "1":"0") . "', ";
		$clause .= "details='" . mysql_real_escape_string($save_data->details) . "', ";
		$clause .= "pray_cat='" . mysql_real_escape_string($save_data->pray_cat) . "' ";
		save_where( 'pray_id', $save_data->prayer_id, "pray_main", $clause);
		$save_data->prayer_id = mysql_insert_id();

		//Prayer_Submitter - if no ID
		if($save_data->submitter_id == "") {
			$clause = "name='" . mysql_real_escape_string($save_data->name) . "', ";
			$clause .= "email='" . mysql_real_escape_string($save_data->email) . "' ";
			save_where( 'submitter_id', $save_data->submitter_id, "pray_main", $clause);
			$save_data->submitter_id = mysql_insert_id();

		}
		
		//Pray_sub - need submitter_id
		$clause = "pray_id='" . mysql_real_escape_string($save_data->prayer_id) . "', ";
		$clause .= "submitter_id='" . mysql_real_escape_string($save_data->submitter_id) . "', ";
		$clause .= "relationship='" . mysql_real_escape_string($save_data->relationship) ."'";
		save_where( 'pray_id',$save_data->prayer_id, "pray_sub", $clause);
		
		
		//Pray_lifespan
		$clause = "pray_id='" . mysql_real_escape_string($save_data->prayer_id) . "', ";
		$clause .= "valid_until='" . mysql_real_escape_string($save_data->valid_until) . "', ";
		$clause .= "valid_from=CURDATE() ";
		save_where( 'pray_id',$save_data->prayer_id, "pray_lifespan", $clause);

		mysql_query("COMMIT") or die(mysql_error());
	}
	
	function save_where($keyfld, $key, $table, $update_fields_clause) {
		if($key == "NEW" || $key == "") {
			$qry = "INSERT INTO $table SET {{clause}}";
		} else {
			$qry_exist = "SELECT * FROM $table WHERE $keyfld ='$key' ";
			$res = mysql_query($qry_exist) or die($qry_exist . ": " .mysql_error());
			if(mysql_affected_rows() > 0) {
				$qry = "UPDATE $table SET {{clause}} WHERE $keyfld ='$key' ";
			} else {
				$qry = "INSERT INTO $table SET {{clause}}";
			}
		}
		
		$qry = preg_replace('/{{clause}}/', $update_fields_clause, $qry);
		echo $qry;
		$res = mysql_query($qry) or die($qry . ": " . mysql_error());
		
	}
	
		//Build the SET field clauses...
		//$clause = "";
		//foreach($save_data as $key => $valu) {
		//	$clause .= ", $key='$valu'";
		//}
		
	function deactivate_prayer($prayer_id) {
		$qry = "UPDATE pray_lifespan SET is_active=0 WHERE pray_id = " . mysql_real_escape_string($prayer_id);
		
		//echo $qry;
		echo "This prayer request has been deactivated.  Thank you.  Please click <a href='prayer-list.php'>here</a> to return to the prayer list.";
	}

?>