<!DOCTYPE html>
<HTML>
	<?php
	/**
	 * The template for displaying Search Results pages.
	 *
	 * @package WordPress
	 * @subpackage Twenty_Ten
	 * @since Twenty Ten 1.0
	 */
	require('wp-blog-header.php');
	get_header(); ?>
	
			<STYLE type='text/css'>
				#container {
					height: 1500px;
				}
				
				#container ul, #container li {
					list-style: none;
					margin: 0px;
					padding-left: 15px;
				}
				
				#container ul {
					margin-top: 5px;
					margin-bottom: 15px;
				}

				#content #menu { display: table; }
				#content #menu li, #content #menu li ul { 
					display: table-row; 
				}
				#content #menu li a {
					display: table-cell;
					width: 140px;
				}				
				#content #menu li p {
					display: table-cell;
					width: 350px;
					padding-bottom: 10px;
				}
				
			</STYLE>
	
	
			<div id="container">
				<div id="content" role="main">
				
					<?php include('menu.php'); ?>
				</div><!-- #content -->
			</div><!-- #container -->
	
	<?php get_footer(); ?>
</HTML>