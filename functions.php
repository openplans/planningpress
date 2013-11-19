<?php
/**
 * @package WordPress
 * @subpackage planningpress
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 */
load_theme_textdomain( 'planningpress', TEMPLATEPATH . '/languages' );
include_once('word-on-the-street.php');

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
        require_once( $locale_file );

/* Set the content width based on the theme's design and stylesheet. */
if ( ! isset( $content_width ) )
        $content_width = 600;

/* Remove code from the <head> */
//remove_action('wp_head', 'rsd_link'); // Might be necessary if you or other people on this site use remote editors.
//remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
//remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
//remove_action('wp_head', 'index_rel_link'); // Displays relations link for site index
//remove_action('wp_head', 'wlwmanifest_link'); // Might be necessary if you or other people on this site use Windows Live Writer.
//remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
//remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
//remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_filter( 'the_content', 'capital_P_dangit' ); // Get outta my Wordpress codez dangit!
remove_filter( 'the_title', 'capital_P_dangit' );
remove_filter( 'comment_text', 'capital_P_dangit' );

// Hide the version of WordPress you're running from source and RSS feed // Want to JUST remove it from the source? Try: remove_action('wp_head', 'wp_generator');
function remove_wp_versn() {return '';}
add_filter('the_generator', 'remove_wp_versn');

// This function removes the comment inline css
function remove_recent_comments_style() {
        global $wp_widget_factory;
        remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'remove_recent_comments_style' );

/* Remove meta boxes from Post and Page Screens */
function customize_meta_boxes() {
   /* These remove meta boxes from POSTS */
  //remove_post_type_support("post","excerpt"); //Remove Excerpt Support
  //remove_post_type_support("post","author"); //Remove Author Support
  //remove_post_type_support("post","revisions"); //Remove Revision Support
  //remove_post_type_support("post","comments"); //Remove Comments Support
  //remove_post_type_support("post","trackbacks"); //Remove trackbacks Support
  //remove_post_type_support("post","editor"); //Remove Editor Support
  //remove_post_type_support("post","custom-fields"); //Remove custom-fields Support
  //remove_post_type_support("post","title"); //Remove Title Support


  /* These remove meta boxes from PAGES */
  //remove_post_type_support("page","revisions"); //Remove Revision Support
  //remove_post_type_support("page","comments"); //Remove Comments Support
  //remove_post_type_support("page","author"); //Remove Author Support
  //remove_post_type_support("page","trackbacks"); //Remove trackbacks Support
  //remove_post_type_support("page","custom-fields"); //Remove custom-fields Support

}
add_action('admin_init','customize_meta_boxes');

/* This theme uses wp_nav_menus() for the header menu, utility menu and footer menu. */
function register_pprss_menus() {
  register_nav_menus( array(
        'header' => __( 'Header Menu', 'planningpress' ),
        'sidebar' => __( 'Sidebar Menu', 'planningpress' ),
        'footer' => __( 'Footer Menu', 'planningpress' ),
        'utility' => __( 'Utility Menu', 'planningpress' )
  ) );
}
add_action( 'init', 'register_pprss_menus' );

/* Add default posts and comments RSS feed links to head */
add_theme_support( 'automatic-feed-links' );

/* This theme uses post thumbnails */
add_theme_support( 'post-thumbnails' );

/* This theme supports editor styles */
add_editor_style("/css/editor-style.css");

/* Disable the admin bar */
show_admin_bar( false );

/* This enables post formats. If you use this, make sure to delete any that you aren't going to use. */
//add_theme_support( 'post-formats', array( 'aside', 'audio', 'image', 'video', 'gallery', 'chat', 'link', 'quote', 'status' ) );

