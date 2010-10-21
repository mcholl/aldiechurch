<?php
	set_include_path('..');
	require_once('login_funcs.php');
	require('wp-blog-header.php');
	$theme_base = 'wp-content/themes/twentyten';
	include($theme_base . '/header.php');
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
		<TITLE>Submit Prayer Request</TITLE>
		<STYLE type='text/css'>
			FIELDSET p {
				display: block;
				width: 400px;
			}
		
			FIELDSET p LABEL {
				width: 150px;
				display: block;
				float: left;
			}
			FIELDSET p INPUT[type='text'], SELECT {
				width: 330px;
				display: block;
			}			
			
			FIELDSET#vis, FIELDSET#ismem {			
				display: table-row;
				width: 400px;
				border: 0;
			}
			
			.note {
				width: 490px;
			}
		</STYLE>
		<SCRIPT type='text/javascript' src='jquery-1.3.2.js'></SCRIPT>
		<SCRIPT type='text/javascript' src='json2.js'></SCRIPT>

	</HEAD>
	<BODY>
		<DIV id='container'>
			<FORM id='submit_prayer' method='post'>
				<FIELDSET>
					<LEGEND>Pray for</LEGEND>
					<p>
						<LABEL>Please Pray For</LABEL>
						<INPUT id='display' type='text'></INPUT>
					</p>
					<FIELDSET id='ismem'>
						<LABEL>
							<INPUT id='is_member' type='checkbox' value='y'></INPUT>
						This person is a church member
						</LABEL>
					</FIELDSET>
					<FIELDSET id='vis'>
						<INPUT id='visibility' name='visibility' type='radio' value='public'><LABEL>Public (Printed in the bulletin)</LABEL></INPUT>
						<INPUT id='visibility' name='visibility' type='radio' value='private'><LABEL>Private (Prayer Team Only)</LABEL></INPUT>
					</FIELDSET>
					<p>
						<LABEL>Details:</LABEL>
						<SELECT id='pray_cat'>
							<?php /*
								$qry = "SELECT pray_cat, description FROM pray_cats";
								$res = mysql_query($qry) or die($qry . ': ' . mysql_error());
								while($row = mysql_fetch_array($res)) {
									echo "<OPTION id='" . $row[0] . "'>" . $row[1] . "</OPTION>";
								} */
							?>
						</SELECT>
					</p>
					<TEXTAREA id='details' rows='5' cols='60'></TEXTAREA>
				</FIELDSET>
				
				<FIELDSET>
					<LEGEND>About you</LEGEND>
					
					<p>
						<LABEL>Requested By</LABEL>
						<INPUT id='requested_by' type='text' disabled='true' value='<?= get_auth_info('displayName'); ?>'></INPUT>
					</p>
					<p>
						<LABEL>Your Email Address</LABEL>
						<INPUT id='requested_by' type='text' disabled='true' value='<?= get_auth_info('email'); ?>'></INPUT>
					</p>
					
					<p>
						<LABEL> Your Relationship to the one being prayed for</LABEL>
						<INPUT id='relationship' type='text'></INPUT>
					</p>
					
					<p>
						<LABEL>Ask me about this again on</LABEL>
						<INPUT id='end_date' type='text'></INPUT>
					</p>
					
					<p class='note'>We want to keep our prayer list as up to date as possible. We will contact you around the 'end date' you specify, and will be happy to either keep this person on our list or not, as you desire. We simply desire to keep current on what God is doing in this person's life. Thank you for your assistance in this matter.</p>
				</FIELDSET>
			</FORM>
		</DIV>
		
		<SCRIPT type='text/javascript' >
			$(document).ready(function() {
				alert('Hello');
				$('#end_date').datePicker().val(new Date().asString()).trigger('change');
			});

		</SCRIPT>


	       <!-- required plugins for date picker-->
		<script type="text/javascript" src="date.js"></script>
		<!--[if IE]><script type="text/javascript" src="jquery.bgiframe.min.js"></script><![endif]-->
<!--        <script type="text/javascript" src="jquery.datePicker.js"></script> -->
        <link rel="stylesheet" type="text/css" media="screen" href="datePicker.css">
        
	</BODY>
</HTML>