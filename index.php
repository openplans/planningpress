<?php
/**
 * @package WordPress
 * @subpackage planningpress
 */

get_header(); ?>

    <div id="primary" class="has-sidebar">
			<div id="content">

				<?php get_template_part( 'loop', 'index' ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>