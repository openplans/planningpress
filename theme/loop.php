<?php
/**
 * @package WordPress
 * @subpackage planningpress
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<nav id="nav-above" role="article">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'planningpress' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'planningpress' ) ); ?></div>
	</nav><!-- #nav-above -->
<?php endif; ?>

<?php /* Start the Loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
		<header class="entry-header">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'planningpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

			<?php if ( !is_front_page() ){ ?><div class="entry-meta">
				<?php
					printf( __( '<span class="sep">Posted on </span><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a> <span class="byline"><span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%4$s" title="%5$s">%6$s</a></span></span>', 'planningpress' ),
						get_permalink(),
						get_the_date( 'c' ),
						get_the_date(),
						get_author_posts_url( get_the_author_meta( 'ID' ) ),
						sprintf( esc_attr__( 'View all posts by %s', 'planningpress' ), get_the_author() ),
						get_the_author()
					);
				?>
			</div><!-- .entry-meta --><?php } ?>
		</header><!-- .entry-header -->

		<?php if ( is_archive() || is_search() ) : // Only display Excerpts for archives & search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content clearfix">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'planningpress' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'planningpress' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<?php if ( !is_front_page() ){ ?><footer class="entry-meta">
			<span class="cat-links-wrapper">
			  <span class="cat-links"><span class="entry-utility-prep entry-utility-prep-cat-links"><?php _e( 'Posted in ', 'planningpress' ); ?></span><?php the_category( ', ' ); ?></span>
			  <span class="meta-sep"> | </span>
		  </span>
			<?php the_tags( '<span class="tag-links-wrapper"><span class="tag-links">' . __( 'Tagged ', 'planningpress' ) . '</span>', ', ', '<span class="meta-sep"> | </span></span>' ); ?>
			<span class="comments-link-wrapper">
			  <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'planningpress' ), __( '1 Comment', 'planningpress' ), __( '% Comments', 'planningpress' ) ); ?></span>
		  </span>
			<?php edit_post_link( __( 'Edit', 'planningpress' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
		</footer><!-- #entry-meta --><?php } ?>
	</article><!-- #post-<?php the_ID(); ?> -->

	<?php comments_template( '', true ); ?>

<?php endwhile; ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	<nav id="nav-below" role="article">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'planningpress' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'planningpress' ) ); ?></div>
	</nav><!-- #nav-below -->
<?php endif; ?>