/* Register widgetized area and update sidebar with default widgets */
function planningpress_widgets_init() {

  register_sidebar( array (
    'name' => __( 'Sidebar', 'planningpress' ),
    'id' => 'sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
    'after_widget' => "</aside>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );

  register_sidebar( array (
    'name' => __( 'Above Frontpage', 'planningpress' ),
    'id' => 'above-frontpage',
    'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
    'after_widget' => "</aside>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );

}
add_action( 'init', 'planningpress_widgets_init' );

/* Remove senseless dashboard widgets for non-admins. (Un)Comment or delete as you wish. */
function remove_dashboard_widgets() {
        global $wp_meta_boxes;

        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // Plugins widget
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPress Blog widget
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // Other WordPress News widget
        //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // Right Now widget
        //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // Quick Press widget
        //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // Incoming Links widget
        //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // Recent Drafts widget
        //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Recent Comments widget
}

/* Hide Menu Items */
function planningpress_configure_menu_page(){
        //remove_menu_page("link-manager.php"); //Hide Links
        //remove_menu_page("edit-comments.php"); //Hide Comments
        //remove_menu_page("tools.php"); //Hide Tools
}

if (!current_user_can('manage_options')) {
        add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
        add_action("admin_menu","planningpress_configure_menu_page"); //While we're add it, let's configure the menu options as well
}


/*
 *
 * Theme Options
 *
 */
function pprss_theme_options_menu() {
    add_submenu_page( 'themes.php', 'Theme Options', 'Theme Options', 'manage_options', 'theme-options', 'pprss_theme_options' );
}
add_action('admin_menu', 'pprss_theme_options_menu');


if (!function_exists('pprss_theme_options')){
function pprss_theme_options() {
  //must check that the user has the required capability
  if (!current_user_can('manage_options')) { wp_die( __('You do not have sufficient permissions to access this page.') ); }

  // variables for the field and option names
  $hidden_field_name = 'my_submit_hidden';

  // Read in existing option values from database
  $opt_val_colophon_text = get_option( 'pprss_colophon_text' );
  $opt_val_use_header_search = get_option( 'pprss_use_header_search' );

  // See if the user has posted us some information
  // If they did, this hidden field will be set to 'Y'
  if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
    // Read their posted value
    $opt_val_colophon_text = $_POST[ 'pprss_colophon_text' ];
    $opt_val_use_header_search = $_POST[ 'pprss_use_header_search' ];
    // Save the posted value in the database
    update_option( 'pprss_colophon_text', $opt_val_colophon_text );
    update_option( 'pprss_use_header_search', $opt_val_use_header_search );
    // Put an settings updated message on the screen
    ?><div class="updated"><p><strong>Theme options saved.</strong></p></div><?php
  }

  // Now display the settings editing screen
  ?>
  <div class="wrap">
    <div class="icon32"><img src="<?php bloginfo('template_url') ?>/images/image-sunset-32-gray.png" /></div>
    <h2>Theme Options</h2>
    <form name="form1" method="post" action="">
      <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

      <h3 class="title">Site Credits &amp; Copyright Information</h3>
      <p><textarea name="pprss_colophon_text" id="pprss_colophon_text" rows="2" cols="90"><?php echo stripslashes($opt_val_colophon_text); ?></textarea></p>

      <p><input type="checkbox" name="pprss_use_header_search" id="pprss_use_header_search" value="pprss_use_header_search"<?php if( $opt_val_use_header_search ) { echo 'checked="checked"'; }; ?> /> Include search form in header.</p>

      <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>
    </form>
  </div>
  <?php
}
/* ---------- end Theme Options */
}

/*
 *
 * Appearance > Menus > Contextual Help
 *
 */
function my_custom_help($contextual_help, $screen_id, $screen) {
        if ( $screen_id == 'nav-menus' ) {
                $contextual_help .= '<p>The following CSS classes will add icons to menu items: <small>';
                $contextual_help .= 'address-book, alarm-clock, arrow-090, arrow-180, arrow-270, arrow, balloon-facebook, balloon-twitter, ';
                $contextual_help .= 'balloon, bell, bin, book, bookmark, box-label, box, briefcase, calculator, calendar-month, camera, ';
                $contextual_help .= 'card-address, clock, color-swatch, color, cross, document-pdf, document-text, document-word, drive, ';
                $contextual_help .= 'exclamation, feed, film, fire, folder-horizontal, folder, globe-green, globe-model, globe, home, ';
                $contextual_help .= 'image-sunset, image, inbox-document-text, inbox-film, inbox-image, inbox-slide, inbox-table, inbox, ';
                $contextual_help .= 'information, jar-label, jar, lifebuoy, light-bulb, magnet, magnifier, mail, map, marker, media-player, ';
                $contextual_help .= 'megaphone, microphone, mobile-phone, monitor-sidebar, monitor, newspaper, notebook, paper-bag-label, ';
                $contextual_help .= 'paper-bag, pencil, photo-album, plus, printer, question, receipt-text, receipt, ruler-triangle, ruler, scissors, ';
                $contextual_help .= 'script-text, script, server, service-bell, sitemap, smiley-lol, smiley-sad, smiley-wink, smiley, soap, ';
                $contextual_help .= 'socket, sofa, sort, stamp, star, sticky-note-pin, sticky-notes-pin, sticky-notes, sticky-note, store, ';
                $contextual_help .= 'switch, system-monitor, .table, tag-label-black, tag-label-blue, tag-label-gray, tag-label-green, ';
                $contextual_help .= 'tag-label-pink, tag-label-purple, tag-label-red, tag-label-yellow, tag, target, telephone, television, ';
                $contextual_help .= 'terminal, thumb-up, thumb, tick, ticket, user-business-boss, user-business, user-female, user, vise, ';
                $contextual_help .= 'wall-brick, wall, wand-hat, wand, water, weather-cloudy, weather-lightning, weather-rain, weather-snow, ';
                $contextual_help .= 'weather, webcam, wheel, wooden-box, wrench</small></p>';
        }
        return $contextual_help;
}
add_filter('contextual_help', 'my_custom_help', 10, 3);


/*
 *
 * Custom Post Type: Events
 *
 */
add_action('init', 'add_cpt_event');
function add_cpt_event()
{
  $labels = array(
    'name' => _x('Events', 'post type general name'),
    'singular_name' => _x('Event', 'post type singular name'),
    'add_new' => _x('Add New', 'pprss_event'),
    'add_new_item' => __('Add New Event'),
    'edit_item' => __('Edit Event'),
    'new_item' => __('New Event'),
    'view_item' => __('View Event'),
    'search_items' => __('Search Events'),
    'not_found' =>  __('No events found'),
    'not_found_in_trash' => __('No events found in Trash'),
    'parent_item_colon' => '',
    'taxonomies' => '',
    'menu_name' => 'Events'
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array("slug" => "event"),
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','excerpt')
  );
  register_post_type('pprss_event',$args);
}

// show columns for Events in admin
function pprss_event_edit_columns($columns) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Event",
        "pprss_col_ev_date" => "Date",
        "pprss_col_ev_times" => "Time",
        "pprss_col_ev_desc" => "Description",
        );
    return $columns;
}
add_filter ("manage_edit-pprss_event_columns", "pprss_event_edit_columns");

function pprss_event_custom_columns($column) {
    global $post;
    $custom = get_post_custom();
    switch ($column){
        case "pprss_col_ev_date":
            // - show dates -
            $startd = $custom["pprss_event_startdate"][0];
            $endd = $custom["pprss_event_enddate"][0];
            $startdate = date("F j, Y", $startd);
            $enddate = date("F j, Y", $endd);
            echo $startdate . '<br /><em>' . $enddate . '</em>';
        break;
        case "pprss_col_ev_times":
            // - show times -
            $startt = $custom["pprss_event_startdate"][0];
            $endt = $custom["pprss_event_enddate"][0];
            $time_format = get_option('time_format');
            $starttime = date($time_format, $startt);
            $endtime = date($time_format, $endt);
          $allday = $custom["pprss_event_allday"][0];
          if ($allday != 'yes') {
                echo $starttime;
              if ($endtime) echo '<br /><em>' . $endtime . '</em>';
          }
          else { print '<em>(all day)</em>'; }
        break;
        case "pprss_col_ev_desc";
            the_excerpt();
        break;
    }
}
add_action ("manage_posts_custom_column", "pprss_event_custom_columns");

// show Events meta box
function pprss_event_create() {
    add_meta_box('pprss_event_meta', 'Event Details', 'pprss_event_meta', 'pprss_event', 'side', 'high');
    add_meta_box('new-meta-boxes', __('MyGeoPosition.com Geotags / GeoMetatags / GeoFeedtags / GeoMicroformats'), 'mygpGeotagsGeoMetatags_displayMetaBox', 'pprss_event', 'normal', 'high');

}
add_action( 'admin_init', 'pprss_event_create' );

function pprss_event_meta () {
    // - grab data -
    global $post;
    $custom = get_post_custom($post->ID);
    $meta_sd = $custom["pprss_event_startdate"][0];
    $meta_ed = $custom["pprss_event_enddate"][0];
    $meta_ad = $custom["pprss_event_allday"][0];
    $meta_hidedate = $custom["pprss_event_hidedate"][0];

    // - convert to pretty formats -
    $clean_st_h = '';
    $clean_st_m = '';
    $clean_st_a = '';

    $clean_et_h = '';
    $clean_et_m = '';
    $clean_et_a = '';

    if (!$meta_sd) { $clean_sd = ''; }
    else { $clean_sd = date("D, M d, Y", $meta_sd); }

    if ($meta_sd && ($meta_ad != 'yes')) {
        $clean_st_h = date("h", $meta_sd);
        $clean_st_m = date("i", $meta_sd);
        $clean_st_a = date("a", $meta_sd);
    }

    if (!$meta_ed) { $clean_ed = ''; }
    else {  $clean_ed = date("D, M d, Y", $meta_ed); }

    if ($meta_ed && ($meta_ad != 'yes')) {
        $clean_et_h = date("h", $meta_ed);
        $clean_et_m = date("i", $meta_ed);
        $clean_et_a = date("a", $meta_ed);
    }

    // - security -
    echo '<input type="hidden" name="pprss-events-nonce" id="pprss-events-nonce" value="' .
    wp_create_nonce( 'pprss-events-nonce' ) . '" />';

    // - output -
    ?>
    <div class="pprss-meta">
        <ul>
          <li><label>Start Date</label> <input name="pprss_event_startdate" class="pprss-event-date" value="<?php echo $clean_sd; ?>" size="14" /></li>
          <li><label>Start Time</label> <input name="pprss_event_starttime_h" value="<?php echo $clean_st_h; ?>" size="2" maxlength="2" />:<input name="pprss_event_starttime_m" value="<?php echo $clean_st_m; ?>" size="2" maxlength="2" />
            <select name="pprss_event_starttime_a">
              <option value=""></option>
              <option value="am" <?php if ($clean_st_a == 'am') print 'selected="selected"'; ?> >am</option>
              <option value="pm" <?php if ($clean_st_a == 'pm') print 'selected="selected"'; ?> >pm</option>
            </select>
          </li>
          <li>
            <input type="hidden" name="pprss_event_allday" value="no"  />
            <input type="checkbox" name="pprss_event_allday" value="yes" <?php if ($meta_ad == 'yes') print 'checked="checked" '?> > <label>All Day (no time display)</label>
          </li>
          <li><label>End Date</label> <input name="pprss_event_enddate" class="pprss-event-date" value="<?php echo $clean_ed; ?>" size="14" /></li>
          <li><label>End Time</label> <input name="pprss_event_endtime_h" value="<?php echo $clean_et_h; ?>" size="2" maxlength="2" />:<input name="pprss_event_endtime_m" value="<?php echo $clean_et_m; ?>" size="2" maxlength="2" />
            <select name="pprss_event_endtime_a">
              <option value=""></option>
              <option value="am" <?php if ($clean_et_a == 'am') print 'selected="selected"'; ?> >am</option>
              <option value="pm" <?php if ($clean_et_a == 'pm') print 'selected="selected"'; ?> >pm</option>
            </select>
          </li>
          <li>
            <input type="checkbox" name="pprss_event_hidedate" value="yes" <?php if ($meta_hidedate == 'yes') print 'checked="checked" '?> > <label>Hide Date and Time</label>
          </li>
        </ul>
    </div>
    <?php
}

// save Events data
add_action ('save_post', 'save_pprss_event');

function save_pprss_event(){
    global $post;

    // - still require nonce
    if ( !wp_verify_nonce( $_POST['pprss-events-nonce'], 'pprss-events-nonce' )) {
        return $post->ID;
    }

    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;

    // - convert back to unix & update post
    if(!isset($_POST["pprss_event_startdate"])):
    return $post;
    endif;

    update_post_meta($post->ID, "pprss_event_hidedate", $_POST["pprss_event_hidedate"] );

    if ( ($_POST["pprss_event_starttime_h"] != '') && ($_POST["pprss_event_starttime_m"] != '') && ($_POST["pprss_event_allday"] != 'yes') ) {
        $updatestartd = strtotime ( $_POST["pprss_event_startdate"] . ' ' . $_POST["pprss_event_starttime_h"] . ':' .  $_POST["pprss_event_starttime_m"] . ' ' . $_POST["pprss_event_starttime_a"]  );
    }
    else {
        $updatestartd = strtotime ( $_POST["pprss_event_startdate"] . ' 11:45 pm' );
    }
    update_post_meta($post->ID, "pprss_event_startdate", $updatestartd );

    if(!isset($_POST["pprss_event_enddate"])):
    return $post;
    endif;

    if ( ($_POST["pprss_event_endtime_h"] != '') && ($_POST["pprss_event_endtime_m"] != '') && ($_POST["pprss_event_allday"] != 'yes') ) {
        $updateendd = strtotime ( $_POST["pprss_event_enddate"] . ' ' . $_POST["pprss_event_endtime_h"] . ':' .  $_POST["pprss_event_endtime_m"] . ' ' . $_POST["pprss_event_endtime_a"]  );
    }
    else { $updateendd = strtotime ( $_POST["pprss_event_enddate"] . ' 11:45 pm' );
    }
    update_post_meta($post->ID, "pprss_event_enddate", $updateendd );

    if(!isset($_POST["pprss_event_allday"])):
    return $post;
    endif;
    if ( $_POST["pprss_event_allday"] == 'yes' ) { $updateallday = 'yes'; }
    else {  $updateallday = 'no'; }
    update_post_meta($post->ID, "pprss_event_allday", $updateallday );

}

// Events datepicker UI
function pprss_event_styles() {
    global $post_type;
    if( 'pprss_event' != $post_type )
        return;
    wp_enqueue_style('ui-datepicker', get_bloginfo('template_url') . '/events/css/jquery-ui-1.8.9.custom.css');
}
add_action( 'admin_print_styles-post.php', 'pprss_event_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'pprss_event_styles', 1000 );

function pprss_event_functions_css() {
        wp_enqueue_style('events-functions-css', get_bloginfo('template_directory') . '/events/css/pprss-event-functions.css');
}
add_action('admin_init', 'pprss_event_functions_css');

function pprss_event_scripts() {
    global $post_type;
    if( 'pprss_event' != $post_type )
        return;
//    wp_deregister_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui', get_bloginfo('template_url') . '/events/js/jquery-ui-1.8.9.custom.min.js', array('jquery'));
    wp_enqueue_script('ui-datepicker', get_bloginfo('template_url') . '/events/js/jquery.ui.datepicker.js');
    wp_enqueue_script('custom_script', get_bloginfo('template_url').'/events/js/pubforce-admin.js', array('jquery'));
}
add_action( 'admin_print_scripts-post.php', 'pprss_event_scripts', 1000 );
add_action( 'admin_print_scripts-post-new.php', 'pprss_event_scripts', 1000 );

// get events loop upcoming & past
function get_upcoming_events() {
    // hide events that are older than 6am today
    $today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );

    // query
    global $wpdb;
    $querystr = "
        SELECT *
        FROM $wpdb->posts wposts, $wpdb->postmeta metastart, $wpdb->postmeta metaend
        WHERE (wposts.ID = metastart.post_id AND wposts.ID = metaend.post_id)
        AND (metaend.meta_key = 'pprss_event_enddate' AND metaend.meta_value > $today6am )
        /* AND metastart.meta_key = 'pprss_event_enddate' */
        /* ^ dunno why this was set to end instead of start */
        AND metastart.meta_key = 'pprss_event_startdate'
        AND wposts.post_type = 'pprss_event'
        AND wposts.post_status = 'publish'
        ORDER BY convert(metastart.meta_value, signed) ASC LIMIT 500
        ";

    return $wpdb->get_results($querystr, OBJECT);
}
function get_past_events() {
    // hide events that are older than 6am today
    $today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );

    // query
    global $wpdb;
    $querystr = "
        SELECT *
        FROM $wpdb->posts wposts, $wpdb->postmeta metastart, $wpdb->postmeta metaend
        WHERE (wposts.ID = metastart.post_id AND wposts.ID = metaend.post_id)
        AND (metaend.meta_key = 'pprss_event_enddate' AND metaend.meta_value < $today6am )
        /* AND metastart.meta_key = 'pprss_event_enddate' */
        /* ^ dunno why this was set to end instead of start */
        AND metastart.meta_key = 'pprss_event_startdate'
        AND wposts.post_type = 'pprss_event'
        AND wposts.post_status = 'publish'
        ORDER BY convert(metastart.meta_value, signed) DESC LIMIT 500
        ";

    return $wpdb->get_results($querystr, OBJECT);
}

