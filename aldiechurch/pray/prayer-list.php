<!DOCTYPE html>
<HTML>
	<?php
		set_include_path('pray' . PATH_SEPARATOR . '..');
		include('authorize.php');
		
		require_once('login_funcs.php');
		require_once('wp-blog-header.php');
		require_once('Pray.php');
		
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	?>
	
	<HEAD>
		<TITLE>Prayer List</TITLE>
		<STYLE type='text/css' >
			.is_member {
				font-style: italic;
			}
			
			DIV.pray_cat {
				margin-top: 20px;
				font-size: 130%;
				font-style: bold;
			}
			
			DIV.day_of_week {
				margin-top: 10px;
				font-size: 110%;
				font-style: italic;
			}
			
			li {
				margin-left: 1em;
			}
			
			DIV.day_break {
				display: block;
				float: left;
				width: 20%;
			}
			DIV#Emptyday { 
				display: none;
			}
			ul {
				margin: 0;
				-moz-padding-start:0px;
				width: 100%;
				list-style: none;
			}
			
		</STYLE>
	</HEAD>
	<BODY>
	
		<DIV id='container'>
			<H1>Church of Our Redeemer Prayer List</H1>
			<a href='EditRequest.php?new'>Click here to submit a prayer request</a>
			<a href='http://bcponline.org/HE/pop.htm' target='_new'>Click here to see the Prayers of the People (BCP 1979)</a>
			
			<DIV id='prayer_list'>
				<?php
					html_dateseperated_prayerlist('ShowRequest.php', false);
				?>
			</DIV>
		</DIV>
			
		<?php
				include($theme_base . '/footer.php');
		?>
	</BODY>
</HTML>