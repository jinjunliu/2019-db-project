<?php

include('lib/common.php');
// written by zxie86


    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    }
    
$query = "SELECT login_first_name, login_last_name " .
    "FROM Users " .
    "WHERE Users.username = '{$_SESSION['username']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get User Profile... <br>".  __FILE__ ." line:". __LINE__ );
}
?>

<?php include("lib/header.php"); ?>
<title>View Vehicle Info</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>
            <div class="features">

                <div class="View Repair section">
                    <div class="subtitle">View Vehicle Info</div>

                    <form name="profileform" action="view_vehicle.php" method="get">
                        <table>
                            <tr>
                                <td>Vin Number</td>
                                <td>
                                    <input name = "vin" type = "text" id = "vin">
                                </td>
                            </tr>
                            <tr>
                                <input name = "view" type = "submit" id = "view" value = "View">
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $enteredVin = mysqli_real_escape_string($db, $_GET['vin']);
            $query = "";
            if(empty($enteredVin)){
                //array_push($error_msg,  "INPUT ERROR: Please input validate  Vin number... <br>".  __FILE__ ." line:". __LINE__ );
                $query = "SELECT vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name, sale_price FROM Vehicle ";
            }else{
                $query = "SELECT vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name, sale_price ";
                $from = " FROM Vehicle ";
                $where = " WHERE vin = '$enteredVin' ";
                $query = $query . $from . $where . " ORDER BY vin";
            }
            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                array_push($error_msg,  "Query ERROR: Failed to get Vehicle information..." . __FILE__ ." line:". __LINE__ );
            }
            echo "<div>";
            echo "<table>";
            echo "<tr>";
            echo "<td class=\"heading\">Vin</td>";
            echo "<td class=\"heading\">Vehicle Mileage </td>";
            echo "<td class=\"heading\">Vehicle Description</td>";
            echo "<td class=\"heading\">Model Name</td>";
            echo "<td class=\"heading\">Model Year</td>";
            echo "<td class=\"heading\">Type Name</td>";
            echo "<td class=\"heading\">Manufacturer Name</td>";
            echo "<td class=\"heading\">Sale Price</td>";
            echo "<td class=\"heading\">Purchase Price</td>";
            echo "</tr>";

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $vin = $row['vin'];
                $temp_result = mysqli_query($db, "SELECT purchase_price FROM Buy WHERE vin = '$vin' ");
                $temp_row = mysqli_fetch_array($temp_result, MYSQLI_ASSOC);
                $purchase_price = $temp_row['purchase_price'];
                print "<tr>";
                print "<td>" . $row['vin'] . "</td>";
                print "<td>" . $row['vehicle_mileage'] . "</td>";
                print "<td>" . $row['vehicle_description'] . "</td>";
                print "<td>" . $row['model_name'] . "</td>";
                print "<td>" . $row['model_year'] . "</td>";
                print "<td>" . $row['type_name'] . "</td>";
                print "<td>" . $row['manufacturer_name'] . "</td>";
                print "<td>" . $row['sale_price'] . "</td>";
                echo "<td>" . $purchase_price . "</td>";
                echo "<td><a href='edit_vehicle.php?vin=".$row['vin']."&manufacturer_name=".$row['manufacturer_name']."&vehicle_mileage=".$row['vehicle_mileage']. "&vehicle_description=" .$row['vehicle_description']. "&model_name=" .$row['model_name']. "&model_year=" .$row['model_year']. "&type_name=" .$row['type_name']. "&sale_price=" . $purchase_price."'>Edit</a></td>";
                echo "<td><a href='delete_vehicle.php?vin=".$row['vin']."&manufacturer_name=".$row['manufacturer_name']."&vehicle_mileage=".$row['vehicle_mileage']. "&vehicle_description=" .$row['vehicle_description']. "&model_name=" .$row['model_name']. "&model_year=" .$row['model_year']. "&type_name=" .$row['type_name']. "&sale_price=" .$purchase_price."'>Delete</a></td>";
                print "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
        ?>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>

    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>