add_action( 'init', 'create_event_taxonomies', 0 );
function create_event_taxonomies()
{
  $labels = array(
    'name' => _x( 'Event Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Event Tag', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Event Tags' ),
    'popular_items' => __( 'Popular Event Tags' ),
    'all_items' => __( 'All Event Tags' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Event Tag' ),
    'update_item' => __( 'Update Event Tag' ),
    'add_new_item' => __( 'Add New Event Tag' ),
    'new_item_name' => __( 'New Event Tag' ),
    'separate_items_with_commas' => __( 'Separate tags with commas' ),
    'add_or_remove_items' => __( 'Add or remove tags' ),
    'choose_from_most_used' => __( 'Choose from the most used tags' ),
    'menu_name' => __( 'Event Tags' )
  );

  register_taxonomy('pprss_event_tag','pprss_event',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'writer' )
  ));
}
/* ----------------------------------------------------------- end CPT: Event */


/*
 * Custom Post Type: FAQ
 */
add_action('init', 'add_cpt_faq');
function add_cpt_faq()
{
  $labels = array(
    'name' => _x('FAQs', 'post type general name'),
    'singular_name' => _x('FAQ', 'post type singular name'),
    'add_new' => _x('Add New', 'pprss_faq'),
    'add_new_item' => __('Add New FAQ'),
    'edit_item' => __('Edit FAQ'),
    'new_item' => __('New FAQ'),
    'view_item' => __('View FAQ'),
    'search_items' => __('Search FAQ'),
    'not_found' =>  __('No FAQs found'),
    'not_found_in_trash' => __('No FAQs found in Trash'),
    'parent_item_colon' => '',
    'menu_name' => 'FAQs'
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array("slug" => "faq"),
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','page-attributes')
  );
  register_post_type('pprss_faq',$args);
}

