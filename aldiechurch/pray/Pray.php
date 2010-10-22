<?php

	set_include_path('..');
	require_once('DbConnect.php');
	require_once('Ajaxify.php');
	class ReturnObject {}

	
	function get_pray_cats_as_options() {
	
			$qry = "SELECT pray_cat, description FROM pray_cats";
			$res = mysql_query($qry) or die($qry . ': ' . mysql_error());
			while($row = mysql_fetch_array($res)) {
				echo "<OPTION id='" . $row[0] . "'>" . $row[1] . "</OPTION>";
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
?>