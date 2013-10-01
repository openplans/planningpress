<?php
/**
 * @package WordPress
 * @subpackage planningpress
 */
?>

        </div><!-- #main  -->

</div><!-- #page -->
        <footer id="colophon" role="contentinfo" class="clearfix">

          <div id="site-credit" class="banner banner-anchor">
            <?php
            $opt_val_colophon_text = get_option( 'pprss_colophon_text' );
            if ($opt_val_colophon_text) {
             echo stripslashes($opt_val_colophon_text);
            }
            else {
             echo 'PlanningPress is a project of <a href="http://openplans.org/">OpenPlans</a>.';
            }
            ?>
          </div>

          <nav id="footer-nav" class="clearfix banner-inline-list banner" role="article">
            <?php wp_nav_menu( array(
                'theme_location' => 'footer',
                'fallback_cb'    => false
              ) ); ?>
          </nav><!-- #footer-nav -->

        </footer><!-- #colophon -->

<?php if ( is_page_template("page-map.php") ) { ?>
<script src="<?php echo get_template_directory_uri(); ?>/leaflet/leaflet.js"></script>
<?php } ?>

<script type="text/javascript">
  jQuery(function ($) {
    $(document).ready(function() {

      $('#nav-bttn').click(function(){
        $('#sidebar-nav').toggleClass('expose');
        return false; 
      });

      <?php if ( is_page_template("page-map.php") ) { locate_template( '/js/page-map-js-leaflet.php', true ); } ?>


        jQuery(".colorbox").colorbox({maxWidth:"95%", maxHeight:"95%"});

        // temp hide the title attribute on hover so it doesn't yeild a tooltip
        jQuery(".colorbox").hover(
            function () {
                jQuery(this).attr('tmptitle', jQuery(this).attr('title'));
                jQuery(this).attr('title', '');
            },
            function () {
                jQuery(this).attr('title', jQuery(this).attr('tmptitle'));
            }
        );
        jQuery(".colorbox").click(
            function () {
                jQuery(this).attr('title', jQuery(this).attr('tmptitle'));
            }
        );

        if (jQuery("ul#slides").length) {
        jQuery.ajaxSetup({cache:false});
        jQuery("ul#slides li").hide(); //Hide all content
        var comments_url = '<?php bloginfo('url');?>/?p=';
        if (window.location.hash) {
            var h = window.location.hash.split('#')[1];
            if (jQuery("ul#slides li#" + h).length) {
              jQuery("ul#slides li#" + h).show(); //Show first tab content
              jQuery("ul#slides-nav li." + h).addClass("active").show(); //Activate first tab
              jQuery("#comments").html('loading . . .');
              jQuery("#comments").load(comments_url + h + ' #comments', ajax_callback_scroll);
            } else {
              jQuery("ul#slides li:first").show(); //Show first tab content
              jQuery("ul#slides-nav li:first").addClass("active").show(); //Activate first tab
              var activeSlide = jQuery("ul#slides-nav li:first").find("a").attr("href"); //Find the href attribute value to identify the active tab + content
              var slideid = jQuery(activeSlide).attr('id');
              jQuery("#comments").html('loading . . .');
              jQuery("#comments").load(comments_url + slideid + ' #comments', ajax_callback);
            }
        } else {
            jQuery("ul#slides li:first").show(); //Show first tab content
            jQuery("ul#slides-nav li:first").addClass("active").show(); //Activate first tab
            var activeSlide = jQuery("ul#slides-nav li:first").find("a").attr("href"); //Find the href attribute value to identify the active tab + content
            var slideid = jQuery(activeSlide).attr('id');
            jQuery("#comments").html('loading . . .');
            jQuery("#comments").load(comments_url + slideid + ' #comments', ajax_callback);
        }


        //On Click Event
        jQuery("ul#slides-nav li").click(function() {

            jQuery("ul#slides-nav li").removeClass("active"); //Remove any "active" class
            jQuery(this).addClass("active"); //Add "active" class to selected tab
            jQuery("ul#slides li").hide(); //Hide all tab content

            var activeSlide = jQuery(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
            jQuery(activeSlide).show(); //Fade in the active ID content

            jQuery(".slides-share-link").hide();
            var slideid = jQuery(activeSlide).attr('id');
            jQuery("#comments").html('loading . . .');
            jQuery("#comments").load(comments_url + slideid + ' #comments', ajax_callback);
            return false;
        });


        //Next
        jQuery("#slides-next").click(function(){
            var currli = jQuery("ul#slides li:visible");
            // get next list item
            var nextli = currli.next();
            // if nextli length is 0, make it equal to first li
            if (nextli.length == 0) {
                nextli = currli.siblings(':first');
            }
            jQuery("ul#slides-nav li").removeClass("active"); //Remove any "active" class
            currli.hide();

            nextli.show();
            jQuery("ul#slides-nav li").eq(nextli.index()).addClass("active"); //Highlight the current nav item

            jQuery(".slides-share-link").hide();

            var slideid = nextli.attr('id');
            jQuery("#comments").html('loading . . .');
            jQuery("#comments").load(comments_url + slideid + ' #comments', ajax_callback);
            return false;
        });

        //Prev
        jQuery("#slides-prev").click(function(){
            var currli = jQuery("ul#slides li:visible");
            var prevli = currli.prev();
            if (prevli.length == 0) {
                prevli = currli.siblings(':last');
            }
            jQuery("ul#slides-nav li").removeClass("active"); //Remove any "active" class
            currli.hide();

            jQuery("ul#slides-nav li").eq(prevli.index()).addClass("active"); //Highlight the current nav item
            prevli.show();

            jQuery(".slides-share-link").hide();
            var slideid = prevli.attr('id');
            jQuery("#comments").html('loading . . .');
            jQuery("#comments").load(comments_url + slideid + ' #comments', ajax_callback);
            return false;
        });

        //Slides Share Popup Link
        jQuery(".slides-permalink").click(function() {
            jQuery(".slides-share-link").show();
            jQuery(".slides-share-link-input").focus()
            jQuery(".slides-share-link-input").select()
            return false;
        });
        jQuery(".slides-share-link-close").click(function() {
            jQuery(".slides-share-link").hide();
            return false;
        });
        jQuery(".slides-share-link-input").click(function() {
            this.focus();
            this.select();
        });
        }

    });
  });

function ajax_callback(response, status, xhr) {
  var newurl = jQuery("ul#slides li:visible .slides-share-link-input").val();
  jQuery('#commentform').append('<input type="hidden" name="redirect_to" value="' + newurl + '" id="redirect_to">')
    jQuery('a.comment-link').each(function() {
        newhref = jQuery(this).attr('href').split('comment-')[1];
        newhref = newurl + '#comment-' + newhref;
        jQuery(this).attr('href', newhref);
      });
}
function ajax_callback_scroll(response, status, xhr) {
  ajax_callback(response, status, xhr);
  var h = '#' + window.location.hash.split('#')[2];
  jQuery('html, body').animate({
    scrollTop: jQuery(h).offset().top
        });
}

</script>

<?php wp_footer(); ?>

</body>
</html>