// filter the "Enter title here" text
function pprss_faq_enter_title_here( $title ) {
    $screen = get_current_screen();
    if ( 'pprss_faq' == $screen->post_type ) {
        $title = 'Enter question here';
    }
    return $title;
}
add_filter( 'enter_title_here', 'pprss_faq_enter_title_here' );
/* ----------------------------------------------------------- end CPT: FAQ */


/*
 * Custom Post Type Icons
 */
add_action( 'admin_head', 'cpt_icons' );
function cpt_icons() {
    ?>
    <style type="text/css" media="screen">
      #menu-posts-pprss_event .wp-menu-image {
        background: url(<?php bloginfo('template_url') ?>/cpt-icons/calendar-task.png) no-repeat 6px -17px !important;
      }
      #menu-posts-pprss_faq .wp-menu-image {
        background: url(<?php bloginfo('template_url') ?>/cpt-icons/question.png) no-repeat 6px -17px !important;
      }
      #menu-posts-pprss_event:hover .wp-menu-image, #menu-posts-pprss_event.wp-has-current-submenu .wp-menu-image,
      #menu-posts-pprss_faq:hover .wp-menu-image, #menu-posts-pprss_faq.wp-has-current-submenu .wp-menu-image {
        background-position:6px 7px!important;
      }
    </style>
