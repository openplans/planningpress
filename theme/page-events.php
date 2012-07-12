<?php
/**
 * Template Name: Events Template
 * Description: page template with a list of events
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
        </article><!-- #post-<?php the_ID(); ?> -->

                <?php rewind_posts(); ?>

                <div class="events-list">
                  <h2 class="upcoming-events">Upcoming Events</h2>
                  <?php 
                  $upcoming_events = get_upcoming_events();

                  if ($upcoming_events):

                  global $post;

                  foreach ($upcoming_events as $post):
                  setup_postdata($post);

                  $custom = get_post_custom(get_the_ID());
                  $sd = $custom["pprss_event_startdate"][0];
                  $ed = $custom["pprss_event_enddate"][0];
                  $allday = $custom["pprss_event_allday"][0];
                  $hidedate = $custom["pprss_event_hidedate"][0];
                  ?>

                  <?php if ($hidedate != 'yes') { ?>
                  <article id="post-<?php the_ID(); ?>" <?php post_class("clearfix has-date"); ?> role="article">
                  <?php } else { ?>
                  <article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?> role="article">
                  <?php } ?>
                      <header class="event-header">
                        <h3 class="entry-title"><a href="<?php the_permalink(); ?>" class="permalink"><?php the_title(); ?></a></h3>
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
                        <?php if ($hidedate != 'yes'):?>  
                          <span class="event-date-and-time">
                          <span class="event-date">
                          <?php 
                          $startday = date("l, F jS, Y", $sd);
                          $endday = date("l, F jS, Y", $ed);
                          if ($startday != $endday) { // it's a multi-day event

                            // the days of the week range
                            $startdayofweek = date("l", $sd);
                            $enddayofweek = date("l", $ed);
                            $dayofweekrange = '<span class="dayofweek">' . $startdayofweek . '&ndash;' . $enddayofweek . ' </span>';

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
                            $dayofmonthrange = '<span class="dayofmonth"><span>' . $startdayofmonth . '&ndash;</span>' . $enddayofmonth . '</span>';

                            // set the date range for multi-day events
                            $date_range = $dayofweekrange . '<span class="hidden">,</span> ' . $dayofmonthrange;

                          } else { // it's a single-day event
                            $startdayofweek = date("l", $sd);
                            $startdayofmonth = date("F jS, Y", $sd); 
                            $date_range = '<span class="dayofweek">' . $startdayofweek . ' </span>' . $startdayofmonth;
                          }

                          echo $date_range;

                          ?></span><?php 
                          if ($allday != 'yes') { 
                            ?><span class="hidden">,</span> <span class="event-time"><?php echo date("g:i A", $sd);
                            if ($ed != $sd) { ?><span class="ndash">&ndash;</span><?php echo date("g:i A", $ed); } ?></span><?php 
                          } ?>
                          </span>
                  		  <?php endif;?>
                          </div>
                      </header>

                      <div class="entry-content clearfix">
                        <?php if ( has_excerpt() ) {
                          the_excerpt(); 
                          ?> 
                          <a href="<?php the_permalink(); ?>" class="permalink">More Details&hellip;</a>
                          <?php 
                        } else {
                        the_content();  
                        ?>
                        <?php } ?>

                        <?php edit_post_link( __( 'Edit', 'planningpress' ), '<span class="edit-link">', '</span>' ); ?>
                      </div><!-- .entry-content -->
                  </article><!-- #post-<?php the_ID(); ?> -->

                  <?php
                  endforeach;

                  else :
                  echo "<p>There are no upcoming events.</p>";
                  endif;

                  ?>

                  <?php 
                  $past_events = get_past_events();

                  if ($past_events):

                  ?><h2 class="past-events">Past Events</h2><?php

                  global $post;

                  foreach ($past_events as $post):
                  setup_postdata($post);

                  $custom = get_post_custom(get_the_ID());
                  $sd = $custom["pprss_event_startdate"][0];
                  $ed = $custom["pprss_event_enddate"][0];
                  $allday = $custom["pprss_event_allday"][0];
                  $hidedate = $custom["pprss_event_hidedate"][0];
                  ?>

                  <?php if ($hidedate != 'yes') { ?>
                  <article id="post-<?php the_ID(); ?>" <?php post_class("clearfix has-date"); ?> role="article">
                  <?php } else { ?>
                  <article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?> role="article">
                  <?php } ?>
                      <header class="event-header">
                        <h3 class="entry-title"><a href="<?php the_permalink(); ?>" class="permalink"><?php the_title(); ?></a></h3>
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
                        <?php if ($hidedate != 'yes'):?>  
                          <span class="event-date-and-time">
                          <span class="event-date">
                          <?php 
                          $startday = date("l, F jS, Y", $sd);
                          $endday = date("l, F jS, Y", $ed);
                          if ($startday != $endday) { // it's a multi-day event

                            // the days of the week range
                            $startdayofweek = date("l", $sd);
                            $enddayofweek = date("l", $ed);
                            $dayofweekrange = '<span class="dayofweek">' . $startdayofweek . '&ndash;' . $enddayofweek . ' </span>';

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
                            $dayofmonthrange = '<span class="dayofmonth"><span>' . $startdayofmonth . '&ndash;</span>' . $enddayofmonth . '</span>';

                            // set the date range for multi-day events
                            $date_range = $dayofweekrange . '<span class="hidden">,</span> ' . $dayofmonthrange;

                          } else { // it's a single-day event
                            $startdayofweek = date("l", $sd);
                            $startdayofmonth = date("F jS, Y", $sd); 
                            $date_range = '<span class="dayofweek">' . $startdayofweek . ' </span>' . $startdayofmonth;
                          }

                          echo $date_range;

                          ?></span><?php 
                          if ($allday != 'yes') { 
                            ?><span class="hidden">,</span> <span class="event-time"><?php echo date("g:i A", $sd);
                            if ($ed != $sd) { ?><span class="ndash">&ndash;</span><?php echo date("g:i A", $ed); } ?></span><?php 
                          } 

                          ?>
                        </span>
                  		  <?php endif;?>
                    		</div>
                      </header>

                      <div class="entry-content clearfix">
                        <?php if ( has_excerpt() ) {
                          the_excerpt(); 
                          ?> 
                          <a href="<?php the_permalink(); ?>" class="permalink">More Details&hellip;</a>
                          <?php 
                        } else {
                        the_content();  
                        ?>
                        <?php } ?>

                        <?php edit_post_link( __( 'Edit', 'planningpress' ), '<span class="edit-link">', '</span>' ); ?>
                      </div><!-- .entry-content -->
                  </article><!-- #post-<?php the_ID(); ?> -->

                  <?php
                  endforeach;

                  else :
                  echo "<p>There are no past events.</p>";
                  endif;

                  ?>
                </div>

                <?php comments_template( '', true ); ?>

              </div><!-- #content -->
            </div><!-- #primary -->

        <?php get_footer(); ?>
