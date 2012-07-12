<?php
/**
 * @package WordPress
 * @subpackage planningpress
 */

get_header(); ?>

		<div id="primary">
			<div id="content">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
					<header class="entry-header event-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>

                <?php 

                $custom = get_post_custom(get_the_ID());
                $sd = $custom["pprss_event_startdate"][0];
                $ed = $custom["pprss_event_enddate"][0];
                $allday = $custom["pprss_event_allday"][0];
                $hidedate = $custom["pprss_event_hidedate"][0];?>
                        <div class="event-meta">
                        <?php 
                        $terms = wp_get_object_terms($post->ID, 'pprss_event_tag');
                          if(!empty($terms)){
                            if(!is_wp_error( $terms )){
                              foreach($terms as $term){
                                echo '<span class="event-tag" style="background-color:' . $term->description . ';">';
                                echo '' . $term->name . ''; 
                                echo '<span class="hidden">:</span></span>';
                              }
                            }
                          } ?>
            <span class="event-date-and-time">
            <span class="event-date">
<?php

                if ($hidedate != 'yes'):
                      $startday = date("l, F jS, Y", $sd);
                      $endday = date("l, F jS, Y", $ed);
                      if ($startday != $endday) { // it's a multi-day event

                        // the days of the week range
                        $startdayofweek = date("l", $sd);
                        $enddayofweek = date("l", $ed);
                        $dayofweekrange = $startdayofweek . '&ndash;' . $enddayofweek;

                        // the days of the month(s) range
                        $startmonth = date("F, Y", $sd);
                        $endmonth = date("F, Y", $ed);
                        if ($startmonth != $endmonth) { // it's a multi-month event
                          $startyear = date("Y", $sd);
                          $endyear = date("Y", $ed);
                          if ($startyear != $endyear) { // if the daterange spans multiple years...
                            // show both years
                            $startdayofmonth = date("F jS, Y", $sd); 
                          } else {
                            // show only one year
                            $startdayofmonth = date("F jS", $sd); 
                          }
                          $enddayofmonth = date("F jS, Y", $ed);
                        } else { // it's a single-month event
                          $startdayofmonth = date("F jS", $sd);
                          $enddayofmonth = date("jS, Y", $ed);
                        }
                        $dayofmonthrange = $startdayofmonth . '&ndash;' . $enddayofmonth;

                        // set the date range for multi-day events
                        $date_range = $dayofweekrange . ', ' . $dayofmonthrange;
                        
                      } else { // it's a single-day event
                        $date_range = $startday;
                      }
                      echo $date_range;
                ?>
            </span>

            <?php 
            if ($allday != 'yes') {
            ?>
            <span class="event-time">
                (<?php echo date("g:i A", $sd);
                if ($ed != $sd) {
                    ?><span class="ndash">&ndash;</span><?php 
                    echo date("g:i A", $ed); 
                }
                ?>)
            </span>
            <?php 
            }
            else { 
            }
	    ?>
	    </span>
	    <?php
	    endif;

            ?>
</div>					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'bikeshare' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->

					<?php edit_post_link( __( 'Edit', 'bikeshare' ), '<span class="edit-link">', '</span>' ); ?>

				</article><!-- #post-<?php the_ID(); ?> -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>