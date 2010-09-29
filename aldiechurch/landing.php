<!DOCTYPE html>
<HTML>

	<HEAD>
		<STYLE type='text/css'>
			#leftcol {
				position: absolute;
				float: left;
				top: 0px;
				left: 0px;
				height: 100%;
				width: 480px;
				
				background:#FFF3DB url("http://www1.blogblog.com/scribe/bg_paper_mid.jpg") ;
			}
			#rightcol {
				position: absolute;
				top: 0px;
				left: 501px;
				height: 100%;
				width: 229px;
				
			}
					
			
			#leftcol, #rightcol {
				padding: 5px;
			}
			
			#leftcol p, #rightcol p {
				padding-left: 15px;
			}

			#announcements p {
				padding-left: 0px;
			}
			
			DIV.title {
				font-size: 130%;
				font-style: bold;
				color: red;
				padding-top: 10px;
			}
			#announcements a {
				color: rgb(66, 120, 211);
			}
			#announcements p.ann_body {
				padding-bottom: 10px;
			}
			#announcements p.title {
				font-size: 120%;
			}
			#announcements p.title a{			
				color: black;
			}

		</STYLE>
	</HEAD>

	<?php
	require('wp-blog-header.php');
	?>
	
<!--	<base href="http://doverknoll.com/aldiechurch/" /> -->
	<?php
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	?>
	
	
	<DIV id='container'>
	
		<DIV id='content'>
			<DIV id='leftcol'>
			
				<DIV id='announcements'>
					<DIV class='title'>Announcements</DIV>
					<DIV class='sec_content'>
						<!-- Put in an announcements loop -->
						<?php 
							$lastposts = get_posts('category_name=ann');
							 foreach($lastposts as $post) :
								setup_postdata($post);
								?>
								<p class='title'>
									<a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>">
										<?= the_title(); ?>
									</a>
								</p>
								<p class='ann_body'>
									<a href="<?php the_permalink(); ?>" >
										<?= the_content() ?>
									</a>
								</p>
					<?php endforeach; ?>
	
					</DIV>
				</DIV>
				
			</DIV>
			
			<DIV id='rightcol'>
	
				<DIV id='reading'>
					<DIV class='title'>Readings for Sunday</DIV>
					<DIV class='sec_content'>
						<!-- Get the most recent Readings post, put text ref as the link title -->
						<p>TODO: Pull most recent readings post</p>
						
						<BR />
						<p><a href='http://textweek.com'>Resources for studying the text</a></p>
						<p><a href='prayer-list'>Prayer List</a></p>

					</DIV>
				</DIV>
					
				<DIV id='Worship Particpants'>
					<DIV class='title'>Serving This Week</DIV>
					<DIV class='sec_content'>
						<!-- Pull together the particpants for this week -->
						<p>LEM: TODO GetfromDb</p>
						<p>Lector: TODO GetfromDb</p>
						<p>Acolyte: TODO GetfromDb</p>
						<p>Preacher: TODO GetfromDb</p>
						<p>&nbsp;</p>
						<p>Coffee Hour: TODO GetfromDb</p>

					</DIV>
				</DIV>
				
				<DIV id='sermon'>
					<DIV class='title'>Good Reading
					</DIV>
					<DIV class='sec_content'>
						<?php
							// Pull most recent sermon post (link to it)
							$lastposts = get_posts('category_name=sermons&numberposts=1');
							 foreach($lastposts as $post) :
								setup_postdata($post);
								?>
							<p class='title'>
								Sermon: <a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>">
									<?= the_title(); ?>
								</a>
							</p>
									
							<?php endforeach; ?>

							<p>Newsletter: <a href='newsletters.php'>Redeeming Features</a></p>
					</DIV>
				</DIV>
	
	
				<DIV id='fellowship'>
					<DIV class='title'>
						<a href=''>Fellowship Links</a>
					</DIV>
					<DIV class='sec_content'>
						<!-- Pull the fellowship links here -->
					</DIV>
				</DIV>
	
				
			</DIV>
		</DIV>	
	</DIV>
	
	
	<?php
			include($theme_base . '/footer.php');
	?>
</HTML>