<?php }


/*
 * Map Page Template Meta Box
 */
function add_map_opt_metabox() {
  $post_id = in_array('post', $_GET) ? $_GET['post'] : (in_array('post_ID', $_POST)) ? $_POST['post_ID'] : false;
  $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
  if ($template_file == 'page-map.php') {
    add_meta_box( 'map_opt-metabox', __( 'Map Template Options' ), 'map_opt_metabox', 'page', 'side', 'default' );
  }
}
add_action( 'admin_init', 'add_map_opt_metabox' );

// display the metabox
function map_opt_metabox() {
  global $post;
  $map_show_posts = get_post_meta( $post->ID, 'map_show_posts', true );
  $map_show_posts_count = get_post_meta( $post->ID, 'map_show_posts_count', true );

  echo '<p><input type="checkbox" name="map_show_posts" id="map_show_posts" value="map_show_posts"';
  if( $map_show_posts ) {
    echo 'checked="checked"';
  };
  echo ' /> Include recent posts below page content.</p>';

  echo 'Show <input type="text" class="small-text" value="';
  if( $map_show_posts_count ) {
    echo $map_show_posts_count;
  };
  echo '" id="map_show_posts_count" name="map_show_posts_count" /> posts.';
}

// process & save the fields
function save_map_opt_metabox( $post_id ) {
  global $post;
  if( $_POST ) {
    update_post_meta( $post->ID, 'map_show_posts', $_POST['map_show_posts'] );
    update_post_meta( $post->ID, 'map_show_posts_count', $_POST['map_show_posts_count'] );
  }
}
add_action( 'save_post', 'save_map_opt_metabox' );



