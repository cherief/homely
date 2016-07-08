// Formats numbers with commas
function comma(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

// Opens filter sidebar
function openNav() {
    document.getElementById("mySidenav").style.display = "block";
}

// Closes filter sidebar
function closeNav() {
    document.getElementById("mySidenav").style.display = "none";
}

// Popups for Leaflet markers
function propertyPopup(feature, layer) {
  layer.bindPopup("<b>" + feature.properties.name + "</b><br>" + feature.properties.price + "</b><br><b>Property Type</b> " + feature.properties.propertytype + "</b><br><b>Bedrooms</b> " + feature.properties.bedrooms);
}
 
function schoolPopup(feature, layer) {
  layer.bindPopup("<b>" + feature.properties.name + "</b>");
}
//--------------------------

// Updates layers with filter choices
function updateLayers() {

    map.eachLayer(function (layer) {
      map.removeLayer(layer);
    });


    addressLayer.then(function(data) {

        var allAddresses = L.geoJson(data)
        
        console.log(data);
          var house = L.geoJson(data, {
              filter: function(feature, layer) {
                  return feature.properties.propertytype == "House";
              },
              pointToLayer: function(feature, latlng) {
                  return L.marker(latlng, {
                      icon: houseIconOrange
                  })
              },
              onEachFeature: propertyPopup
          });

        var apartment = L.geoJson(data, {
            filter: function(feature, layer) {
                return feature.properties.propertytype == "Apartment";
            },
            pointToLayer: function(feature, latlng) {
                return L.marker(latlng, {
                    icon: houseIconOrange
                })
            },
            onEachFeature: propertyPopup
        });

        var townhouse = L.geoJson(data, {
            filter: function(feature, layer) {
                return feature.properties.propertytype == "Townhouse";
            },
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


        if (document.getElementById('houseCB').checked == true) {
          house.addTo(map)
        }
        if (document.getElementById('apartmentCB').checked == true) {
          apartment.addTo(map)
        }
        if (document.getElementById('townhouseCB').checked == true) {
          townhouse.addTo(map)
        }

        map.addLayer(osm);
    });

}
//------------------------------

