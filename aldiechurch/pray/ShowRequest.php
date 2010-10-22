<?php
	set_include_path('..');
	require_once('login_funcs.php');
	require('wp-blog-header.php');
	$theme_base = 'wp-content/themes/twentyten';
	include($theme_base . '/header.php');
	require_once('DbConnect.php');
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
		<BASE href='/aldiechurch/pray/'></BASE>
		<TITLE>Submit Prayer Request</TITLE>
		<STYLE type='text/css'>
			FIELDSET {
				margin-top: 10px;
			}
			
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
			
			LABEL#is_member {			
				display: block;
				float: none;
				width: 400px;
				border: 0;
			}
			
			.note {
				width: 490px;
			}
		</STYLE>
	</HEAD>
	<BODY>
		<DIV id='container'>
			<FORM id='submit_prayer' method='post'>
				<INPUT type='hidden' id='prayer_id'></INPUT>
				<FIELDSET>
					<LEGEND>Pray for</LEGEND>
					<p>
						<LABEL>Please Pray For</LABEL>
						<INPUT id='display' type='text' disabled='true'></INPUT>
					</p>
					<p>
						<LABEL>&nbsp;</LABEL>
						<LABEL id='is_member'>This person is a church member</LABEL>
					</p>
					<br />
					<p>
						<LABEL>Details:</LABEL>
						<INPUT id='pray_cat' type='text' disabled='true'>
						</INPUT>
					</p>
					<TEXTAREA id='details' rows='5' cols='60' disabled='true'></TEXTAREA>
				</FIELDSET>				
				<DIV id='buttons'>
					<INPUT type='button' id='edit' onClick='edit_prayer()' value='Edit'></INPUT>
					<INPUT type='button' id='email' onClick='send_email()' value='Send Encouraging Email'></INPUT>
				</DIV>
			</FORM>
		</DIV>
		
		<SCRIPT type='text/javascript' src='jquery-1.3.2.js'></SCRIPT>
		<SCRIPT type='text/javascript' src='json2.js'></SCRIPT>
		<SCRIPT type='text/javascript' src='utils.js'></SCRIPT>

		<SCRIPT type='text/javascript' >
			$(document).ready(function() {
				//load the requested prayer request (ShowRequest.php?show=id)
				var prayer_id = getURLParameter('show');
				if(prayer_id != "") {
					load_prayer( prayer_id );
				}
				
				//For Debugging Only
				$("#edit").show();
				
			});
			
			function load_prayer( prayer_id ) {
				var res = get_response_from("Pray.php", "ajax_get_prayer_details", new Array(prayer_id));
				var resdata = JSON.parse(res);
				var pdata = resdata.row;
				
				$("#prayer_id").val( prayer_id );
				$("#display").val(pdata.display);
				if(pdata.is_member=="1") {	
					$("#is_member").show();
				} else {
					$("#is_member").hide();
				}
				$("#pray_cat").val(pdata.description);
				$("#details").val(pdata.details);
				
				var loggedin_email = get_response_from("../login_funcs.php", "show_auth_info", new Array('email'));
				if(loggedin_email==pdata.email) {
					$("#edit").show();
					$("#email").hide();
				} else {
					$("#edit").hide();
					$("#email").show();
				}
			}
			
			function edit_prayer() {
				var prayer_id = $("#prayer_id").val();
				if(prayer_id == "") {
					alert('This is not a prayer you may edit.');
				} else {
					window.location.href='EditRequest.php?edit='+prayer_id;
				}
			}

		</SCRIPT>


        
	</BODY>
</HTML>