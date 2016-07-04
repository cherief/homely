<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
  <head>
    <title>homely</title>
    <meta charset="utf-8">
    <!-- JQuery -->
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="script.js"></script>
   
    <!-- Leaflet -->
    <script>L_PREFER_CANVAS = true;</script>
    <link rel="stylesheet" href="style-leaflet.css" type="text/css">
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
    <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>

    <script src="leaflet.ajax.js"></script>

    <!-- JQuery UI -->


<!-- <link href=
    "http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel=
    "stylesheet">
   // <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script> -->


<link href=
    "http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" rel=
    "stylesheet">
   <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

  </head>



  <body>

      <!-- Add map using leaflet -->
      <div id="map" class="map"></div>


      <!-- HEADER -->
  <div id="header" class="header">

        <span> <img src="homely-icon.jpg" height="26px"/></span>


        <span id="homely">HOMELY</span>



<!--         <?php 
          $api = file_get_contents("api_server");
          echo '<span>'.$api.'</span>'; 
        ?> -->

<!--           <span class="dropdown">
            <button class="dropbtn">Export</button>
            <div class="dropdown-content">
                <a href="#">HTML</a>
                <a href="#">CSV</a>
                <a href="#">GEOJSON</a>
            </div>
          </span> -->

          <button class="nav" onclick="openNav()">Filter</button>

  </div>

          <div id="mySidenav" class="sidenav">

            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&#215</a>



            <button class="updatebtn" onclick="updateLayers()">Update</button>


            <p>
            <span class="filtertype">Listing price </span><br>
            <input type="text" id="LPvalue" class="slidervalue" readonly><br>
            <div id="LPslider" class="slider"></div>
            <input id="auctionCB" type="checkbox" checked>
            <label for="auctionCB" class="side-label">Include auctions</label>

            <span class="filtertype">Property Type</span><br>
            <input id="apartmentCB" type="checkbox" checked>
            <label for="apartmentCB" class="side-label">Apartment</label>
            <input id="houseCB" type="checkbox" checked>
            <label for="houseCB" class="side-label">House</label>
            <input id="townhouseCB" type="checkbox" checked>
            <label for="townhouseCB" class="side-label">Townhouse</label>

            <span class="filtertype">Bedrooms</span><br>
            <input id="bed1CB" type="checkbox" checked>
            <label for="bed1CB" class="side-label">1</label>
            <input id="bed2CB" type="checkbox" checked>
            <label for="bed2CB" class="side-label">2</label>
            <input id="bed3CB" type="checkbox" checked>
            <label for="bed3CB" class="side-label">3</label>
            <input id="bed4CB" type="checkbox" checked>
            <label for="bed4CB" class="side-label">4</label>
            <input id="bed5CB" type="checkbox" checked>
            <label for="bed5CB" class="side-label">&ge; 5</label>

            <span class="filtertype">Bathrooms</span><br>
            <input id="bath1CB" type="checkbox" checked>
            <label for="bath1CB" class="side-label">1</label>
            <input id="bath2CB" type="checkbox" checked>
            <label for="bath2CB" class="side-label">2</label>
            <input id="bath3CB" type="checkbox" checked>
            <label for="bath3CB" class="side-label">&ge; 3</label>

            <span class="filtertype">Garages</span><br>
            <input id="garage1CB" type="checkbox" checked>
            <label for="garage1CB" class="side-label">1</label>
            <input id="garage2CB" type="checkbox" checked>
            <label for="garage2CB" class="side-label">&ge; 2</label>


            <!-- Travel time -->
            <div class="filtertype">Travel Time </div>
            <input type="text" id="TTvalue" class="slidervalue" readonly><br>
            <div id="TTslider" class="slider"></div>

            <!-- <div class="subfiltertype">Get address </div> -->
            <input type="text" id="TTaddress" value="Get address"><br>


            <p>
            <!-- Block size -->
            <span class="filtertype">Block Size </span><br>
            <input type="text" id="BSvalue" class="slidervalue" readonly><br>
            <div id="BSslider" class="slider"></div>

            <p>
            <span class="filtertype">UV </span><br>
            <input type="text" id="UVvalue" class="slidervalue" readonly><br>
            <div id="UVslider" class="slider"></div>

            <p>
            <span class="filtertype">EER </span><br>
            <input type="text" id="EERvalue" class="slidervalue" readonly><br>
            <div id="EERslider" class="slider"></div>



            <div class="spacerdiv"> </div>



        </div>



