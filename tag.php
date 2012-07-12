<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * @subpackage planningpress
 */

get_header(); ?>

		<section id="primary" role="region">
			<div id="content">

				<?php the_post(); ?>

				<header class="page-header">
					<h1 class="page-title"><?php
						printf( __( 'Tag Archives: %s', 'planningpress' ), '<span>' . single_tag_title( '', false ) . '</span>' );
					?></h1>
				</header>

				<?php rewind_posts(); ?>

				<?php get_template_part( 'loop', 'tag' ); ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_footer(); ?>