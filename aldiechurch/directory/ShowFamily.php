<?php
	set_include_path('..');
	require_once('login_funcs.php');
	require_once('Directory.php');
	require('wp-blog-header.php');
	$theme_base = 'wp-content/themes/twentyten';
	include($theme_base . '/header.php');
	require_once('DbConnect.php');
	
	/* Based on the LIST parameter in the url, find the view that renders the desired list */
	if (isset($_GET['fam'])) {
		$famlyid = mysql_real_escape_string($_GET['fam']);
	
		//$qry_contacts = "SELECT * FROM directory_contacts WHERE famlyid=$famlyid;
		
	}  else {
		//If no paramter, this page is invalid.
		header('Location: ShowList.php');	
	}
	
	include('authorize.php');
	
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
		<STYLE type='text/css'>
			.disclaimer { font-size: 80% }
			.leftcol {
				position: relative;
				display: block;
				float: left;
				width: 250px;
				left: 0px;
			}
			.rightcol {
				position: relative;
				display: block;
				float: left;
				padding-left: 50px;
				width: 400px;
			}
			.rightcol IMG {
				width: 400px;
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
		</STYLE>
	</HEAD>
	<BODY>
		<DIV id='container'>
			<?php show_family_block($famlyid); ?>
			<DIV class='disclaimer'>
				<p>(c)Copyright 2010.</p>
				<p>The above information is copyrighted by The Church Of Our Redeemer in order to prevent its use for any non-church related activities. This information is provided for the convenience of church members, and is intended for church-related business only.
				This information may not be forwarded in an email, printed, diseminated, collected, sold, used to solicit for business or otherwise transmitted without authorization from the Church of Our Redeemer.</p> 
			</DIV>
		</DIV>
	</BODY>
</HTML>
