
	<STYLE type='text/css'>
			.disclaimer { font-size: 80% }
			
			DIV#directory_menu {
				position: fixed;
				background-color: white;
				left: 20px;
				top: 27px;
				width: 200px;
				border: 1px dotted black;
			}
			
			DIV#directory_menu p {
				text-align: center;
				margin: 10px;
			}
			
			DIV#pages {
				position: relative;
				width: 85%;
				border: 2px solid black;
				background-color: rgb(249,195,108);
				opacity: 100%;
				z-index: 2;
				margin-bottom: 5px;
				
				height: 2em;
			} 
			/* 				
				position: fixed; 				
				top: 10px;
				left: 20px;
			*/
			
			DIV#pages a {
				display: table-cell;
				padding-right: 5px;
				float: left;
			}
	</STYLE>

	<DIV id='directory_menu'>
		<p>Available Directory Sorts</p>
		<ul>
			<li>
				<a href='PhotoDirectory.php?show=a-c'>Photo Directory</a>
				<DIV id='pages'>
					<?php
						$a_pages = array('A-C', 'D-G', 'H-K', 'L-N', 'O-R', 'S-V', 'W-Z');
						echo "<a href='?show=a-z'>All</a>&nbsp;";
						foreach($a_pages as $pg) {
							echo "<a href='PhotoDirectory.php?show=$pg'>" . $pg . "</a>&nbsp;";
						}
					?>
				</DIV>
			</li>

			<?php  
				directory_menu(); 
			?>
		</ul>
	</DIV>
	
	<DIV class='disclaimer'>
		<p>(c)Copyright 2010.</p>
		<p>The above information is copyrighted by The Church Of Our Redeemer in order to prevent its use for any non-church related activities. This information is provided for the convenience of church members, and is intended for church-related business only.
		This information may not be forwarded in an email, printed, diseminated, collected, sold, used to solicit for business or otherwise transmitted without authorization from the Church of Our Redeemer.</p> 
	</DIV>