/*
 * make is_plugin_active work within templates
 */
function pprss_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}


/*
 * Custom Post Type: Slide
 */
add_action('init', 'add_cpt_slide');
function add_cpt_slide()
{
  $labels = array(
    'name' => _x('Slides', 'post type general name'),
    'singular_name' => _x('Slide', 'post type singular name'),
    'add_new' => _x('Add New', 'pprss_slide'),
    'add_new_item' => __('Add New Slide'),
    'edit_item' => __('Edit Slide'),
    'new_item' => __('New Slide'),
    'view_item' => __('View Slide'),
    'search_items' => __('Search Slides'),
    'not_found' =>  __('No Slides found'),
    'not_found_in_trash' => __('No Slides found in Trash'),
    'parent_item_colon' => '',
    'menu_name' => 'Slides'
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array("slug" => "slide"),
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','thumbnail','comments')
  );
  register_post_type('pprss_slide',$args);
}

add_action( 'init', 'create_slide_taxonomies', 0 );
function create_slide_taxonomies()
{
  $labels = array(
    'name' => _x( 'Slide Locations', 'taxonomy general name' ),
    'singular_name' => _x( 'Slide Location', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Slide Locations' ),
    'popular_items' => __( 'Popular Slide Locations' ),
    'all_items' => __( 'All Slide Locations' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Slide Location' ),
    'update_item' => __( 'Update Slide Location' ),
    'add_new_item' => __( 'Add New Slide Location' ),
    'new_item_name' => __( 'New Slide Location' ),
    'separate_items_with_commas' => __( 'Separate locations with commas' ),
    'add_or_remove_items' => __( 'Add or remove locations' ),
    'choose_from_most_used' => __( 'Choose from the most used locations' ),
    'menu_name' => __( 'Slide Locations' )
  );

  register_taxonomy('pprss_slide_location','pprss_slide',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'location' )
  ));

  $labels = array(
    'name' => _x( 'Slide Change Types', 'taxonomy general name' ),
    'singular_name' => _x( 'Slide Change Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Slide Change Types' ),
    'popular_items' => __( 'Popular Slide Change Types' ),
    'all_items' => __( 'All Slide Change Types' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Slide Change Type' ),
    'update_item' => __( 'Update Slide Change Type' ),
    'add_new_item' => __( 'Add New Slide Change Type' ),
    'new_item_name' => __( 'New Slide Change Type' ),
    'separate_items_with_commas' => __( 'Separate change types with commas' ),
    'add_or_remove_items' => __( 'Add or remove change types' ),
    'choose_from_most_used' => __( 'Choose from the most used change types' ),
    'menu_name' => __( 'Slide Change Types' )
  );

  register_taxonomy('pprss_slide_change_type','pprss_slide',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'change-type' )
  ));
}

