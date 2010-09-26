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
	
			<base href="http://doverknoll.com/aldiechurch/" />
	
			<div id="container">
				<div id="content" role="main">
	
	<?php if ( have_posts() ) : ?>
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					<?php
					/* Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called loop-search.php and that will be used instead.
					 */
					 get_template_part( 'loop', 'search' );
					?>
	<?php else : ?>
					<div id="post-0" class="post no-results not-found">
						<h2 class="entry-title">What would you like to find?</h2>
						<div class="entry-content">
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</div><!-- #post-0 -->
	<?php endif; ?>
				</div><!-- #content -->
			</div><!-- #container -->
	
	<?php get_footer(); ?>
</HTML>