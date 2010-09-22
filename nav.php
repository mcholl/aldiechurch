<STYLE type='text/css'>
	/* Menu Look & Feel */
	DIV#menu ul#nav {
		background-color: rgb(240, 240, 240);
		color: rgb(66, 120, 211); 
		font-family: "Callibri", sans-serif;
		font-style: normal;
		font-size: 10pt;
		width: 640px;
		padding: 5px 5px 0px 5px;
		border: 1px solid black;

		z-index: 10;

	}
	
	li>ul {
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
		
	ul#nav li, ul#nav li ul li{
		float: left;
		position: relative;
	}
	ul#nav li {	width: 110px; }
	ul#nav li ul li { width: 200px }
		
	ul#nav li a {
		text-decoration: none;
		display: block;
		color: inherit;
	}
		
	li ul {
		display: none;
		position: absolute;
		width:196px;
		top: 18px;
		left: 0;
		margin-left:-1px;		
	}
		
	li>ul {
		top: auto;
		left: auto;
		padding: 4px;
	}
		
	li:hover {
		color: rgb(214, 0, 29);
	}
	li:hover ul, li.over ul {
		display: block;
		color: rgb(66, 120, 211);
	}	
	
	/* IE 6 Support */
	body {   behavior:url(csshover.htc); }
	
</STYLE>

<DIV id='menu' class='menu'>
	<ul id='nav'>
		<li >Home</li>
		<li >Sermons</li>
		<li >About Us
			<ul >
				<li >Services</li>
				<li >Directions</li>
				<li >What we believe</li>
				<li >Mission Statement</li>
				<li >Staff</li>
				<li >Frequently Asked Questions</li>
			</ul>
		</li>
		<li >Community
			<ul >
				<li >Announcements</li>
				<li >Calendar</li>
				<li >Newsletter</li>
				<li class='login_req'>Prayer List</li>
				<li class='login_req'>Pictures</li>
				<li class='login_req'>Facebook Group</li>
				<li class='login_req'>Member Directory</li>
			</ul>
		</li>
		<li >Wider Church
			<ul >
				<li ><a href='http://thediocese.net'>The Diocese</a></li>
				<li ><a href='http://textweek.com'>The Lectionary</a></li>
				<li ><a href='http://dailyoffice.org'>Daily Offices</a></li>
				<li >Links we like</li>
			</ul>
		</li>
	</ul>
</DIV>