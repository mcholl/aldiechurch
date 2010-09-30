<?php
	require_once('login_funcs.php');
	require('wp-blog-header.php');
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
		<TITLE>Church of Our Redeemer</TITLE>
	<!--	<base href="http://doverknoll.com/aldiechurch/" /> -->
		<?php
			$theme_base = 'wp-content/themes/twentyten';
			include($theme_base . '/header.php');
		?>

		<STYLE type='text/css'>
			
			/*#prayer, #frontpage_ann, #service_times*/
			#container > DIV, #rightcol > DIV {
				padding: 5px;
				padding-bottom: 15px;
				margin: 5px;
				display: block;
				float: left;
			}

			#rightcol { 				
				width: 390px;
			}
			
			#leftcol {
				height: 60%;
				width: 290px;
			}
			
			#leftcol IMG {
				width: 275px;
			}
			
			#frontpage_ann {
				border: 1px dotted brown;
				background:#FFF3DB url("http://www1.blogblog.com/scribe/bg_paper_mid.jpg") repeat-y;		
				height: 125px;
				width: 100%;
			}

			#frontpage_ann .sec_content a { 	text-decoration: none; }	
			
			.sec_content { padding-left: 5px; 	}
			
			#service_times .sec_content TABLE { 
				font-size: 12px; 
				padding-left: 40px;	
			}
			
			#service_times TD.link { text-align: center; }

			#directions IMG {
				margin-top: 10px;
			}
			
			#prayer p {
				font-family: "Times New Roman", serif;
				text-align: center;
			}
			
			/*:first-child:first-letter*/
			.title {
				 font-size : 130%;
				 font-weight : bold;
				 color : #c00;
				 text-align: center;
				 padding-bottom: 10px;
			}
			
			#prayer .title { 
				margin-top:30px; 
			}
			
			#welcome .title { padding-bottom: 6px; }
			
		</STYLE>
	</HEAD>
	<BODY>

		<DIV id='container'>
			<DIV id='leftcol'>
				<DIV id='welcome'>
					<DIV class='title'>Welcome!</DIV>
					<DIV class="sec_content" >We are thrilled that you chose to visit our home on the web! If you have not already visited with us in person, we hope you will consider doing so soon.</DIV>
				</DIV>
				
				<IMG src='images/Hello.png'></IMG>
				
				<DIV id='prayer'>
					<DIV class='title'>Prayer for Church of Our Redeemer</DIV>
					<p>Lord Jesus, we pray that you would give us <a href='sermons'>ears to hear</a>, <a href=''>hearts to love</a>, and <a href=''>wills devoted to serve</a>.</P>
					<p>That in the days to come, this congregation would be known, not for our glory, but for yours alone.</P>
					<p>That this place would be an oasis, a place where people come to find out about you, where lives are straightened out and made whole;</p>
					<p>a place where people find the peace that passes all understanding. </P>
					<p>Give us, we pray, a heart to desire these things above all else we pray. We pray through Jesus Christ. Amen</p>
				</DIV>
				<BR />
				<BR />
				<BR />
				<BR />
			</DIV>
				
			<DIV id='rightcol'>
				<DIV id='service_times'>
					<DIV class='title'>Service Times</DIV>
					<DIV class='sec_content'>
						<TABLE border='0'>
							<TR><TD width='60px'>Sun 8am</TD><TD>Holy Eucharist (Contemplative Service)</TD></TR>
							<TR><TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10am</TD><TD>Holy Eucharist (with Sunday School & Childcare)</TD></TR>
							<TR><TD>Wed 8am</TD><TD>Holy Eucharist with Service of Healing</TD></TR>
							<TR><TD colspan='2' class='link'><a href='/aldiechurch/schedule-of-services' >For more information, click here</a></TD></TR>
						</TABLE>
					</DIV>
				</DIV>
	
				<DIV id='frontpage_ann'>
					<?php query_posts('category_name=ann&tag=frontpage&showposts=1'); ?> <!-- To Do: Get the 'Most Important Announcement' as deignated by some king of tag. -->
					<?php while (have_posts()) : the_post(); ?>
					<DIV class='title'>
							<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
							<?php the_title(); ?></a>
					</DIV>
					<DIV class='sec_content'>
							<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
							<?php the_content(); ?>
							</a>
					</DIV>
					<?php endwhile;?>
				</DIV>
	
				<DIV id='directions'>
					<DIV class='title'>Driving Directions</DIV>
					<DIV class='sec_content'>We are located at <a href='http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=39518+John+Mosby+Highway,+Aldie,+VA&sll=38.975075,-77.634737&sspn=0.008741,0.022724&ie=UTF8&hq=&hnear=39518+John+Mosby+Hwy,+Aldie,+Loudoun,+Virginia+20105&ll=38.975225,-77.636969&spn=0.008741,0.022724&z=16'>39518 John Mosby Highway, Aldie, VA 20105</a>. Please click <a href='http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=39518+John+Mosby+Highway,+Aldie,+VA&sll=38.975075,-77.634737&sspn=0.008741,0.022724&ie=UTF8&hq=&hnear=39518+John+Mosby+Hwy,+Aldie,+Loudoun,+Virginia+20105&ll=38.975225,-77.636969&spn=0.008741,0.022724&z=16'>here</a> for custom directions.</DIV>
					<IMG src='images/directions.png'></IMG>
					
					<!-- IE7 doesn't want to let me scroll down to the bottom, so I'm adding some space here -->
					<BR />
					<BR />
					<BR />
					<BR />
	
				</DIV>
			</DIV>
		</DIV>
	<?php
				include($theme_base . '/footer.php');
		?>
	</BODY>
</HTML>