<!DOCTYPE html>
<HTML>
	<?php
	require('wp-blog-header.php');
	?>
	
	<base href="http://doverknoll.com/aldiechurch/" />
	<?php
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	?>
	
	
	<DIV id='container'>
		<iframe src="http://www.google.com/calendar/embed?showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=aldiechurch.com_652e7cd34hnti0h9u0p2omt5ro%40group.calendar.google.com&amp;color=%238D6F47&amp;ctz=America%2FNew_York" style=" border-width:0 " width="630" height="600" frameborder="0" scrolling="no"></iframe>
	</DIV>
	
	
	<?php
			include($theme_base . '/footer.php');
	?>
</HTML>