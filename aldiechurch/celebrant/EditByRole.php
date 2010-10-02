	<?php
		require_once('Celebrant.php');

		$is_editable = isset($_GET['edit']);
			
		$default_id = 1;
		if(isset($_GET['edit'])) {
			$default_id =  $_GET['edit'];
		} else if ( isset($_GET['show']) ) {
			$default_id = $_GET['show'];
		}
	

	?>

	<STYLE type='text/css'>
			<?php
				echo "DIV#body_area DIV.answer " . ( ($is_editable) ? " LABEL " :  " SELECT ") . "{ \n"
			?>
				display: none;
			}
	</STYLE>
	
	<LINK rel='stylesheet' href='celebrant.css'></LINK>
	
	<DIV id='content'>
	
		<DIV id='select_object'>
			<SPAN class='selector'>Role</SPAN>
			<SPAN class='selected_object'>
				<SELECT id='role_id' onClick='set_participants_by_role(this.value)' onKeyUp='set_participants_by_role(this.value)' onSelectionChange='set_participants_by_role(this.value)' >
					<?php  get_roles_as_options();	?>
				</SELECT>
			</SPAN>
		</DIV>
		
		<DIV id='body_area'>
			<!-- Actually this gets set dynamically as you choose which role to edit -->
			<?php  get_editbody_editbyrole( $default_id ) ?>
		</DIV>
		
		
	</DIV>
	
	<DIV id='buttons'>
		<!-- Save Button -->
		<!-- |<-- prev Service  & -next Service  links ->|
		
		<!-- |<-- prev Quarter  & -next Quarter  links ->|

	</DIV>
	
	<SCRIPT type='text/javascript' src='jquery-1.3.2.js'></SCRIPT>
	<SCRIPT type='text/javascript' src='json2.js'></SCRIPT>
	<SCRIPT type='text/javascript' src='utils.js'></SCRIPT> 
	
	<SCRIPT type='text/javascript'>
		$(document).ready( function() {
			$("#role_id").val(<?=$default_id?>);
			var role_id = $("#role_id").val();
			set_participants_by_role( role_id );
		});
		
		function set_participants_by_role( selected_role ) {
			return;
		
			//Get a new body (in order to get the selction choices to be valid
			alert('about to set ');
			var new_body = get_response_from('Celebrant.php', 
			'get_editbody_editbyrole', new Array( selected_role));
			$("DIV#body_area").html(new_body);
			
			
			//Populate the empty form with data from the database
			var date_ids = new Array();
			var els = $("div.date_row").get();
			for(var el in els) {
				date_ids.push( $(els[el]).attr('date_id') );
			}
		
			var date_data = JSON.stringify(date_ids);
			var role_id = $("#role_id").val();
			var res= get_response_from('Celebrant.php', 'get_participants_by_role', new Array(role_id, date_data));
			//alert(res);
			var data = JSON.parse(res);
			
			for(var ro=0; ro < data.rows.length; ro++) {
				try {
					var rd = data.rows[ro];
					var sel = "div.date_row[date_id='" + rd.date_id +"'] div.answer ";
					$(sel+" SELECT").val( rd.p_id );
					$(sel+" LABEL").text( rd.name );
				} catch(exception) {
					Console.WriteLine(exception)
				}
			}
		}
		
	</SCRIPT>
	
	