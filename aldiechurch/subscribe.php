<!DOCTYPE html>
<HTML>
	<?php
	require('wp-blog-header.php');
	
	function sub_buttons_row($title, $feed) {
	
				echo '<TR>';
				echo '<TD>' . $title .'</TD>';
				echo '<TD><a href=' . $feed . ' title="Subscribe to my feed"><img src="images/feed-icon.gif" style="border:0"/></a></TD>';
				echo '<TD><a href=\'http://fusion.google.com/add?feedurl=' . $feed . '\'><img src="http://gmodules.com/ig/images/plus_google.gif" alt="Google Reader or Homepage" border="0"></a></TD>';
				echo '<TD><a href="http://add.my.yahoo.com/rss?url='.$feed.'"><img src="http://us.i1.yimg.com/us.yimg.com/i/us/my/addtomyyahoo4.gif" border="0" alt="Add to My Yahoo!"></a></TD>';
				//echo '<TD><a href="http://feeds.my.aol.com/add.jsp?'. $feed .'"><img src="http://myfeeds.aolcdn.com/vis/myaol_cta1.gif" alt="Add to My AOL" border="0"/></a></TD>';
				echo '<TD><input type="hidden" value="'.$feed.'" name="feed"/><input type="submit" value="Subscribe via Email"/></TD>';
				echo "</TR>";
	}
	?>
	
<!--	<base href="http://doverknoll.com/aldiechurch/" /> -->
	<?php
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	?>
	
	<STYLE type='text/css'>
		.title {
			font-size: 14px;
		}
		
		.sec_content { font-size: 12px; }
		
		TABLE {
			width: 70%;
			border: 1px solid black;
			margin-left: 50px;
		}
		
		TABLE TD:first-child { width: 100px; }
		TABLE TD { width: 5%; }
	</STYLE>
	
	<DIV id='container'>
	
		<DIV class='title'>Stay in Touch</DIV>
		<br />
		
		<DIV id='sec_content'><p>Life is busy. There's no need to use your time coming back to this site every time something changes, when the Internet can do that for you!  Just subscribe to the content you're interested in following by using the links below.</p>
		
		<p>You have the option of subscribing via <a href='http://rss.softwaregarden.com/aboutrss.html'>RSS feeds</a> and reading them using a news aggregator like <a href='http://reader.google.com'>Google Reader</a> or having content delivered straight to your email inbox. There is no charge for either service, but neither service is perated by Church of Our Redeemer.</p>
		</DIV>
		
		<br />
		
	<form action="http://www.feedmyinbox.com/feeds/verify/" method="post" id="fmi" target="fmi" onsubmit="window.open('http://www.feedmyinbox.com/', 'fmi', 'scrollbars=yes,width=520,height=490');return true;">
	
		<BR />

		<TABLE >
				<?php 
					sub_buttons_row( "Sermons (text)",  "http://doverknoll.com/aldiechurch/archives/category/sermons/feed");

					sub_buttons_row( "Sermons (audio)",  "http://doverknoll.com/aldiechurch/archives/category/sermons/feed");

					sub_buttons_row( "Annoucements",  "http://doverknoll.com/aldiechurch/archives/category/church/ann/feed");
					
					sub_buttons_row( "Newsletter",  "http://doverknoll.com/aldiechurch/archives/category/church/ann/newsletter");
				
				?>
				
				<TR>
					<TD>Church Calendar</TD>
					<TD><a href='http://www.google.com/calendar/ical/aldiechurch.com_652e7cd34hnti0h9u0p2omt5ro%40group.calendar.google.com/public/basic.ics'><IMG src='images/ical.gif'></IMG></a></TD>
					<TD colspan='3'><a href="http://www.google.com/calendar/render?cid=http%3A%2F%2Fwww.google.com%2Fcalendar%2Ffeeds%2Faldiechurch.com_652e7cd34hnti0h9u0p2omt5ro%2540group.calendar.google.com%2Fpublic%2Fbasic" target="_blank"><img src="http://www.google.com/calendar/images/ext/gc_button6.gif" border=0></a></TD>
				</TR>
		</TABLE>
		
		<BR />
		Email Address: (if subscribing via email) <input type="text" value="" name="email" />

	</form>
	
	</DIV>
	
	
	
	<?php
			include($theme_base . '/footer.php');
	?>
</HTML>