<?php

echo "<html>";
echo "<head>";
echo "<title>";
echo "TEST SQL";
echo "</title>";

echo "</head>";

echo "<body>";
echo "<script src='jquery.js'></script>";

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




$sql = "SELECT name, bedrooms, bathrooms, garages, propertytype, listingprice, auction FROM alladdresses WHERE $listingprice_query AND $bedrooms_query AND $bathrooms_query";

// var_dump($sql);
// echo "<p>";

// echo "<p>";

if ($mysqli->query($sql)) {

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        //printf ("%s | Bed: %s | Bath: %s | Garages: %s | %s | $%s | %s<br>", $row["name"], $row["bedrooms"], $row["bathrooms"], $row["garages"], $row["propertytype"], $row["listingprice"], $row["auction"]);
    }

} else {
    echo "No results!";
}

/* close connection */
$mysqli->close();

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


echo "<div id='demo'></div>";


$temp = "HELLO";
$t = [2,5];

?>

<script>
    $(document).ready(function() {

       $template = '{"test":"Unit","out":5 }';




     json_template = JSON.parse($template);

     json_template["properties"] = "<?php echo $temp; ?>";
     json_template["out"] = <?php echo $t; ?>;

     document.getElementById("demo").innerHTML = json_template;

     console.log(json_template);

    });

</script>

<?php



echo "</body>";

echo "</html>";

?>