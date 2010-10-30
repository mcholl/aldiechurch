<?php
	set_include_path("directory" . PATH_SEPARATOR . "..");
	include('authorize.php');
	require_once('login_funcs.php');
	require_once('Directory.php');
	require('wp-blog-header.php');
	
	$theme_base = 'wp-content/themes/twentyten';
	include($theme_base . '/header.php');
	require_once('DbConnect.php');
	
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
		<TITLE>Birthday List</TITLE>
		<STYLE type='text/css'>
			DIV.content {	display: table; }
			DIV.row {display: table-row; }
			SPAN.names { display: table-cell; }
			SPAN.address { display: table-cell; }
			
			.disclaimer { font-size: 80% }
			
			DIV#directory_menu {
				position: fixed;
				background-color: white;
				left: 20px;
				top: 27px;
				width: 200px;
				border: 1px dotted black;
			}
			
			DIV#directory_menu p {
				text-align: center;
			}
			
			DIV#blocks {
				display: block;
				float: left;
				width: 100%;
			}
			
			DIV#family_block {
				position: relative;
				display: block;
				float: left;
				height: 350px;
				width: 220px;
				border: 3px double black; 
				padding: 5px;
				margin-right: 3px;
				margin-top: 3px;
			}
			
			DIV#family_block .leftcol {
				position: absolute;
				top: 175px;
			}
			DIV#family_block .rightcol {
				position: absolute;
				top: 0px;
			}
			
			DIV#family_block IMG {
				width: 190px;
				margin: 10px;
				top: 10px;
			}
			DIV#family_block IMG.nopic {
				top: 10px;
				width: 100px;
				
			}
			
			p { 
				margin: 0px;
				padding: 0px;
			}

			.lastname {
				font-size: 24px;
				padding-bottom: 5px;
			}
			.parents, .kids {
				font-size: 16px;
			}
			
			DIV.contacts, p.address {
				padding-top: 20px;
			}
			
			DIV.contacts { display: table; }
			DIV.contacts p {display: table-row; }
			DIV.contacts SPAN.label, DIV.contacts SPAN.contact {
				display: table-cell;
				width: 150px;
			}
			
			DIV#container {
				height: auto;
			}
			
			DIV#pages {
				position: fixed;
				top: 10px;
				left: 20px;
				border: 2px solid black;
				background-color: rgb(249,195,108);
				opacity: 100%;
				z-index: 2;
			}

		</STYLE>
	</HEAD>
	<BODY>
	
		<DIV id='directory_menu'>
			<p>Available Directory Sorts</p>
			<ul>
				<li><a href='PhotoDirectory.php'>Photo Directory</a></li>
				<?php  
					directory_menu(); 
				?>
			</ul>
		</DIV>
		
		<DIV id='container'>
			<DIV id='pages'>
				<?php
					$a_pages = array('A-C', 'D-G', 'H-K', 'L-N', 'O-R', 'S-V', 'W-Z');
					foreach($a_pages as $pg) {
						echo "<a href='?show=$pg'>" . $pg . "</a>&nbsp;";
					}
				?>
			</DIV>
			<DIV id='blocks'>
			<?php
			
				$qry = "SELECT * FROM vw_names ";
				if( isset( $_GET['show'] ) ){
					$rng = explode('-', $_GET['show']);
					if(count($rng) == 1) {
						$qry .= "WHERE LEFT(famlyname," . strlen($rng[0]) . ")='" . $rng[0] . "'";
					} else if(count($rng) == 2) {
						$qry .= "WHERE LEFT(famlyname," . strlen($rng[0]) . ") >='" . $rng[0] . "' AND LEFT(famlyname," . strlen($rng[1]) . ") <='" . $rng[1] . "'";
					}
				}
				
				$res = mysql_query($qry) or die($qry .": " . mysql_error());
				while($row = mysql_fetch_assoc($res)) {
					echo "<DIV id='family_block'>";
					echo "<a href='ShowFamily.php?fam=" . $row['famlyid'] . "'>";
					show_family_block($row['famlyid']);
					echo "</a>";
					echo "</DIV>";
				}
		
			?>
			</DIV>
			<DIV class='disclaimer'>
				<p>(c)Copyright 2010.</p>
				<p>The above information is copyrighted by The Church Of Our Redeemer in order to prevent its use for any non-church related activities. This information is provided for the convenience of church members, and is intended for church-related business only.
				This information may not be forwarded in an email, printed, diseminated, collected, sold, used to solicit for business or otherwise transmitted without authorization from the Church of Our Redeemer.</p> 
			</DIV>
		</DIV>
	</BODY>

</HTML>

