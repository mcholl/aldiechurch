<!DOCTYPE html>
<HTML>
	<?php
		require_once('login_funcs.php');
		require('wp-blog-header.php');
	?>
	<HEAD>
		<STYLE type='text/css'>
			ul { display: table; }
			li { display: table-row; }
			.page_id { display: table-cell; }
			.url { display: table-cell; }
		</STYLE>
	</HEAD>
	<BODY>
		<ul>
		<?php
			$pgs = get_all_page_ids();
			foreach($pgs as $pg) {
				echo "<li>";
				echo "	<DIV class='page_id'>" . $pg . "</DIV>  ";
				echo " 	<DIV class='url'>http://". $_SERVER['SERVER_NAME'] . '/aldiechurch/' . get_page_uri( $pg ) . "</DIV>"; 
				echo"</li>";
			}
		?>
		</ul>
			
	</BODY>
</HTML>