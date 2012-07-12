      var map = new L.Map('map', {
            scrollWheelZoom: false
            });
      var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/03cdfe364cd44464ae4d126009e52117/997/256/{z}/{x}/{y}.png',
          cloudmadeAttrib = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
          cloudmade = new L.TileLayer(cloudmadeUrl, {
            maxZoom: 18,
            minZoom: 0
            });
      var london = new L.LatLng(51.505, -0.09);
      map.setView(london, 13).addLayer(cloudmade);
//      map.locateAndSetView(13).addLayer(cloudmade);

      var markerLocation = new L.LatLng(51.5, -0.09);
      var MyIcon = L.Icon.extend({
          iconUrl: '<?php echo get_template_directory_uri(); ?>/leaflet/images/marker-pink.png'
      });
      var icon = new MyIcon();
      var marker = new L.Marker(markerLocation, {icon: icon});
      map.addLayer(marker);

      marker.bindPopup("<b>OMG! Easy-to-work-with Popups!</b> <br />I hope they stay that way once I try to do something more advanced.").openPopup();

      // ui to make the map taller
      $('#map-container').append('<a href="#" id="map-sizer">&#9660;</a>');
      $('#map-sizer').click(function(){
          $('#map-container').toggleClass('tall');
          map.invalidateSize();
          $('#map-container.tall').find('#map-sizer').html('&#9650;');
          $('#map-container:not(.tall)').find('#map-sizer').html('&#9660;');
          return false;
      });
      // ui to make the map wider
      $('#map-container').append('<a href="#" id="map-widener">&#9658;</a>');
      $('#map-widener').click(function(){
          $('#map-container').toggleClass('wide');
          map.invalidateSize();
          $('#map-container.wide').find('#map-widener').html('&#9668;');
          $('#map-container:not(.wide)').find('#map-widener').html('&#9658;');
          $('#map-container.wide').prependTo('#main');
          $('#map-container:not(.wide)').prependTo('#primary');
          return false;
      });
