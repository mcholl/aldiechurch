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
			<SPAN class='selector'>Participant</SPAN>
			<SPAN class='selected_object'>
				<SELECT id='p_id' onClick='set_participant(this.value)' onKeyUp='set_participant(this.value)' onSelectionChange='set_participant(this.value)' >
					<?php  get_all_pids_as_options();	?>
				</SELECT>
			</SPAN>
		</DIV>
		
		<DIV id='body_area'>
			<!-- Actually this gets set dynamically as you choose which role to edit -->
			<?php  get_editbody_editbypid( $default_id, '', '' ) ?>
		</DIV>
		
		
	</DIV>
	
	<DIV id='buttons'>
		<INPUT type='button' value='<- Prev' onClick='date_buttons_pid("prevq")' class='editMode'></INPUT>
		<!-- Save Button -->
		<INPUT type='button' value='Save' onClick='save_by_pid()' class='editMode'></INPUT>
		<INPUT type='button' value='New' onClick='new_person()' class='editMode'></INPUT>
		<INPUT type='button' value='Next ->' onClick='date_buttons_pid("nextq")' class='editMode'></INPUT>
		
	</DIV>
	
	<SCRIPT type='text/javascript' src='jquery-1.3.2.js'></SCRIPT>
	<SCRIPT type='text/javascript' src='json2.js'></SCRIPT>
	<SCRIPT type='text/javascript' src='utils.js'></SCRIPT> 
	<SCRIPT type='text/javascript' src='celebrant.js'></SCRIPT>	
