<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<STYLE type='text/css'>
			#content a {
				font-size: 12pt;
				font-color: black;
				margin-left: 5px;
			}
		</STYLE>

		<div id="container">
			<div id="content" role="main">
			
				<h1 class="page-title">Frequently Asked Questions</h1>

			<?php
				 $lastposts = get_posts('category=faq&meta_key=sort_order&orderby=meta_value&order=asc&numberposts=12');
				 foreach($lastposts as $post) :
						setup_postdata($post);
			 ?>
						 <h2>
						 	<a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>"><?php the_title(); ?></a>
						 </h2>
			 <?php endforeach; ?>
				

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
