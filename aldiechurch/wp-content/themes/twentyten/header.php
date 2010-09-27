<?php
/**
 *@package WordPress
 *@subpackage TWenty_Ten
 *@since Twenty Ten 1.0
 */?>
<!DOCTYPE html>
<HTML <?php language_attributes(); ?>>
	<HEAD>
		<TITLE>Church of Our Redeemer</TITLE>
		<STYLE type='text/css'>
			<?php include('mystyle.css'); ?>
		</STYLE>
	</HEAD>
	
	<BODY <?php body_class(); ?>>
		<?php
			$base = '/aldiechurch';
		?>

		<DIV id='topmargin'>&nbsp;</DIV>
		<DIV id='header'>
			<IMG src='<?= $base ?>/images/episshield.png' />
			<DIV>
				<p><SPAN class='blue'>The Church of&nbsp;</SPAN><SPAN class='red'>Our Redeemer</SPAN><p>
				<p>To Know Him and Make Him Known In Word and Deed</p>
			</DIV>
			<!--<IMG src='<?= $base ?>/images/large_church2.jpg' />-->
			<br />
		</DIV>

		<?php include('nav.php'); ?>
