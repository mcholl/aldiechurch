<?php

	set_include_path("..");
	include("DbConnect.php");
	
 	if(isset($_GET['import_data'])) {
		read_file_data($_GET['import_data']);
	}
	
	function read_file_data($filename) {
		$data = file_get_contents($filename);
		$data = fix_memplus_xml($data);
		import_data($data);
	}
	
	function fix_memplus_xml($xml_string) {
		/* When Membership Plus 11 exports contact data as xml, 
		they spit out invalid Xml. Specifically, contact info has tags 
		like <Home_(Voice)> which causes any reasonable parser
		to fail. This function uses regular expressions to translate 
		that into <Voice loc='Home'> */

		$regex_find = "/E-mail/"; //e.g. "E-mail"
		$replace = "Email";	//e.g. "<Email>"
		$xml_string = preg_replace($regex_find, $replace, $xml_string); 
		
		$regex_find_open_tag = "<(([A-za-z]+)_\(([A-za-z]+)\))>"; //e.g. "<Home_(Voice)>"
		$replace_opentag = "$3 loc='$2' class='contact'";	//e.g. "<Voice loc='Home' class='contact'>"
		$xml_string = preg_replace($regex_find_open_tag, $replace_opentag, $xml_string); 
		
		$regex_find_close_tag = "</([A-za-z]+) loc=\'[A-za-z]+\' class='contact'>"; //e.g. "</Voice type='Home'>"
		$replace_closetag = "/$1";	//e.g. "</Voice>"
		$xml_string = preg_replace($regex_find_close_tag, $replace_closetag, $xml_string);
		
		return $xml_string;		
	}
	
	function import_data($xml_string) {
		/* Takes the results of the Membership Plus Xml Export
			and populates the read-only directory table structure
			for the membership directory */
		$xml = new SimpleXMLElement($xml_string);
		
		foreach($xml->children() as $record) {
			mysql_query("START TRANSACTION") or die(mysql_error());
				
			if(is_family_record($record)) {
				import_family_record($record);
			} else {
				import_member_record($record);
			}
			
			if($record->ADDRID) {
				import_address_record($record);
			}
			
			//Import All Contact Records - once for each member and onc for each family unit
			$MBRID = mysql_real_escape_string($record->MBRID);
			$FAMLYID = mysql_real_escape_string($record->FAMLYID);
			
			$contact_nodes = $record->xpath("*[@class='contact']");
			foreach($contact_nodes as $contact) {
				import_contact_record($MBRID, $contact);
				import_contact_record($FAMLYID, $contact); //Makes it availble to the family as a whole
			}

			mysql_query("COMMIT") or die(mysql_error());
			//echo "<BR />";
		}

		echo count($xml->children()) . " records";

	}
	
	function import_family_record($record) {
			/* Family Record */
			/*<RECORD>
					 <MBRID>1</MBRID>
					 <FAMLYID>1</FAMLYID>
					 <FAMLYNAME>Hollinger</FAMLYNAME>
					 <ANNIVERSARY>7/11/1998</ANNIVERSARY>
			</RECORD>
			CREATE TABLE dir_families (
				FAMLYID			int,
				FAMLYNAME	varchar(32),
				ANNIVERSARY	date,
				picture			BLOB,
				PRIMARY KEY(FAMLYID)
			);
			*/
			
			$tbl = "dir_families";
			$key = "FAMLYID = '" . mysql_real_escape_string($record->FAMLYID) ."'";
			$clause = "SET ";
			$clause .= "FAMLYID = '" . mysql_real_escape_string($record->FAMLYID) ."', ";
			$clause .= "FAMLYNAME = '" . mysql_real_escape_string($record->FAMLYNAME) ."', ";
			$clause .= "ANNIVERSARY = '" . sql_date($record->Anniversary) ."' ";
			save_where($tbl, $key, $clause);
	}
	
	function import_member_record($record) {
			/*Member Record */
			/*<RECORD>
					 <MBRID>5</MBRID>
					 <FIRSTNAME>Jonathan</FIRSTNAME>
					 <LASTNAME>Hollinger</LASTNAME>
					 <TYPEOFMBR>4</TYPEOFMBR>
					 <FAMLYID>1</FAMLYID>
					 <Birthday>8/10/2005</Birthday>
			</RECORD>*/
		//echo "<BR />MBRID = ";
		//echo $record->MBRID;
		
			$tbl = "dir_members";
			$key = "MBRID = '" . mysql_real_escape_string($record->MBRID) ."'";
			$clause = "SET ";
			$clause .= "MBRID = '" . mysql_real_escape_string($record->MBRID) ."', ";
			$clause .= "TYPEOFMBR = '" . mysql_real_escape_string($record->TYPEOFMBR) ."', ";
			$clause .= "FAMLYID = '" . mysql_real_escape_string($record->FAMLYID) ."', ";
			$clause .= "FIRSTNAME = '" . mysql_real_escape_string($record->FIRSTNAME) ."', ";
			$clause .= "LASTNAME = '" . mysql_real_escape_string($record->LASTNAME) ."', ";
			$clause .= "birthday = '" . sql_date($record->Birthday) ."'"; 
			save_where($tbl, $key, $clause);
	
			if( sql_date($record->Anniversary) != "") {
				save_where("dir_families", "FAMLYID = '" . mysql_real_escape_string($record->FAMLYID) ."'",  "SET ANNIVERSARY = '" . sql_date($record->Anniversary) ."' ");
			}
	}
	
	function import_address_record($record) {
			/*
				 <ADDRID>1</ADDRID>
				 <ADDRLN1>23811 Champe Ford Road</ADDRLN1>
				 <CITY>Middleburg</CITY>
				 <STATE>VA</STATE>
				 <ZIPCODE>20117</ZIPCODE>
				*/
			$tbl = "dir_member_address";
			$key = "MBRID = '" . mysql_real_escape_string($record->MBRID) ."' AND ADDRID = '" . mysql_real_escape_string($record->ADDRID) ."'";
			$clause = "SET ";
			$clause .= "MBRID = '" . mysql_real_escape_string($record->MBRID) ."', ";
			$clause .= "ADDRID = '" . mysql_real_escape_string($record->ADDRID) ."' ";
			save_where($tbl, $key, $clause);
			
			$tbl = "dir_addresses";
			$key = "addrid = '" . mysql_real_escape_string($record->ADDRID) ."'";
			$clause = "SET ";
			$clause .= "ADDRID = '" . mysql_real_escape_string($record->ADDRID) ."', ";
			$clause .= "ADDRLN1 = '" . mysql_real_escape_string($record->ADDRLN1) ."', ";
			$clause .= "CITY = '" . mysql_real_escape_string($record->CITY) ."', ";
			$clause .= "STATE = '" . mysql_real_escape_string($record->STATE) ."', ";
			$clause .= "ZIPCODE = '" . mysql_real_escape_string($record->ZIPCODE) ."' ";
			save_where($tbl, $key, $clause);				
	}
	
	function import_contact_record($MBRID, $contact_node) {
		/* e.g. 	<Voice type='Home'>(703)555-1212</Voice>
		or			<Email type='Work'>bill@microsoft.com</Email> 
				Goes into dir_contacts
						MBRID			int,
						contactloc		varchar(4),
						contacttyp		varchar(10),
						contactinf		varchar(32)
			*/
		
			$typ = $contact_node->getName();
			$atts = $contact_node->attributes();
			$loc = $atts['loc'];
			
			$tbl = "dir_contacts";
			$key = "MBRID = '" . $MBRID ."' AND contactloc = '$loc' AND contacttyp='$typ'";
			$clause = "SET ";
			$clause .= "MBRID = '" . $MBRID ."', ";
			$clause .= "contacttyp = '$typ', ";
			$clause .= "contactloc = '$loc', ";
			$clause .= "contactinf = '$contact_node'";
			save_where($tbl, $key, $clause);				

	}
	
	function save_where($tbl, $key, $clause) {
		/* Warning: Unsafe function! */
		
		$key_qry = "SELECT * FROM $tbl WHERE $key";
		$res = mysql_query($key_qry) or die($key_qry .": " . mysql_error());
		if (mysql_affected_rows()==0) {
			$qry = "INSERT INTO $tbl $clause";
		} else { 
			$qry = "UPDATE $tbl $clause WHERE $key";
		}
		//echo "<BR />".$qry;
		
		mysql_query($qry) or die($qry . ": " . mysql_error());
	}
	
	function is_family_record($record) {
		if (!isset($record->FAMLYID)) {
			return false;
		}
		if(!isset($record->MBRID)) {
			return false;
		}
		
		if (strtolower($record->FAMLYID) == strtolower($record->MBRID)) {
//			echo $record->FAMLYID . "==" . $record->MBRID . "<BR />";
			return true;
		}
		
		return false;
	}
	
	function sql_date($raw_date) {
		/* Given a unsafe date in the form mm/dd/yyyy
		returns YYYY-MM-DD */
		
		$the_date = trim(mysql_real_escape_string($raw_date));
		if(strlen($the_date) >=4) {
			$parts = explode('/', $the_date);
			if(count($parts)!=3) {
				//echo "Not enough parts $the_date";
				return $the_date;
			}
			$m = $parts[0];
			$d = $parts[1];
			$y = $parts[2];
			if($y == 'xxxx') {
				$y = "1801";
			}
			$the_date = $y . "-" . $m . "-" . $d;
			//echo "<BR />" . $the_date;
		}
		
		return $the_date;
	}


				/* Full Family Record
				<RECORD>
						 <MBRID>1</MBRID>
						 <TYPEOFMBR>2</TYPEOFMBR>
						 <FAMLYID>1</FAMLYID>
						 <FAMLYNAME>Hollinger</FAMLYNAME>
						 <NAME>The Hollinger Family</NAME>
						 <SORTNAME>HOLLINGER</SORTNAME>
						 <MAILINGLBL>The Hollinger Family</MAILINGLBL>
						 <PHONELABEL>Home</PHONELABEL>
						 <PHONELIST>TRUE</PHONELIST>
						 <ADDRID>1</ADDRID>
						 <ADDRLN1>23811 Champe Ford Road</ADDRLN1>
						 <CITY>Middleburg</CITY>
						 <STATE>VA</STATE>
						 <ZIPCODE>20117</ZIPCODE>
						 <ANNIVERSARY>7/11/1998</ANNIVERSARY>
						 <Home_(Voice)>(540)687-3299</Home_(Voice)>
						 <Work_(E-mail)>mcholl@yahoo.com</Work_(E-mail)>
				</RECORD>*/
				/* Full Member Record
				<RECORD>
						 <MBRID>5</MBRID>
						 <FIRSTNAME>Jonathan</FIRSTNAME>
						 <LASTNAME>Hollinger</LASTNAME>
						 <TYPEOFMBR>4</TYPEOFMBR>
						 <FAMLYID>1</FAMLYID>
						 <FAMLYNAME>Hollinger</FAMLYNAME>
						 <NAME>Jonathan Hollinger</NAME>
						 <SORTNAME>HOLLINGER JONATHAN</SORTNAME>
						 <MAILINGLBL>Jonathan Hollinger</MAILINGLBL>
						 <PHONELABEL>Home</PHONELABEL>
						 <PHONELIST>TRUE</PHONELIST>
						 <ADDRID>1</ADDRID>
						 <ADDRLN1>23811 Champe Ford Road</ADDRLN1>
						 <CITY>Middleburg</CITY>
						 <STATE>VA</STATE>
						 <ZIPCODE>20117</ZIPCODE>
						 <Birthday>8/10/2005</Birthday>
						 <Home_(Voice)>(540)687-3299</Home_(Voice)>
						 <Work_(E-mail)>mcholl@yahoo.com</Work_(E-mail)>
				</RECORD>*/

?>