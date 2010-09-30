<!DOCTYPE html>
<HTML>
	<?php
	require('wp-blog-header.php');
	?>
	
<!--	<base href="http://doverknoll.com/aldiechurch/" /> -->
	<?php
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	?>
	
	<script type="text/javascript">
	  var rpxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://static.");
	  document.write(unescape("%3Cscript src='" + rpxJsHost +
	"rpxnow.com/js/lib/rpx.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	  RPXNOW.overlay = true;
	  RPXNOW.language_preference = 'en';
	</script>
	
	<DIV id='container'>
	    
	    
	    <!-- Really, this is just the link inside of menu.php.  if isset($_SESSION['login_inf]) <a href=''>logout</a>) else ( -->
	     <a class="rpxnow" onclick="return false;"
href="https://aldiechurch.rpxnow.com/openid/v2/signin?token_url=http%3A%2F%2Fdoverknoll.com%2Faldiechurch%2Frpx.php"> Sign In </a> 
        <!-- rpx.php then redirects back to home.php, with you signed in... -->
	</DIV>
	
	
	<?php
			include($theme_base . '/footer.php');
	?>
</HTML>