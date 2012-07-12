<?php
/**
 * @package WordPress
 * @subpackage planningpress
 */

get_header(); ?>

	<div id="primary">
		<div id="content">

			<article id="post-0" class="post error404 not-found" role="article">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'planningpress' ); ?></h1>
				</header>

				<div class="entry-content clearfix">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'planningpress' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>