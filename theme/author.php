<?php
/**
 * @package WordPress
 * @subpackage planningpress
 */

get_header(); ?>

		<section id="primary" role="region">
			<div id="content">

				<?php the_post(); ?>

				<header class="page-header">
					<h1 class="page-title author"><?php printf( __( 'Author Archives: <span class="vcard">%s</span>', 'planningpress' ), "<a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a>" ); ?></h1>
				</header>

				<?php rewind_posts(); ?>

				<?php get_template_part( 'loop', 'author' ); ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_footer(); ?>