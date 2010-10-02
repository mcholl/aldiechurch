	<?php
		require_once('Celebrant.php');
		
		$is_editable = isset($_GET['edit']);
		
		$default_id = null;
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
			<SPAN class='selector'>Service</SPAN>
			<SPAN class='selected_object'>
				<SELECT id='service_date' onClick='set_participants_by_date(this.value)' 
				onKeyUp='set_participants_by_date(this.value)' 
				onSelectionChange='set_participants_by_date(this.value)' >
					<?php  get_options_for_dateid(12, '' );	?>
				</SELECT>
			</SPAN>
		</DIV>
		
		<DIV id='body_area'>
				<?php get_editbody_editbyservice( ) ?>			
		</DIV>

	</DIV>
	
	<SCRIPT type='text/javascript' src='jquery-1.3.2.js'></SCRIPT>
	<SCRIPT type='text/javascript' src='json2.js'></SCRIPT>
	<SCRIPT type='text/javascript' src='utils.js'></SCRIPT>
	
	<SCRIPT type='text/javascript'>
		$(document).ready( function() {
			set_participants_by_date( 1 );
		});
		
		function set_participants_by_date( selected_date ) {			
			
			//Populate the empty form with data from the database
			var role_ids = new Array();
			var els = $("div.job_row").get();
			for(var el in els) {
				role_ids.push( $(els[el]).attr('role_id') );
			}
		
			var role_data = JSON.stringify(role_ids);
			var date_id = $("#service_date").val();
			var res= get_response_from('Celebrant.php', 'get_participants_by_date', new Array(date_id, role_data));
			//alert(res);
			var data = JSON.parse(res);
			
			for(var ro=0; ro < data.rows.length; ro++) {
				try {
					var rd = data.rows[ro];
					var sel = "div.job_row[role_id='" + rd.role_id +"'] div.answer ";
					$(sel+" SELECT").val( rd.p_id );
					$(sel+" LABEL").text( rd.name );
				} catch(exception) {
					Console.WriteLine(exception)
				}
			}
		}
		
	</SCRIPT>
	
