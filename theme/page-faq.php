<?php
/**
 * Template Name: FAQ Template
 * Description: page template with a list of FAQs
 *
 * @package WordPress
 * @subpackage planningpress
 */

get_header(); ?>

    <div id="primary">
      <div id="content">

        <?php the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->
          <div class="entry-content clearfix">
            <?php the_content(); ?>
            <?php edit_post_link( __( 'Edit', 'planningpress' ), '<span class="edit-link">', '</span>' ); ?>
          </div><!-- .entry-content -->
          <?php rewind_posts(); ?>

          <?php
          $faq_query = new WP_Query('post_type=pprss_faq&posts_per_page=-1&orderby=menu_order&order=ASC');
          ?>

          <ul>
            <?php while ( $faq_query->have_posts() ) : $faq_query->the_post(); ?>
            <li><a href="#<?php $slug = basename(get_permalink()); echo $slug; ?>" title="<?php the_title(); ?>" rel="bookmark" class="trigger"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
          </ul>

        </article><!-- #post-<?php the_ID(); ?> -->


        <div class="faq-list">
          <?php rewind_posts();
          while ( $faq_query->have_posts() ) : $faq_query->the_post(); 
          ?>

          <?php $slug = basename(get_permalink()); ?>

          <article id="<?php echo $slug; ?>" <?php post_class(); ?> role="article">
            <h5 class="entry-title"><a href="#<?php echo $slug; ?>" title="<?php the_title(); ?>" rel="bookmark" class="trigger"><?php the_title(); ?></a></h5>
            <div class="entry-content">
              <?php the_content(); ?>
            </div><!-- .entry-content -->
            <?php edit_post_link( __( 'Edit&nbsp;&rarr;', 'openplans' ), '<span class="edit-link">', '</span>' ); ?>
          </article><!-- #post-<?php the_ID(); ?> -->

          <?php endwhile; ?>

        </div>

        <?php comments_template( '', true ); ?>

      </div><!-- #content -->
    </div><!-- #primary -->

<?php get_footer(); ?>