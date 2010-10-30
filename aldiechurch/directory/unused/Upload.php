<body>
	<?
		set_include_path('..');
		include("DbConnect.php");	
		if (!isset($_REQUEST["submit"])) {
	?>
		<form method="POST" action="<?= $_SERVER["PHP_SELF"] ?>" enctype="application/x-www-form-
		urlencoded">
			<table>
				<tr>
					<td>Family</td>
					<td>
						<select name="famlyid">
							<?php
								$qry = "SELECT famlyid, CONCAT(famlyname, ' -- ' , IF(ISNULL(parent_first_names),'',parent_first_names), ',' , IF(ISNULL(kid_first_names),'',kid_first_names)) FROM vw_names";
								$res = mysql_query($qry) or die($qry . ": " . mysql_error());
								while( $row = mysql_fetch_array($res) ) {
									echo "<OPTION value='" . $row[0] . "'>" . $row[1] . "</OPTION>";
								}			
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>File</td>
					<td><input type="file" name="imgfile"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" name="submit" value="upload">
						<input type="reset">
					</td>
				</tr>
			</table>
		</form>
	<?
	//-- save image to db --
	} else {
		$famlyid = mysql_real_escape_string($_POST['famlyid']);
	
		  $hndl=fopen($_REQUEST["imgfile"],"r");
		  $isize=sizeof($_REQUEST["imgfile"]);
		
		  $imgdata="";
		  while(!feof($hndl)){
			    $imgdata.=fread($hndl,$isize);
		  };
		   
		  $imgdata=addslashes($imgdata);		
		  $qry = "UPDATE dir_families SET picture='". $imgdata ."' WHERE famlyid=" . $famlyid;
		
		  mysql_query($qry) or die($qry . ": " . mysql_error() );		
		  fclose($hndl);
		
		  echo "<a href=\"ViewImage.php\">view image</a>";
	};
?>
</body> 