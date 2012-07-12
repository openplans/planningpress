<?php
/**
 * Template Name: Page with Sidebar
 * Description: a template with a sidebar
 *
 * @package WordPress
 * @subpackage planningpress
 */

get_header(); ?>

		<div id="primary" class="has-sidebar">
			<div id="content">

				<?php the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content clearfix">
						<?php the_content(); ?>
						<?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'planningpress' ) . '&after=</div>' ); ?>
						<?php edit_post_link( __( 'Edit', 'planningpress' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-<?php the_ID(); ?> -->

				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>