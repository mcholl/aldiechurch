<!DOCTYPE html>
<HTML>
	<?php
	require('wp-blog-header.php');
	$url = $_GET['url']; 
	$src="https://docs.google.com/viewer?url=$url&embedded=true";
	//$src=$src . '&tail=' . rand(10000,100000);
	/*This page is called as follows:
			showpdf.php?url=http://mysite.com/myfile.PDF
	    The passed pdf is then displayed in an inframe on this page,
	    using google docs viewer.
	*/
	?>
	
	<?php
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	?>
		
	<DIV id='container'>
		<!--
			<H2>Showing <?=$url?></H2>
			<p>full link <?=$src?></p> -->
			<iframe id='FrameID'   style="border-width:0;" width="730" height="600" src='<?=$src?>' frameborder="0" scrolling="no" > </iframe>
	</DIV>
	
	
	<?php
			include($theme_base . '/footer.php');
	?>
</HTML>