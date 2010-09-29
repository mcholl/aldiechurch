<?php
	$pages_base = '/aldiechurch';
	$posts_base = '/aldiechurch/archives/category';
?> 

<DIV id='menu' class='menu'>
	<ul id='nav'>
		<li >
			<a href='<?= $pages_base ?>/home.php'>Home</a>
			<ul>
				<li>
					<a href='<?= $pages_base ?>/welcome.php'>Welcome</a>
					<p>The Home Page for users who are not logged in</p>
				</li>
				<li class='login_req'>
					<a href='<?= $pages_base ?>/landing.php'>Home [todo]</a>
					<p>The Home Page for users who are logged in</p>
				</li>

				<li >
					<a href='<?= $pages_base ?>/login.php'>Login [TODO]</a>
					<p>In order to gain access to menu links that are greyed out, you must first log in. You may log in using your Facebook Id, a Google Mail id, Yahoo Id, or any other OpenId provider. Your information will be stored between sessions in a cookie, unless if you turn those off. There is no Aldie-Church-specific log-in option.</p>
				</li>
				<li >
					<a href='<?= $pages_base ?>/subscribe.php'>Subscribe</a>
					<p>Rather than visiting AldieChurch.com every time there is an update to the site's content, you can use RSS or receive updated content in your email inbox.</p>
				</li>
				<li >
					<a href='<?= $pages_base ?>/search.php'>Search this site</a>
					<p>Provides the option of searching the entire site for specified keywords. If you know what you want but not where it is, use this.</p>
				</li>
				<li >
					<a href='<?= $pages_base ?>/sitemap.php'>Site Map</a>
					<p>This page (where we show you all menu options along with helpful descriptions).
				</li>
				<li>&nbsp;</li>
				<li class='login_req' >
					<a href='<?= $pages_base ?>/help'>Admin Help [TODO]</a>
					<p>Helpful information for the poor admin who gets to keep everything up to date. Note, this is a WordPress site, so most of your documentation (if you need it) is really at WordPress.com.  This page is more for 'How do I make changes to aldiechurch.com</p>
				</li>
				<li class='login_req'>
					<a href='<?= $pages_base ?>/wp-admin/'>Site Admin</a>
					<p>The backend of this website, where we add content and stuff</p>
				</li>
			</ul>
		</li>
		<li><a href='<?= $posts_base ?>/sermons/'>Worship</a>
			<ul>
				<li >
					<a href='<?= $posts_base ?>/sermons/'>Sermons</a>
					<p>The text of each sermon preached at Church of Our Redeemer. If you are looking for a specific sermon, you may find it easier to use the search option to locate it.</p>
				</li>
				<li >
					<a href='<?= $posts_base ?>/service/'>Service [TODO]</a>
					<p>Copies of the bulletins, in which the order of service is laid out.</p>
				</li>
				<li >
					<a href='<?= $posts_base ?>/readings/'>Readings [TODO]</a>
					<p>The weekly lessons, as provided in the bulletin insert. Yes, this is the same as the lectionary, but here you get the exact translation referred to during the sermon.</p>
				</li>
				<li>&nbsp</li>
				<li class='login_req'>
					<a href='<?= $pages_base ?>/prayer-list'>Prayer List</a>
					<p>Part of worship is lifting up our friends before the Lord. Here we have the weekly prayer list, divided into five helpful subsections, so you can pray for some each day.</p>
				</li>
				<li >
					<a href='http://www.commonprayer.org/offices.cfm'>Daily Offices (1928)</a>
					<p>From the book of Common Prayer, a helpful daily devotional, with prayers and readings that will take you through all of scripture in three years. To be clear, we use the 1979 BCP in our services, but the 1928 BCP, in addition to being out of copyright, is pretty good too.</p>
				</li>
				<li>&nbsp</li>
				<li class='login_req'>
					<a>Worship Particpant Schedule</a>
					<p>A way to quickly find out who the LEM, lector, acolyte, and coffee hour host are for any given Sunday. (Oh yeah, and to find out when you're scheduled too!</p>
				</li>
			</ul>
		</li>
		<li ><a href='http://www.biblegateway.com/'>Study</a>
			<ul >
				<li >
					<a href='http://www.biblegateway.com/'>The Bible</a>
					<p>A link to BibleGateway.com, a helpful bible resource</p>
				</li>
				<li >
					<a href='http://net.bible.org/bible.php'>The NET Bible</a>
					<p>A solid, scholarly, free, open source bible that can be used without copyright restrictions. The NET Bible's philosophy is to expose the translation and source material in as many footnotes as possible.</p>
				</li>
				<li >
					<a href='http://textweek.com'>The Lectionary (RCL)</a>
					<p>A list of Bible readings used each Sunday. The RCL is a set of readings agreed to by several Christian denominations, with the idea that as much of the church as possible will be studying the same texts each week.</p>
				</li>
				<li>&nbsp;</li>
				<li >
					<a href='<?= $posts_base ?>/mensbiblestudy'>Men's Bible Study [TODO]</a>
					<p>Every month, the men use this study to guide their discussions after church.</p>
				</li>
				<li >
					<a href='<?= $posts_base ?>/links'>Other Links we like</a>
					<p>The Internet is full of good information to help you study scripture. Occasionally, we'll think to post links to some good ones.</p>
				</li>
			</ul>
		</li>
		<li ><a href='<?= $posts_base ?>/church/ann/' >Fellowship</a>
			<ul >
				<li >
					<a href='<?= $posts_base ?>/church/ann/' >Announcements</a>
					<p>A summary of what's coming up in the church's life.</p>
				</li>
				<li >
					<a href='<?= $pages_base ?>/calendar.php'>Calendar</a>
					<p>A summary of what's going on in the church this week. Note, you can also subscribe to this content on your mobile device to stay in touch!</p>				
				</li>
				<li >
					<a href='<?= $pages_base ?>/newsletters.php'>Newsletter</a>
					<p>Our (bi-?)monthly newsletter.</p>
				</li>
				<li>&nbsp</li>
				<li class='login_req'>
					<a>Pictures [TODO]</a>
					<p>A link to the photostream to which all church members can post photos of church activities.</p>
				</li>
				<li class='login_req'>
					<a>Member Directory</a>
					<p>A link to the pictures and contact information f our members. We restrict access to this information to existing members only, in order to protect their privacy.</p>
				</li>
				<li>
					<a href='http://www.facebook.com/home.php#!/group.php?gid=34501739968&ref=ts'>Facebook Group</a>
					<p>A link to the facebook group where some members have been known to hang out. All content is public, but to post, you must join the group. Further instructions available at facebook.</p>
				</li>
				<li class='login_req'>
					<a>Worship Participant Schedule</a>
					<p>See above</p>					
				</li>
				<li class='login_req'>
					<a href='<?= $pages_base ?>/prayer-list'>Prayer List</a>
					<p>See above</p>					
				</li>
				<li class='login_req'>
					<a href='<?= $pages_base ?>/talentsurvey'>Talent Survey [TODO]</a>
					<p>Help us help you participate in the work of the church by filling out this survey of how you'd like to particpate.</p>
				</li>
				<li>&nbsp</li>
				<li >
					<a href='http://thediocese.net'>The Diocese</a>
					<p>As an Episcopal church, Church of Our Redeemer is tied to a larger, statewide entity, the Diocese of Virginia. Learn more here.</p> 
				</li>
			</ul>
		</li>
		<li ><a href='<?=$pages_base?>/schedule-of-services'>About Us</a>
			<ul >
				<li >
					<a href='<?=$pages_base?>/schedule-of-services'>Services</a>
					<p>Information about Service times (& our snow policy!)</p>
				</li>
				<li >
					<a href='<?=$pages_base?>/directions'>Directions</a>
					<p>How to find us in real life.</p>
				</li>
				<li >
					<a href='http://villageofaldie.com'>Our Village</a>
					<p>Some information about Aldie, VA.</p>
				</li>
				<li >
					<a href='<?=$posts_base?>/about_us/faq/'>Frequently Asked Questions</a>
					<p>Miscellaneous questions about how we "do church" at Church of Our Redeemer</p>
				</li>
				<li>&nbsp</li>
				<li >
					<a href='<?=$pages_base?>/staff'>Clergy & Lay Volunteers</a>
					<p>Some information about the people who help run the church.</p>
				</li>
				<li >
					<a href='<?=$pages_base?>/ministries'>Ministries [TODO]</a>
					<p>Information about what we do at Church of Our Redeemer</p>
				</li>
				<li >
					<a href='<?=$pages_base?>/history'>Our History [TODO]</a>
					<p>A little bit about where we (this congregation) came from. If you want to know more about where the wider church came from, take a Church History class!</p>
				</li>
				<li >
					<a href='<?=$pages_base?>/building'>Our Facility</a>
					<p>A little bit about our building</p>
				</li>
				<li>&nbsp</li>
				<li >
					<a href='<?=$pages_base?>/contact-us'>Contact Us</a>
					<p>How to reach a human being.</p>
				</li>
			</ul>
		</li>
	</ul>
</DIV>

