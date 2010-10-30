<?php

		set_include_path('..');
		require_once('DbConnect.php');

		function show_family_block($famlyid) {
			$qry_names = "SELECT * FROM vw_names WHERE famlyid=" . $famlyid;		
			$res_names = mysql_query($qry_names) or die($qry_names . ": " . mysql_error());
			$row_names = mysql_fetch_assoc($res_names);
			
			$qry_address = "SELECT a.* FROM dir_addresses a LEFT JOIN dir_member_address ma ON ma.addrid=a.addrid WHERE ma.mbrid=" . $famlyid; 
			$res_address = mysql_query($qry_address) or die($qry_address . ": " . mysql_error());
			$row_address = mysql_fetch_assoc($res_address);
			
	
			echo "<DIV class='leftcol'>";
			echo "	<p class='lastname'>" . $row_names['famlyname'] . "</p>";
			echo "	<p class='parents'>" . $row_names['parent_first_names'] . "</p>";
			echo "	<p class='kids'>" . $row_names['kid_first_names'] ."</p>";
			echo "	<p class='address'>" . $row_address['addrln1']. "<br />";
			echo $row_address['city'] .", " .$row_address['state'] ." " .$row_address['zipcode']."</p>";
			
			echo "	<DIV class='contacts'>";
	
			$qry_contacts = "SELECT * FROM vw_fam_contacts WHERE famlyid=$famlyid";
			$res_contacts = mysql_query($qry_contacts) or die($qry_contacts . ": " . mysql_error());
			while( $row = mysql_fetch_assoc($res_contacts)) {
				echo "<p class='contact'>";
				echo 	"    <SPAN class='label'>" . $row['contactloc'] . " " . $row['contacttyp'] . "</SPAN>";
				echo "    <SPAN class='address'>" . $row['contactinf'] . "</SPAN>";
				echo "</p>";
			}
			echo "	</DIV>";
				
			echo "</DIV>";
			echo "<DIV class='rightcol'>";
			$picloc =  $_SERVER['DOCUMENT_ROOT'] .'aldiechurch/directory/pics/' . $famlyid. '.jpg';
			if(file_exists($picloc)) {
				echo "	<IMG src='pics/" . $famlyid . ".jpg'></IMG>";
			} else {
//				echo "<p>" .$picloc . "</p>";
				echo "	<IMG src='pics/NoPic.jpg' class='nopic'></IMG>";
			}
			echo "</DIV>";
		}
		
		function directory_menu() {
			$qrymenu = "SELECT menu, url_action FROM dir_dataviews";
			$resmenu = mysql_query($qrymenu) or die($qrymenu .": " . mysql_error());
			while( $row = mysql_fetch_array($resmenu)) {
				echo "<li><a href='ShowList.php?LIST=".$row['url_action']."'>" . $row['menu'] . "</a></li>";
			}				
		}
		
?>