function pprss_slide_custom_columns($column) {
    global $post;
    $custom = get_post_custom();
    switch ($column){
    case "pprss_col_slide_id":
      echo $post->ID;
      break;
    case "pprss_col_featured_image":
      echo get_the_post_thumbnail($post->ID, array(132,132));
      break;
    case "pprss_col_slide_locations":
      echo get_the_term_list($post->ID, 'pprss_slide_location','',', ','');
      break;
    case "pprss_col_slide_change_types":
      echo get_the_term_list($post->ID, 'pprss_slide_change_type','',', ','');
      break;
    }
}
add_action ("manage_posts_custom_column", "pprss_slide_custom_columns");

function pprss_slide_edit_columns($columns) {
    $extracolumns = array(
                          "cb" => $columns['cb'],
                          "pprss_col_slide_id" => "ID",
                          "pprss_col_featured_image" => "Image",
                          "title" => $columns['title'],
                          "pprss_col_slide_locations" => "Locations",
                          "pprss_col_slide_change_types" => "Change Types",
                          "comments" => $columns['comments'],
                          "date" => $columns['date']
                          );
    return $extracolumns;
}
add_filter ("manage_edit-pprss_slide_columns", "pprss_slide_edit_columns");

add_filter( 'admin_post_thumbnail_html', 'pprssslides_featured_txt_change', 9999, 1 );
function pprssslides_featured_txt_change( $content ) {
  if ( 'pprss_slide' == $GLOBALS['post_type'] ) {
    return str_replace('featured image', 'image', $content );
  }
  return $content;
}

add_filter( 'gettext', 'pprssslides_use_featured', 9999, 4 );
add_filter( 'ngettext', 'pprssslides_use_featured', 9999, 4 );
function pprssslides_use_featured( $translation, $text, $domain ) {
  if ( 'pprss_slide' == get_post_type() ) {
    $translations = &get_translations_for_domain( $domain );
    if ( $text == 'Use as featured image' ) {
      return $translations->translate( 'Use as image' );
    } elseif ( $text == 'Featured Image' ) {
      return $translations->translate( 'Image' );
    }
  }
  return $translation;
}

