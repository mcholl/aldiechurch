<?php
	$pages_base = '/aldiechurch';
	$posts_base = '/aldiechurch/archives/category';
?> 

<STYLE type='text/css'>
	/* Menu Look & Feel */
	#menu { 
		display: block; 
	}	

	DIV#menu ul#nav {
		background-color: rgb(240, 240, 240);
		color: rgb(66, 120, 211); 
		font-family: "Callibri", sans-serif;
		font-style: normal;
		font-size: 10pt;
		width: 740px;
		padding: 5px 5px 0px 5px;
	
		border: 1px solid black;

		z-index: 10;
		height: 15px;
	}
	
	DIV#menu li>ul {
		list-style: none;
		background-color: rgb(240, 240, 240);
		border: 1px solid black;
	}

	/* Basic Menu Navigation */
	ul#nav {
		list-style: none;
		padding: 0;
		margin: 0;
	}
		
	DIV#menu ul#nav li, ul#nav li ul li{
		float: left;
		position: relative;
	}
	DIV#menu ul#nav li {	width: 110px; }
	DIV#menu ul#nav li ul li { width: 200px }
		
	DIV#menu ul#nav li a {
		text-decoration: none;
		display: block;
		color: inherit;
	}
		
	DIV#menu li ul {
		display: none;
		position: absolute;
		width:196px;
		top: 18px;
		left: 0;
		margin-left:-1px;		
	}
		
	DIV#menu li>ul {
		top: auto;
		left: auto;
		padding: 4px;
	}
		
	DIV#menu li:hover {
		color: rgb(214, 0, 29);
	}
	DIV#menu li:hover ul, li.over ul {
		display: block;
		color: rgb(66, 120, 211);
	}	
	
	/* IE 6 Support */
	body {   behavior:url(csshover.htc); }
	
</STYLE>

<DIV id='menu' class='menu'>
	<ul id='nav'>
		<li ><a href='<?= $pages_base ?>/home.php'>Home</a>
			<ul>
				<li ><a href='<?= $pages_base ?>/search.php'>Search this site</a></li>
				<li ><a href='<?= $pages_base ?>/wp-admin/'>Site Admin</a></li>
			</ul>
		</li>
		<li ><a href='<?= $posts_base ?>/sermons/'>Sermons</a></li>
		<li ><a>About Us</a>
			<ul >
				<li ><a href='<?=$pages_base?>/schedule-of-services'>Services</a></li>
				<li ><a href='<?=$pages_base?>/directions'>Directions</a></li>
				<li ><a href='<?=$posts_base?>/about_us/faq/'>Frequently Asked Questions</a></li>
				<li>&nbsp</li>
				<li ><a href='<?=$pages_base?>/staff'>Clergy & Lay Volunteers</a></li>
				<li ><a href='<?=$pages_base?>/ministries'>Ministries [TODO]</a></li>
				<li ><a href='<?=$pages_base?>/history'>Our History [TODO]</a></li>
				<li ><a href='<?=$pages_base?>/building'>Our Facility [TODO]</a></li>
				<li>&nbsp</li>
				<li ><a href='<?=$pages_base?>/contact-us'>Contact Us</a></li>
			</ul>
		</li>
		<li ><a>Community</a>
			<ul >
				<li ><a href='<?= $posts_base ?>/church/ann/' >Announcements</a></li>
				<li ><a href='<?= $pages_base ?>/calendar.php'>Calendar</a></li>
				<li ><a href='<?= $posts_base ?>/church/newsletter'>Newsletter</a></li>
				<li class='login_req'><a href='<?= $pages_base ?>/prayer-list'>Prayer List</a></li>
				<li class='login_req'>Pictures [TODO]</li>
				<li class='login_req'>Facebook Group [TODO]</li>
				<li class='login_req'>Member Directory</li>
				<li ><a href='http://thediocese.net'>The Diocese</a></li>
			</ul>
		</li>
		<li ><a>Study</a>
			<ul >
				<li ><a href='http://www.biblegateway.com/'>The Bible</a></li>
				<li ><a href='http://net.bible.org/bible.php'>The NET Bible</a></li>
				<li ><a href='http://textweek.com'>The Lectionary</a></li>
				<li ><a href='http://www.commonprayer.org/offices.cfm'>Daily Offices (1928)</a></li>
				<li ><a href='<?= $posts_base ?>/links'>Other Links we like</a></li>
			</ul>
		</li>
	</ul>
</DIV>