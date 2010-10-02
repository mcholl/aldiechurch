<?php
	require('wp-blog-header.php');
	require_once('celebrant/Celebrant.php');
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
	<?php
	
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	
	?>
	</HEAD>
	<BODY>
		<DIV id='container'>
			<?php
				/* This page loads each of the functional pages of the system, all located in the /celebrants subsirectory. (By keeping this page here, however, I don't have to worry about locating header, menu, etc...
				
				worship_schedule.php?edit=service&id=56, for example, will call /celebrant/EditByService.php, defaulting to service with the date_id of 56.
				
				First arg will either by edit or show  (the action)
				with a value of service, role, or participant
				
				Second arg is id (and is optional). It represents the date_id, role_id, or participant_id to which the page will first default
				*/
				
				$is_authorized_to_edit = 'false'; //TODO: check current login against those who are authorized to make changes to the schedule.
				
			?>
			<DIV id='action'>
				<FORM>
					<p>Edit By
					<INPUT type='radio' name='ac' value='r' onClick="refresh_page('r')" checked >Role</INPUT>
					<INPUT type='radio' name='ac' value='s' onClick="refresh_page('s')" >Service</INPUT>
				</FORM>
			</DIV>
			
			<DIV id='content'>
			</DIV>
			
			<SCRIPT type='text/javascript' src='celebrant/jquery-1.3.2.js'></SCRIPT>
			<SCRIPT type='text/javascript' src='celebrant/json2.js'></SCRIPT>
			<SCRIPT type='text/javascript' src='celebrant/utils.js'></SCRIPT>	

			<SCRIPT type='text/javascript'>
			
				$(document).ready(function() {
					refresh_page('r');
				});
			
				function refresh_page(edit_by) {
					var url = get_response_from(
						'celebrant/Celebrant.php', 
						'get_action_url', 
						new Array('<?=$is_authorized_to_edit ?>', edit_by)
					);
					url = "celebrant/"+url;
					
					$("#content").html("<h1>"+url+"</h1>");
					$("#content").load(url );
				}
				
			</SCRIPT>			
		</DIV>
		<?php
			include($theme_base . '/footer.php');
	?>
</HTML>


	