add_shortcode('make-slideshow', 'make_slideshow_handler');
function make_slideshow_handler($atts) {
  extract(shortcode_atts( array('slides'=>''), $atts));

  if (!$slides)
    return;
  $slides = explode(',', $slides);

  $out = '<div id="slides-container"><div id="slides-nav-wrapper"><a href="#" id="slides-next">&#9658;</a><a href="#" id="slides-prev">&#9668;</a><ul id="slides-nav">';
  $out2 = '';
  foreach($slides as $k=>$slide) {
    if (!has_post_thumbnail($slide))
      continue;
    $slidepost = get_post($slide);
    $out .= '<li class="' . $slide . '"><a href="#' . $slide . '">&#9679;</a></li>';
    $imgurl = wp_get_attachment_image_src( get_post_thumbnail_id( $slidepost->ID ), 'single-post-thumbnail' );
    $imgurl = $imgurl[0];
    $sharelink = get_permalink();
    //    var_dump($slidepost);

    $out2 .= '<li id="' . $slide . '"><a id="slide' . $k . '" rel="cbslideshow" href="' . $imgurl . '" class="colorbox" title="<h3>' . $slidepost->post_title . '</h3><p>' . $slidepost->post_content . '</p>"><img src="' . $imgurl . '" /></a>';

    $out2 .= '<div class="after-img"><span class="slides-share"><a  href="" class="slides-permalink pngfix" title="Link to this map.">Link</a><span class="slides-share-link">';
    $out2 .= '<a href="#" class="slides-share-link-close pngfix">X</a>';
    $out2 .= '<label>Copy &amp; paste this link to share:</label>';
    $out2 .= '<input class="slides-share-link-input pngfix" type="text" readonly="readonly" value="' . $sharelink . '#' . $slide . '" />';
    $out2 .= '</span></span>';

    $out2 .= '<a class="fullscreen pngfix" href="#" onclick="jQuery(\'#slide' . $k . '\').click();">Full Screen</a>';
    $out2 .= '<h3 class="slide-title">' . $slidepost->post_title . '</h3>';
    $out2 .= '<p class="slide-caption">' . $slidepost->post_content . '<br><br>';
    $terms = get_the_terms($slide, 'pprss_slide_change_type');
    if ( $terms && ! is_wp_error( $terms ) ) {
      $terms_links = array();
      foreach ( $terms as $term ) {
        $terms_links[] = '<a href="' . get_bloginfo('url') . '/' . $term->slug . '">' . $term->name . '</a>';
      }
      $changes_out = join( " | ", $terms_links );
      if ($changes_out)
        $out2 .= 'Types of changes: ' . $changes_out . ' <br>';
    }
    $terms = get_the_terms($slide, 'pprss_slide_location');
    if ( $terms && ! is_wp_error( $terms ) ) {
      $terms_links = array();
      foreach ( $terms as $term ) {
        $terms_links[] = '<a href="' . get_bloginfo('url') . '/' . $term->slug . '">' . $term->name . '</a>';
      }
      $location_out = join( " | ", $terms_links );
      if ($location_out)
        $out2 .= 'Location: ' . $location_out. '<br>';
    }
    $out2 .= '</p></div></li>';
  }
  $out .= '</ul></div><ul id="slides">';
  $out .= $out2;
  $out .= '</ul></div>';

  return $out;
}

function enqueue_theme_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'colorbox', get_bloginfo('stylesheet_directory') . '/colorbox/jquery.colorbox.js', 'jquery' );
}

/* =jQuery and other JavaScript stuffs
-------------------------------------------------------------- */
if ( !is_admin() ) { // instruction to only load if it is not the admin area
    add_action( 'wp_enqueue_scripts', 'enqueue_theme_scripts' );
}

/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function planningpress_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
                case '' :
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <article id="comment-<?php comment_ID(); ?>" class="comment" role="article">
                        <header>
                                <div class="comment-author vcard">
                                        <?php echo get_avatar( $comment, 40 ); ?>
                                        <?php printf( __( '%s <span class="says">says:</span>', 'planningpress' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
                                </div><!-- .comment-author .vcard -->
                                <?php if ( $comment->comment_approved == '0' ) : ?>
                                        <em><?php _e( 'Your comment is awaiting moderation.', 'planningpress' ); ?></em>
                                        <br />
                                <?php endif; ?>

                                <div class="comment-meta commentmetadata">
                                        <a class="comment-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
                                        <?php
                                                /* translators: 1: date, 2: time */
                                                printf( __( '%1$s at %2$s', 'planningpress' ), get_comment_date(),  get_comment_time() ); ?>
                                        </time></a>
                                        <?php edit_comment_link( __( '(Edit)', 'planningpress' ), ' ' );
                                        ?>
                                </div><!-- .comment-meta .commentmetadata -->
                        </header>

                        <div class="comment-body"><?php comment_text(); ?></div>

                        <div class="reply">
                                <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </div><!-- .reply -->
                </article><!-- #comment-##  -->

        <?php
                        break;
                case 'pingback'  :
                case 'trackback' :
        ?>
        <li class="post pingback">
                <p><?php _e( 'Pingback:', 'planningpress' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'planningpress'), ' ' ); ?></p>
        <?php
                        break;
        endswitch;
}


if ( !function_exists('include_wordpress_template')) {
  function include_wordpress_template($t) {
    global $wp_query;
    if ($wp_query->is_404) {
        $wp_query->is_404 = false;
        $wp_query->is_archive = true;
    }
    header("HTTP/1.1 200 OK");
    include($t);
  }
}

function my_template() {
  if (strstr($_SERVER['REQUEST_URI'], 'get_comments')) {
    include_wordpress_template(TEMPLATEPATH . '/ajax-comments.php');
    exit;
  }
}

add_action('template_redirect', 'my_template');
?>