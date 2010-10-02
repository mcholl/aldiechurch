<?php
	require('../wp-blog-header.php');
	require_once('../login_funcs.php');
?>
<!DOCTYPE html>
<HTML>
	<HEAD>
	<?php
	
		$theme_base = '../wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	
	?>
	</HEAD>
	<BODY>
		<DIV id='container'>
			<DIV id='content'>
			
				<DIV id='main_select'>
					<p>Service</p>
					<SELECT id='Service'>
						<!--Dynamically read from database -->
					</SELECT>
				</DIV>
				
				<DIV id='detail'>
					<?php
					
						//for each role in the Database
							//$role = 'textual description (e.g. LEM)
							//$role_id = role_id from db (e.g. 1)
							//$particiant_options = <OPTION value='$pid'>$particpant_name</OPTION> for all people willing to do said role
						while()
						?>
							<p class='role'><?=$role?></p>
							<SELECT id='pid_<?=$role_id?>'>
								<?=$particiant_options?>
							</SELECT>
						<?php
					?>
				</DIV>
			
			</DIV>
		</DIV>
	<?php
			include($theme_base . '/footer.php');
	?>
</HTML>


	