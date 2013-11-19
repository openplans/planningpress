<?php

  #
  #
        /* Sidebar Widget */
        #
  #
  /*

Copyright 2008, The Open Planning Project

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St - 5th Floor, Boston, MA  02110-1301, USA.
*/

function widget_word_on_the_street($args) {

  if (!is_archive()) {
  extract($args);
  $options = get_option('widget_word_on_the_street');
  $commenter_name = stripcslashes($options['commenter_name']);
  $comment_link = stripcslashes($options['comment_link']);
  $post_title = stripcslashes($options['post_title']);
  $comment_excerpt = stripcslashes(apply_filters('widget_text', $options['comment_excerpt']));
  $author_url = stripcslashes($options['author_url']);

  echo $before_widget;
  echo $before_title . "Featured Comment" . $after_title;

  ?>
     <blockquote class="featured-comment" class="selfclear"><span class="open-quote">&#8220;</span><span class="the-quote"><?php echo $comment_excerpt; ?></span><span class="close-quote">&#8221;</span></blockquote>
     <p class="featured-comment-footer">
       <cite><?php echo $commenter_name ?></cite><span class="comment-source">, on <a href="<?php echo $comment_link; ?>"><?php echo $post_title; ?></a></span>
     </p>
<?php
  echo $after_widget;

  }
}


function widget_word_on_the_street_update_options() {
        $options = $newoptions = get_option('widget_word_on_the_street');
        if ( $_POST['word-on-the-street-submit'] ) {
                $newoptions['commenter_name'] = htmlspecialchars($_POST['word-on-the-street-commenter-name'], ENT_QUOTES, "UTF-8");
                $newoptions['comment_link'] = htmlentities($_POST['word-on-the-street-comment-link']);
                $newoptions['post_title'] = htmlspecialchars($_POST['word-on-the-street-post-title'], ENT_QUOTES, "UTF-8");
                $newoptions['comment_excerpt'] = $_POST['word-on-the-street-comment-excerpt'];
                $newoptions['author_url'] = htmlentities($_POST['word-on-the-street-author-url']);
        }

        if ( $options != $newoptions ) {
                $options = $newoptions;
        if (function_exists('wp_cache_phase2_clean_cache')) {
           wp_cache_phase2_clean_cache('wp-cache-');
        }
                update_option('widget_word_on_the_street', $options);
        }
  return $options;
}
function widget_word_on_the_street_control() {

  $options = widget_word_on_the_street_update_options();

  $commenter_name = stripslashes($options['commenter_name']);
  $comment_link = stripslashes($options['comment_link']);
  $post_title = stripslashes($options['post_title']);
  $comment_excerpt = $options['comment_excerpt'];
  $author_url = stripslashes($options['author_url']);

  echo widget_word_on_the_street_form($commenter_name, $comment_link,
                                 $post_title, $comment_excerpt, $author_url);

}

function widget_word_on_the_street_form($commenter_name, $comment_link,
                                        $post_title, $comment_excerpt, $author_url) {
  return '<input type="hidden" id="word-on-the-street-submit" name="word-on-the-street-submit" value="1" />
  <input id="word-on-the-street-commenter-name" name="word-on-the-street-commenter-name" type="hidden" value="' . $commenter_name . '" />
  <input id="word-on-the-street-comment-link" name="word-on-the-street-comment-link" type="hidden" value="' . $comment_link . '"/>
  <input id="word-on-the-street-post-title" name="word-on-the-street-post-title" type="hidden" value="' . $post_title . '"/>
  <input id="word-on-the-street-author-url" name="word-on-the-street-author-url" type="hidden" value="' . $author_url . '"/>
  <label for="word-on-the-street-comment-excerpt" style="line-height: 1.2em; font-weight: bold;">Feature this comment in the "Featured Comment" widget:</label>
  <textarea cols="70" rows="10" style="width: 100%; height: 80px;" id="word-on-the-street-comment-excerpt" name="word-on-the-street-comment-excerpt">' . $comment_excerpt . '</textarea>';

}

add_action('template_redirect', 'widget_word_on_the_street_from_comment');
function widget_word_on_the_street_from_comment() {
  $wots_action = $_GET['wots'];
  if($wots_action === "set_word" )
    {
      if (current_user_can('moderate_comments')) {
          $options = widget_word_on_the_street_update_options();
          wp_redirect($options['comment_link']);
          exit;
      } else {
        echo "Insufficient Priviledges!<br/>";
      }
    }

}

function wots_expander() {
 ?>
   <script type="text/javascript">
      var glob;
      jQuery(document).ready( function() {
               jQuery(this).find('.expander_link').click( function() {
                  jQuery(this).parent().find('.expander_content').toggle("fast");
                  return false;
               });
               jQuery(this).find('.expander_close').click( function() {
                  jQuery(this).parent().parent().hide("fast");
                  return false;
              });
      });


   </script>
 <?php
}

add_action('wp_head', 'wots_expander');

/* link from within comment */
function the_word_link($comment_text)
{

  if(is_admin() || !current_user_can('moderate_comments')) return $comment_text;

  global $comment;
  global $wpdb;
  $comment_id = get_comment_id();
  $results =  $wpdb->get_row("select post_title from $wpdb->posts inner join $wpdb->comments on $wpdb->posts.ID=$wpdb->comments.comment_post_id where $wpdb->comments.comment_ID = $comment_id");
  $o = " | ";
  $o .= '<span class="expander"><a href="" class="expander_link">';
  $o .= 'Feature</a>';
  $o .= '<div style="display:none; padding: 10px; border: 3px solid #ccc; background: #ffffcc; border-top: none;" class="expander_content"> <form action="' .get_permalink() .'?wots=set_word" method="POST">';
  $o .= widget_word_on_the_street_form(get_comment_author(), get_comment_link(),
                                     htmlspecialchars($results->post_title, ENT_QUOTES, "UTF-8"), get_comment_text(), get_comment_author_url());
  $o .= '<input type="submit" name="Submit" value="Go!" />  or <a class="expander_close" href="#">Cancel</a>';
  $o .= '</form></div></span>';

  return $comment_text . $o;
}

add_filter('edit_comment_link', 'the_word_link');

wp_register_sidebar_widget( 'widget-featured-comment', "Featured Comment", 'widget_word_on_the_street');
wp_register_widget_control( 'widget-featured-comment', "Featured Comment", 'widget_word_on_the_street_control', 500, 500);

?>
