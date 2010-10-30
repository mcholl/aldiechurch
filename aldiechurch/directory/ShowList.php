<?php
	set_include_path('..');
	require_once('login_funcs.php');
	include('authorize.php');
	
	require_once('Directory.php');
	require('wp-blog-header.php');
	$theme_base = 'wp-content/themes/twentyten';
	include($theme_base . '/header.php');
	require_once('DbConnect.php');
	
	/* Based on the LIST parameter in the url, find the view that renders the desired list */
	if (isset($_GET['LIST'])) {
		$qry = "SELECT * FROM dir_dataviews WHERE url_action = '" . mysql_real_escape_string($_GET['LIST']) . "'";
		$res = mysql_query($qry) or die($qry . ": " . mysql_error());
		if($res) {
			$row = mysql_fetch_assoc($res);
			
			$data_view = $row['data_view'];
			$pre_include = $row['pre_include'];
			$post_include = $row['post_include'];
			$menu = $row['menu'];
		}
	}  
	
	if(!isset($data_view)) {
		/* Default action = addresses */
		$data_view = 'vwhtml_famaddress';
		$menu = "Addresses";
		$pre_include = '';
		$post_include = '';
	}
		
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
				top: 20px;
				width: 200px;
				border: 1px dotted black;
			}
			
			DIV#directory_menu p {
				text-align: center;
			}
		</STYLE>
	</HEAD>
	<BODY>
	
		<DIV id='directory_menu'>
			<p>Available Directory Sorts</p>
			<ul>
				<li><a href='PhotoDirectory.php'>Photo Directory</a></li>
				<?php  directory_menu(); ?>
			</ul>
		</DIV>
		
		<DIV id='container'>
		<?php 
			//echo 'Looking for ' . $pre_include;
			if(is_null($pre_include)) {
				include('DefaultListHeader.php');
			} else if(file_exists($pre_include)) {
				//echo 'Found ' . $pre_include;
				include($pre_include); 
			}
		
			$qry = "SELECT * FROM " . $data_view;
			$res = mysql_query($qry) or die($qry . ": " . mysql_error());
			while($row = mysql_fetch_array($res)) {
				echo "<DIV class='row'>";
				echo $row[0];
				echo "</DIV>";
			}
		
			if(file_exists($post_include)) {
				include($post_include); 
			}
		?>
			<DIV class='disclaimer'>
				<p>(c)Copyright 2010.</p>
				<p>The above information is copyrighted by The Church Of Our Redeemer in order to prevent its use for any non-church related activities. This information is provided for the convenience of church members, and is intended for church-related business only.
				This information may not be forwarded in an email, printed, diseminated, collected, sold, used to solicit for business or otherwise transmitted without authorization from the Church of Our Redeemer.</p> 
			</DIV>
		</DIV>
	</BODY>

</HTML>

