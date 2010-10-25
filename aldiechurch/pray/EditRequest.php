<?php
	set_include_path('..');
	require_once('login_funcs.php');
	require('wp-blog-header.php');
	$theme_base = 'wp-content/themes/twentyten';
	include($theme_base . '/header.php');
	require_once('DbConnect.php');
	require_once('Pray.php');
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
<!--		<BASE href='/aldiechurch/pray/'></BASE> -->
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
			
			FIELDSET#vis, FIELDSET#ismem {			
				display: table-row;
				width: 400px;
				border: 0;
			}
			
			.note {
				width: 490px;
			}
			
			.error {
				color: red;
				font-style: italic;
			}
		</STYLE>
	</HEAD>
	<BODY>
		<DIV id='container'>
			<FORM id='pr_form' action='' method='get' class="cmxform" >
				<INPUT type='hidden' id='prayer_id'></INPUT>
				<FIELDSET>
					<LEGEND>Pray for</LEGEND>
					<p>
						<LABEL>Please Pray For</LABEL>
						<INPUT id='display' name='display' type='text' class='required'></INPUT>
					</p>
					<FIELDSET id='ismem'>
						<LABEL>
							<INPUT id='is_member' name='is_member' type='checkbox' value='y'></INPUT>
						This person is a church member
						</LABEL>
					</FIELDSET>
					<FIELDSET id='vis'>
						<INPUT id='visibility' name='visibility' type='radio' value='public'><LABEL>Public (Printed in the bulletin)</LABEL></INPUT>
						<INPUT id='visibility' name='visibility' type='radio' value='private'><LABEL>Private (Prayer Team Only)</LABEL></INPUT>
					</FIELDSET>
					<p>
						<LABEL>Details:</LABEL>
						<SELECT id='pray_cat' name='pray_cat' class='required' class='required'>
							<?php get_pray_cats_as_options(); ?>
						</SELECT>
					</p>
					<TEXTAREA id='details' name='details' rows='5' cols='60' class='required'></TEXTAREA>
				</FIELDSET>
				
				<FIELDSET>
					<LEGEND>About you</LEGEND>
					
					<p>
						<LABEL>Prayers Requested By</LABEL>
						<INPUT id='requested_by' name='requested_by' type='text' disabled='true' value='<?= get_auth_info('displayName'); ?>'></INPUT>
					</p>
					<p>
						<LABEL>Your Email Address</LABEL>
						<INPUT id='email' name='email' type='text' disabled='true' value='<?= get_auth_info('email'); ?>' class='required'></INPUT>
					</p>
					
					<p>
						<LABEL> Your Relationship to the one being prayed for</LABEL>
						<INPUT id='relationship' name='relationship' type='text' class='required'></INPUT>
					</p>
					
					<p>
						<LABEL>Ask me about this again on</LABEL>
						<INPUT id='end_date' name='end_date' type='text' class='required'></INPUT>
					</p>
					
					<p class='note'>We want to keep our prayer list as up to date as possible. We will contact you around the 'end date' you specify, and will be happy to either keep this person on our list or not, as you desire. We simply desire to keep current on what God is doing in this person's life. Thank you for your assistance in this matter.</p>
				</FIELDSET>
				
				<DIV id='buttons'>
					<INPUT type='button' id='edit' onClick='edit_prayer()' value='Edit'></INPUT>
					<INPUT type='submit' id='save' onClick='save_prayer()' value='Save' class='submit'></INPUT>
					<INPUT type='button' id='remove' onClick='remove_prayer()' value='Remove'></INPUT>
					<INPUT type='button' id='save' onClick='new_prayer()' value='New'></INPUT>
				</DIV>
				
				<DIV class='error'>
					<SPAN></SPAN>
				</DIV>
			</FORM>
		</DIV>
		
		<SCRIPT type='text/javascript' src='jquery-1.3.2.js'></SCRIPT>
		<SCRIPT type='text/javascript' src='json2.js'></SCRIPT>
		<SCRIPT type='text/javascript' src='utils.js'></SCRIPT>

	       <!-- required plugins for date picker-->		
		<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.5.custom.css" rel="Stylesheet" />	
		<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.5.custom.min.js"></script> 

		<!-- required plug-ins for validation -->		
		<script type="text/javascript" src="http://dev.jquery.com/view/trunk/plugins/validate/jquery.validate.js"></script>


		<SCRIPT type='text/javascript' >
			$(document).ready(function() {
				if( getURLParameter("remove") != "") {
					var res = get_response_from("Pray.php", "deactivate_prayer", new Array( getURLParameter("remove") ) );
					$("DIV#container").html( res ); 
					//"This prayer request has been deactivated.  Thank you.  Please click <a href='prayer-list.php'>here</a> to return to the prayer list.");
				}
			
				/* Init Date Picker */
				var opts = { 
					defaultDate: '+1m',
					maxDate: '+4m',
					minDate: '-1w',
					gotoCurrent: true
				};
				$('#end_date').datepicker(opts);
				var def_date = $("#end_date").datepicker('getDate');
				$("#end_date").datepicker('setDate', def_date);
				
				/*Load data if appropriate */
				if( getURLParameter("edit") != "") {
					load_prayer( getURLParameter("edit") );
				} else {
					$("#prayer_id").val("NEW");
					//Figure out the current submitter id of the logged in person
					$("#requested_by").attr('submitter_id', logged_in_user_submitter_id());
					$("INPUT#visibility").filter( "[value='public']" ).attr('checked', true );
				}

				/* Init Validation */
				var optsValidate = 	{
						invalidHandler: function(form, validator) {
							 var errors = validator.numberOfInvalids();
							 if (errors) {
							   var message = errors == 1
								? 'You missed 1 field. It has been highlighted'
								: 'You missed ' + errors + ' fields. They have been highlighted';
							   $("div.error span").html(message);
							   $("div.error").show();
							 } else {
							   $("div.error").hide();
							}
						}
					}
				$("#pr_form").validate(optsValidate); //
				$("#pr_form").validate().form();
			});
			
			function logged_in_user_submitter_id() {
				/* Returns the submitter_id of the currently logged in user
					or NEW if the currently logged in user has never submitted a 
					prayer request before */
					
					//var email = get_response_from("login_funcs.php", "get_auth_info", new Array('email'));
					var email = $("#email").val();
					var submitter_id = get_response_from("Pray.php", "ajax_get_submitter_id", new Array(email));
					return submitter_id;
			}
			
			function load_prayer(prayer_id) {
				var res = get_response_from("Pray.php", "ajax_get_prayer_details", new Array(prayer_id));
				var resdata = JSON.parse(res);
				var pdata = resdata.row;

				$("#prayer_id").val( prayer_id );				
				$("#display").val(pdata.display);
				$("#is_member").attr('checked', (pdata.is_member=="1"));
				
				$("INPUT#visibility").filter("[value='"+((pdata.is_public=="1")?"public":"private")+"']").attr( 'checked',  true );
				$("#details").val( pdata.details );
				$("#pray_cat").val( pdata.pray_cat );
				
				$("#requested_by")
					.val( pdata.name )
					.attr('submitter_id', pdata.submitter_id);
				$("#email").val( pdata.email );
				$("#relationship").val( pdata.relationship );
				$("#end_date").val( get_myd_date(pdata.valid_until) );
			};
			
			function get_myd_date( the_date ) {
				/* Given a date in the format YYYY-MM-DD
					returns it in the form mm/dd/yyyy */
					//([0-9]{4})\-([0-9]{2})\-([0-9]{2}) = yyyy-mm-dd
					var re = new RegExp( "([0-9]{4})\-([0-9]{2})\-([0-9]{2})" );
					if(the_date.match(the_date) ) {
						//alert('Start parsing');
						var d = re.exec(the_date);
						if(!d) return "";
						var ret = d[2]+"/"+d[3]+"/"+d[1];
						//alert(ret);
						return ret;
						
					} else {
						//alert('Not a date');
						return the_date;
					}
			}
			function get_sql_date( the_date ) {
				/* Given a date in the format mm/dd/yyyy
					returns it in the form YYYY-MM-DD */
					//([0-9]{4})\-([0-9]{2})\-([0-9]{2}) = yyyy-mm-dd
					var re = new RegExp( "([0-9]{2})\/([0-9]{2})\/([0-9]{4})" );
					if(the_date.match(the_date) ) {
						//alert('Start parsing');
						var d = re.exec(the_date);
						if(!d) return "";
						var ret = d[3]+"-"+d[1]+"-"+d[2];
						//alert(ret);
						return ret;
						
					} else {
						//alert('Not a date');
						return the_date;
					}
			}
			
			function save_prayer() {
				if($("#pr_form").validate().form() == true) {
					var data = JSON.stringify( read_data() );
//					alert(data);
					var res = get_response_from("Pray.php", "save_prayer", new Array(data) );
//					alert(res);
				}
			}
			
			function read_data() {
				var pdata = new Object();
				pdata.prayer_id = $("#prayer_id").val( );				
				pdata.display = $("#display").val();
				pdata.is_member = $("#is_member").attr('checked');
				pdata.is_public = $("INPUT#visibility").filter( "[value='public']" ).attr('checked' );
				pdata.pray_cat = $("#pray_cat").val();
				pdata.details = $("#details").val( );
				
				pdata.name = $("#requested_by").val();
				pdata.submitter_id = $("#requested_by").attr('submitter_id');
				pdata.email  = $("#email").val( );
				pdata.relationship = $("#relationship").val(  );
				pdata.valid_until = get_sql_date( $("#end_date").val() );
				
				return pdata;
		}

		</SCRIPT>


        
	</BODY>
</HTML>