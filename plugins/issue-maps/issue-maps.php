<?php
/*
Plugin Name: Issue Maps
Plugin URI:
Description: This plugin allows you to turn a Wordpress page into an issue map.  It allows users to place issues on a Google Map.
Author: Chris Abraham
Version: 0.1
Author URI: http://cjyabraham.com
License: GPL2
*/
/*  Copyright 2011  Chris Abraham  (email : cjyabraham@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


new Issue_Maps;

class Issue_Maps {
  public $issue_table, $issues, $icons;

        function Issue_Maps()
        {
          global $wpdb;
          $this->issue_table = $wpdb->prefix . "issue_map_points";
          $this->icons = Array(
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF6600|FF6600",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0099|FF0099",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|6600FF|6600FF",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|0099FF|0099FF",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|00FF66|00FF66",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|99FF00|99FF00",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|CC9900|CC9900",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|CC0033|CC0033",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9900CC|9900CC",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|0033CC|0033CC",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|00CC99|00CC99",
                               "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|339900|339900"
                               );
          add_action( 'init', array( &$this, 'init' ) );
          add_action( 'admin_init', array( &$this, 'admin_init' ) );
          register_activation_hook(__FILE__,array( &$this, 'setup_tables' ));
        }

        function setup_tables() {
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

          $sql = "CREATE TABLE " . $this->issue_table . " (
                    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    lat double NOT NULL,
                    lon double NOT NULL,
                    post_id bigint(20) unsigned NOT NULL,
                    issue_map_point_type_id bigint(20) unsigned NOT NULL,
                    posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    hide binary NOT NULL DEFAULT FALSE,
                    UNIQUE KEY id (id)
                  );";
          dbDelta($sql);
        }

        function init()
        {
          add_action('template_redirect', array(&$this, 'redirect'));
          add_shortcode( 'issue-map', array( &$this, 'issue_map_shortcode' ));
        }

        function admin_init()
        {
          add_action( 'load-post.php', array( &$this, 'post_meta_boxes_setup' ) );

        }

        function post_meta_boxes_setup() {
          add_action( 'add_meta_boxes', array( &$this, 'add_post_meta_boxes' ) );
        }

        function add_post_meta_boxes() {
          global $wpdb, $post;
          $post_id = $post->ID;

          if ($_GET['point'])
            $this->hide_point();

          $issues = $wpdb->get_results("select * from $this->issue_table where post_id=$post_id;");
          $this->issues = $issues;
          if (!$this->issues)
            return;

          add_meta_box(
                       'issue-map-post-class',// Unique ID
                       esc_html__( 'Issue Map Points', 'example' ),// Title
                       array( &$this, 'issue_map_meta_box' ),// Callback function
                       'page',// Admin page (or post type)
                       'normal',// Context
                       'default'// Priority
                       );
        }

        function issue_map_meta_box($object, $box) {
          global $post;
          $post_id = $post->ID;

          $pattern = get_shortcode_regex();
          preg_match('/'.$pattern.'/s', $post->post_content, $matches);
          if (is_array($matches) && $matches[2] == 'issue-map') {
            $types = explode("=", $matches[3]);
            $types = str_replace('"', '', $types[1]);
            $types = trim($types);
            $types = explode(',', $types);
          }

          ?>
          <table class="display" id="issue-map-points">
             <thead>
             <tr>
             <th>Issue ID</th>
             <th>Lat</th>
             <th>Lon</th>
             <th>Point Type</th>
             <th>Date</th>
             <th>Hide</th>
             </tr>
             </thead>
             <tbody>

             <?php
             foreach($this->issues as $point) {
            ?>
            <tr>
            <td><?php echo $point->id?></td>
            <td><?php echo $point->lat?></td>
            <td><?php echo $point->lon?></td>
            <td><?php echo $types[$point->issue_map_point_type_id]?></td>
            <td><?php echo $point->posted_date?></td>
            <td><a href="<?php echo admin_url('post.php?post=' . $post_id  . '&action=edit&point=' . $point->id . '&hide=' . !$point->hide);?>"><?php echo (!$point->hide) ? 'Hide' : 'Unhide' ?></a></td>
            </tr>
            <?php
          }
          ?>
          </tbody> </table>
              <style type="text/css" title="currentStyle">
              @import "<?php bloginfo('url')?>/wp-content/plugins/issue-maps/datatable.css";
          </style>
              <script type="text/javascript" language="javascript" src="<?php bloginfo('url')?>/wp-content/plugins/issue-maps/jquery.dataTables.js"></script>
              <script type="text/javascript" charset="utf-8">
              jQuery(document).ready(function() {
                  jQuery('#issue-map-points').dataTable( {
                      "aLengthMenu": [[50, 100, -1], [50, 100, "All"]],
                        "iDisplayLength": 50
                        } );
                } );
          </script>
              <?php
        }

        function hide_point() {
          global $wpdb;

          $point_id = $_GET['point'];
          $hide = ($_GET['hide']) ? '1' : '0';
          $query = "UPDATE $this->issue_table set hide=$hide where id=$point_id;";
          $result = mysql_query($query);
          if(!$result)
            die('error|query '.$query);
        }


        function redirect() {
          if (preg_match(",/add_issue_point(/?)$," ,$_SERVER["REQUEST_URI"])) {
              $this->add_issue_point();
              exit;
          } else if (preg_match(",/get_issue_map_points,", $_SERVER['REQUEST_URI'])) {
            header("HTTP/1.0 200 OK");
            $this->get_points();
            exit;
          }
        }

        function add_issue_point() {
          global $wpdb;
          $wpdb->insert($this->issue_table, array(
                                           'lat' => $_POST['lat'],
                                           'lon' => $_POST['lon'],
                                           'post_id' => $_POST['page_id'],
                                           'issue_map_point_type_id' => $_POST['issue_map_point_type_id']
                                           ),
                        array('%f', '%f', '%f', '%f'));

          $new_id = $wpdb->insert_id;
          echo $new_id;
        }

        function get_points() {
          global $wpdb;
          $post_id = $_GET['post_id'];
          $points = $wpdb->get_results("select * from $this->issue_table where post_id=$post_id and hide=FALSE;");
          echo json_encode($points);
        }

        function get_legend($issuetypes) {
          $out = '<div id="issue-map-legend"><h2>Legend</h2>';
          $types = explode(',', $issuetypes);
          foreach ($types as $key => $it) {
            $out .= '<div class="issue-type">';
            $out .= '<img class="issue-type-icon" src="' . $this->icons[$key] . '" />';
            $out .= $it;
            $out .= '</div>';
          }
          $out .= '</div>';

          return $out;
        }

        // [issue-map issue-types="double parking, illegal parking, blocking lane"]
        function issue_map_shortcode( $atts ) {
          extract(shortcode_atts( array('issuetypes' => 'something',
                                        'lat' => 40.71532205,
                                        'lon' => -73.9912891,
                                        'zoom' => 16,
                                        'maxzoom' => 17,
                                        'minzoom' => 12,
                                        'northbound' => '0',
                                        'southbound' => '0',
                                        'westbound' => '0',
                                        'eastbound' => '0'
                                        ), $atts ));

          $out = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.1&sensor=false"></script>';
          $out .= '<div id="mbMap" style="height: 350px;"></div>';
          $out .= $this->get_legend($issuetypes);
          $out .= '<script type="text/javascript">';
          $out .= 'var page_id = ' . get_the_ID() . ';';
          $out .= 'var add_url = "' . get_bloginfo('url') . '/add_issue_point";';
          $out .= 'var get_url = "' . get_bloginfo('url') . '/get_issue_map_points?post_id=' . get_the_ID() . '";';
          $out .= 'var issuetypes = \'' . json_encode(explode(',', $issuetypes)) . '\';';
          $out .= 'var iconsjson = \'' . json_encode($this->icons) . '\';';
          $out .= 'var zoom = ' . $zoom . ';';
          $out .= 'var lat = ' . $lat . ';';
          $out .= 'var lon = ' . $lon . ';';
          $out .= 'var northbound = ' . $northbound . ';';
          $out .= 'var southbound = ' . $southbound . ';';
          $out .= 'var eastbound = ' . $eastbound . ';';
          $out .= 'var westbound = ' . $westbound . ';';
          $out .= 'var maxzoom = ' . $maxzoom . ';';
          $out .= 'var minzoom = ' . $minzoom . ';';

          $out .= <<<HD

jQuery(document).ready(function() {
        mapInit();
});

var map = null;
var marker = null;
var iconMap = new Array();
var icons = jQuery.parseJSON(iconsjson);
var iconNeutral = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|EEE|EEE";

var infowindow = new google.maps.InfoWindow({
         size: new google.maps.Size(150,150)
});


function createMarker(latlng, name, html) {
  var contentString = html;

  var marker = new google.maps.Marker({
      position: latlng,
      map: map,
      icon: iconNeutral
    });

  google.maps.event.addListener(marker, 'click', function() {
      infowindow.setContent(contentString);
      infowindow.open(map,marker);
    });

  google.maps.event.trigger(marker, 'click');
  return marker;
}

function mapOpt(key, i) {
  marker.setIcon(iconMap[key]);
  marker.saved = true;
  saveMarker(key);
}

function saveMarker(key) {
  infowindow.setContent("<em>Saving...</em>");
  postdata = {page_id: page_id, issue_map_point_type_id: key, lat: marker.getPosition().lat(), lon:marker.getPosition().lng()};

  jQuery.post(add_url, postdata, function(data) {
      //alert("Data Loaded: " + data);
    });
  setTimeout(function () { infowindow.close(); }, 1000);
}


function mapInit() {
  var mapOptions = {
                    zoom: zoom,
                    center: new google.maps.LatLng(lat, lon),
                    panControl: false,
                    zoomControl: true,
                    zoomControlOptions: {
                                         style: google.maps.ZoomControlStyle.SMALL
                                        },
                    scaleControl: false,
                    maxZoom: maxzoom,
                    minZoom: minzoom,
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                                           style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                                           },
                    mapTypeId: google.maps.MapTypeId.ROADMAP
  }

  map = new google.maps.Map(document.getElementById("mbMap"), mapOptions);

  google.maps.event.addListener(infowindow, 'closeclick', function() {
      marker.setMap(null);
    });

  var tags = jQuery.parseJSON(issuetypes);
  var popupContent = "Choose issue type:<br>";
  var i=0;
  jQuery.each(tags, function(key, value) {
      iconMap[key] = icons[i];
      popupContent += "<a onclick='mapOpt(" + key + ", " + i + ")' style='background: #EEF;'>" + value + "</a><br/>";
      i++;
    });

  google.maps.event.addListener(map, 'click', function(event) {
      //call function to create marker
      if (marker && !marker.saved) {
        marker.setMap(null);
        marker = null;
      }
      marker = createMarker(event.latLng, "name", popupContent);
    });


  jQuery.getJSON(get_url, function(data) {
    var interval_id = window.setInterval(function(){
      if (map != undefined){
        window.clearInterval(interval_id);
        markers = createMarkers(data);
      }
    }, 100);
  });

  // don't pan outside this area
  if (southbound != 0) {
    var allowedBounds = new google.maps.LatLngBounds(
                                                     new google.maps.LatLng(southbound, westbound),
                                                     new google.maps.LatLng(northbound, eastbound));

    google.maps.event.addListener(map, 'dragend', function() {
        if (allowedBounds.contains(map.getCenter())) return;

        // Out of bounds - Move the map back within the bounds
        var c = map.getCenter(),
          x = c.lng(),
          y = c.lat(),
          maxX = allowedBounds.getNorthEast().lng(),
          maxY = allowedBounds.getNorthEast().lat(),
          minX = allowedBounds.getSouthWest().lng(),
          minY = allowedBounds.getSouthWest().lat();

        if (x < minX) x = minX;
        if (x > maxX) x = maxX;
        if (y < minY) y = minY;
        if (y > maxY) y = maxY;

        map.setCenter(new google.maps.LatLng(y, x));
      });
  }

}

function createMarkers (places) {
  return( jQuery.map(places, function(place){
        marker_icon = iconMap[place.issue_map_point_type_id];
        var m = new google.maps.Marker({
          position: new google.maps.LatLng(place.lat, place.lon),
              map: map,
              draggable: false,
              icon: marker_icon
              });

        return {id: place.id, marker: m};
  }));
}

function deleteMarker() {
  marker.setMap(null);
}
</script>
HD;

          return $out;
        }

}





?>
