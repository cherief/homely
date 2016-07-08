

<html>
<head>
<title>
TEST SQL
</title>
<link rel="stylesheet" href="style-php.css" type="text/css">
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
    <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>

<script src='jquery.js'></script>

    <!-- // <script src="leaflet.ajax.js"></script> -->

</head>

    <body>


<div id='mapid'></div>

<?php

$servername = "mysql.kateandcat.com";   // eg. mysql.yourdomain.com (unique)
$username = "chefis4";   // the username specified when setting-up the database
$password = "cosmo6cat";   // the password specified when setting-up the database
$dbname = "kateandcat_homely";   // the database name chosen when setting-up the database (unique)

$mysqli = new mysqli($servername, $username, $password, $dbname);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


// FILTER VARIABLES
$listingprice_array = array(100000,1000000); // NEED TO GET FROM FILTER (min and max of listing price)
$auction = "yes"; // NEED TO GET FROM FILTER
$bedroms_array = array(3,4,2,5); // NEED TO GET FROM FILTER (can be one of 0,1,2,3,5 where 5 is any with 5 bedrooms or more)
$bathrooms_array = array(0,1,2); // NEED TO GET FROM FILTER (can be one of 0,1,2,3 where 3 is any with 3 bedrooms or more)
$garages_array = array(1,2); // can be one of 0,1,2 where 2 is any with 2 or more
$blocksize_array = array(0,1000); // min and max of block size
$UV_array = array(0,1000000); // min and max of UV
$EER_array = array(0,8); // min and max of EER

//------------------
// Filter listing price
//------------------
$minlistingprice = $listingprice_array[0];
$maxlistingprice = $listingprice_array[1];
$listingprice_query = "((listingprice > ".$minlistingprice." AND listingprice < ".$maxlistingprice.")";

if ($auction == "yes") {
    $listingprice_query .= " OR (auction != 'no'))";
} else {
    $listingprice_query .= " AND (auction = 'no'))";
}
//-----------------

//------------------
// Filter bedrooms
//------------------

$b_count = count($bedroms_array);
if ($b_count > 1 && !in_array(5,$bedroms_array)) {
     $bedrooms_query = "bedrooms IN (".join(",", array_values($bedroms_array)).")";
}
elseif ($b_count > 1 && in_array(5,$bedroms_array)) {
    $b_arr = array_diff($bedroms_array, array(5));
    $bedrooms_query = "(bedrooms IN (".join(",", array_values($b_arr)).") OR bedrooms >= 5)";
}
else {
    $bedrooms_query = "bedrooms = '".$bedroms_array[0]."' ";
}
$bedrooms_query = $mysqli->real_escape_string($bedrooms_query);
//-----------------

//------------------
// Filter bathrooms
//------------------
$b_count = count($bathrooms_array);
if ($b_count > 1 && !in_array(3,$bathrooms_array)) {
     $bathrooms_query = "bathrooms IN (".join(",", array_values($bathrooms_array)).")";
}
elseif ($b_count > 1 && in_array(3,$bathrooms_array)) {
    $b_arr = array_diff($bathrooms_array, array(3));
    $bathrooms_query = "(bathrooms IN (".join(",", array_values($b_arr)).") OR bathrooms >= 5)";
}
else {
    $bathrooms_query = "bathrooms = '".$bathrooms_array[0]."' ";
}
$bathrooms_query = $mysqli->real_escape_string($bathrooms_query);
//-----------------




$sql = "SELECT name, bedrooms, bathrooms, garages, propertytype, listingprice, auction,latitude, longitude FROM alladdresses WHERE $listingprice_query AND $bedrooms_query AND $bathrooms_query";


// if ($mysqli->query($sql)) {

//     $result = $mysqli->query($sql);
//     while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
//         //printf ("%s | Bed: %s | Bath: %s | Garages: %s | %s | $%s | %s<br>", $row["name"], $row["bedrooms"], $row["bathrooms"], $row["garages"], $row["propertytype"], $row["listingprice"], $row["auction"]);
//     }

// } else {
//     echo "No results!";
// }

/* close connection */
//$mysqli->close();

//-----------------------------------------------------

// class Property {
//     public $type = "Feature";
//     public $id = "node/99999";
//     public $name = "";
//     public $lat = "";
//     public $geometry = "";
// }


// class Geometry {
//     public $type = "Point";
//     public $coordinates = "";
// }

// $property = new Property();
// $property->name = "1 Test Street";
// $lat = -35.2809;
// $long = 149.1300;

// $geometry = new Geometry();
// $geometry->coordinates = "[$lat, $long]";

// $property->geometry = $geometry;

// echo json_encode($property, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);




$data = array(); //setting up an empty PHP array for the data to go into

// if($result = mysqli_query($db,$query)) {
// while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
  
//     $data[] = $row;
//     echo "---";
//     echo $row;
//     echo "---";
//   }

  if ($mysqli->query($sql)) {

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        
        $data[] = $row;
    }

} else {
    echo "No results!";
}


$jsonData =json_encode($data);
$original_data = json_decode($jsonData, true);
$features = array();

foreach($original_data as $key => $value) {
    echo "test";
    $features[] = array(
        'type' => 'Feature',
        'properties' => array('time' => $value['bathrooms']),
        'geometry' => array(
             'type' => 'Point', 
             'coordinates' => array(
                  floatval($value['longitude']), 
                  floatval($value['latitude']), 
             ),
         ),
    );
}

$new_data = array(
    'type' => 'FeatureCollection',
    'features' => $features,
);

$final_data = json_encode($new_data);
// $final_data = json_encode($new_data, JSON_PRETTY_PRINT);

print_r($final_data);


?>

<script>
    // $(document).ready(function() {

    //  //   $template = '{"test":"Unit","out":5 }';




    //  // json_template = JSON.parse($template);

    //  // json_template["properties"] = "<?php echo $temp; ?>";
    //  // json_template["out"] = [<?php echo $t; ?>, <?php echo $t2; ?>];

    //  document.getElementById("demo").innerHTML = <?php print_r($final_data) ?>;

    //  json_results = <?php print_r($final_data) ?>;


    // L.geoJson(geojsonFeature).addTo(map);


    // });

    // LEAFLET CODE
    var map = L.map('mapid',{zoomControl:false}); // what does this do?
L.control.zoom({position:'bottomleft'}).addTo(map);
  var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
  var osm = new L.TileLayer(osmUrl); 

  map.setView([-35.26162,149.145187],12); // start the map in Canberra, Australia
  map.addLayer(osm); // add OSM layer to map
  geojsonFeature = <?php print_r($final_data) ?>;
  L.geoJson(geojsonFeature).addTo(map);

</script>

<?php



echo "</body>";

echo "</html>";

?>