<script>

  $(function() {
    $( "#EERslider" ).slider({
      range: true,
      min: 0,
      max: 8,
      step: 1,
      values: [0,8],
      slide: function( event, ui ) {
        $( "#EERvalue" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
      }
    });
    $( "#EERvalue" ).val($( "#EERslider" ).slider( "values", 0 ) +
      " - " + $( "#EERslider" ).slider( "values", 1 ) );
// -------
    $( "#UVslider" ).slider({
      range: true,
      min: 100000,
      max: 1000000,
      step: 50000,
      values: [100000, 1000000],
      slide: function( event, ui ) {
        $( "#UVvalue" ).val( "$" + comma(ui.values[0]) + " - $" + comma(ui.values[1]));
      }
    });
    $( "#UVvalue" ).val("$" + comma($( "#UVslider" ).slider( "values", 0 )) +
      " - $" + comma($( "#UVslider" ).slider( "values", 1 ) ));
// --------
    $( "#LPslider" ).slider({
      range: true,
      min: 100000,
      max: 1000000,
      step: 50000,
      values: [100000,1000000],
      slide: function( event, ui ) {
        $( "#LPvalue" ).val("$" + comma(ui.values[ 0 ]) + " - $" + comma(ui.values[ 1 ]));
      }
    });
    $( "#LPvalue" ).val("$" + comma($( "#LPslider" ).slider( "values", 0 )) +
      " - $" + comma($( "#LPslider" ).slider( "values", 1 )));
// ----- Travel time ------
    $( "#TTslider" ).slider({
      range: false,
      max: 60,
      step: 5,
      values: [60],
      slide: function( event, ui ) {
        $( "#TTvalue" ).val("Less than " + ui.values[0] + " minutes");
      }
    });
    $( "#TTvalue" ).val("Less than " + comma($( "#TTslider" ).slider( "values",0)) +
      " minutes" ); 
// ----- Block size ------
    $( "#BSslider" ).slider({
      range: false,
      max: 1200,
      step: 100,
      values: [0],
      slide: function( event, ui ) {
        $( "#BSvalue" ).val("Greater than " + ui.values[0] + " sqm");
      }
    });
    $( "#BSvalue" ).val("Greater than " + comma($( "#BSslider" ).slider( "values",0)) +
      " sqm" ); 
  });



var map = L.map('map',{zoomControl:false}); // what does this do?


//add zoom control with your options
L.control.zoom({
     position:'bottomleft'
}).addTo(map);
  //var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  //var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
  //var osm = new L.TileLayer(osmUrl); //, {attribution: osmAttrib});   

  map.setView([-35.26162,149.145187],12); // start the map in Canberra, Australia
  //map.addLayer(osm); // add OSM layer to map


 // icons for markers
var houseIconOrange = L.icon({
    iconUrl: 'house-icon-orange.png',
    iconSize:     [25, 27], // size of the icon
    iconAnchor:   [12, 27], // point of the icon which will correspond to marker's location
    popupAnchor:  [1, -35] // point from which the popup should open relative to the iconAnchor
});

var houseIconRed = L.icon({
    iconUrl: 'house-icon-red.png',
    iconSize:     [25, 27], // size of the icon
    iconAnchor:   [12, 27], // point of the icon which will correspond to marker's location
    popupAnchor:  [1, -35] // point from which the popup should open relative to the iconAnchor
});

var schoolIcon = L.icon({
    iconUrl: 'school-icon.png',
    iconSize:     [20, 20], // size of the icon
    iconAnchor:   [10, 20], // point of the icon which will correspond to marker's location
    popupAnchor:  [1, -35] // point from which the popup should open relative to the iconAnchor
});



var addressLayer = $.getJSON("addresses.geojson");

addressLayer.then(function(data) {

        //var allAddresses = L.geoJson(data);

        // var house = L.geoJson(data, {
        //     filter: function(feature, layer) {
        //         return feature.properties.propertytype == "House";
        //     },
        //     pointToLayer: function(feature, latlng) {
        //         return L.marker(latlng, {
        //             icon: houseIconOrange
        //         })
        //     },
        //     onEachFeature: propertyPopup
        // });

        // var others = L.geoJson(data, {
        //     filter: function(feature, layer) {
        //         return feature.properties.propertytype != "House";
        //     },
        //     pointToLayer: function(feature, latlng) {
        //         return L.marker(latlng, {
        //             icon: houseIconRed
        //         })
        //     },
        //     onEachFeature: propertyPopup
        // });
       
        // map.fitBounds(allAddresses.getBounds(), {
        //     padding: [50, 50]
        // });

        // house.addTo(map)
        // others.addTo(map)

        var allAddresses = L.geoJson(data);

        var addresses = L.geoJson(data, {
            pointToLayer: function(feature, latlng) {
                return L.marker(latlng, {
                    icon: houseIconOrange
                })
            },
            onEachFeature: propertyPopup
        });

        map.fitBounds(allAddresses.getBounds(), {
            padding: [50, 50]
        });

        // add to map
        addresses.addTo(map)
        
    });

// var schoolLayer = $.getJSON("data/schools.geojson");
// console.log(schoolLayer);

// schoolLayer.then(function(data) {
//         var allschools = L.geoJson(data);

//         // THIS IS NEW
//         var schools = L.geoJson(data, {
//             filter: function(feature, layer) {
//                 return feature.properties.amenity == "bus stop";
//             },
//             pointToLayer: function(feature, latlng) {
//                 return L.marker(latlng, {
//                     icon: schoolIcon
//                 })
//             }//,
//             //onEachFeature: schoolPopup
//         });


       
//         map.fitBounds(allschools.getBounds(), {
//             padding: [50, 50]
//         });

//         // THIS IS NEW

//         // schools.addTo(map);

//     });

// var schoolPoints = L.geoJson.ajax("data/schools.geojson",{
//               pointToLayer: function(feature, latlng) {
//                 return L.marker(latlng, {
//                     icon: schoolIcon
//                 })
//               },
//               onEachFeature: schoolPopup
// }).addTo(map); // Adding the entire geoJson layer to the map.


// map.on("zoomend", function () {
//     if (map.getZoom() >= 15) {
//         map.addLayer(schoolPoints);
//     } else {
//         // Removing entire geoJson layer that contains the points.
//         map.removeLayer(schoolPoints);
//     }
// });

foo = 12;

if ([1,3,12].indexOf(foo) > -1) {

  console.log("yep");

}

</script>




  </body>
</html>






