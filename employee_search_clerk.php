<?php
include('lib/common.php');
// written by jliu788

if (!isset($_SESSION['username']) OR $_SESSION['permission'] != 1) {
	header('Location: index.php');
	exit();
}

if($showQueries){
    array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

$query = "SELECT COUNT(DISTINCT(Vehicle.vin)) as total FROM Vehicle LEFT JOIN Repair " .
         "ON Vehicle.vin = Repair.vin WHERE Vehicle.vin NOT IN (SELECT vin FROM Sell) " . 
         "AND (repair_status = 'in progress' OR repair_status = 'pending')";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $car_num2 = mysqli_fetch_assoc($result);
    $car2 = $car_num2['total'];
} else {
    $car2 = 0;
}

$query = "SELECT COUNT(DISTINCT(Vehicle.vin)) as total " .
         "FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin " .
         "WHERE Vehicle.vin NOT IN (SELECT vin FROM Sell) " .
         "AND ( " . 
         "(Vehicle.vin NOT IN (SELECT DISTINCT vin FROM Repair)) " . 
         "OR (Vehicle.vin NOT IN (SELECT DISTINCT vin FROM Repair WHERE repair_status = 'pending' OR repair_status = 'in progress')) " . 
         ")";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $car_num3 = mysqli_fetch_assoc($result);
    $car3 = $car_num3['total'];
} else {
    $car3 = 0;
}

$if_search = 0;
/* if form was submitted, then execute query to search for vehicles */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $if_search = 1;
	$entered_type_name = mysqli_real_escape_string($db, $_POST['type_name']);
	$entered_manufacturer_name = mysqli_real_escape_string($db, $_POST['manufacturer_name']);
    $entered_model_year = mysqli_real_escape_string($db, $_POST['model_year']);
    $entered_vehicle_color = mysqli_real_escape_string($db, $_POST['vehicle_color']);
    $keyword = mysqli_real_escape_string($db, $_POST['keyword']);
    $entered_vin = mysqli_real_escape_string($db, $_POST['vin']);

    $query = "SELECT Vehicle.vin, `type_name`, model_name, model_year, manufacturer_name, " .
             "GROUP_CONCAT(DISTINCT vehicle_color SEPARATOR ', ') AS color, vehicle_mileage, sale_price " .
             "FROM Vehicle " .
             "LEFT JOIN VehicleColor ON VehicleColor.vin=Vehicle.vin " .
             "WHERE Vehicle.vin NOT IN (SELECT vin FROM Sell) ";

    if ($entered_type_name != "select" or $entered_manufacturer_name != "select"
        or $entered_vehicle_color != "select" or $entered_model_year != 0
        or (!empty($keyword) and $keyword != '(input search keyword)' and trim($keyword) != '')
        or (!empty($entered_vin) and $entered_vin != '(input VIN)' and trim($entered_vin) != '')) {

		$query = $query . " AND (1=1";

		if ($entered_type_name != "select") {
			$query = $query . " AND `type_name`='$entered_type_name' ";
        }
        if ($entered_manufacturer_name != "select") {
			$query = $query . " AND manufacturer_name='$entered_manufacturer_name' ";
        }
        if ($entered_vehicle_color != "select") {
			$query = $query . " AND vehicle_color='$entered_vehicle_color' ";
        }
        if ($entered_model_year != 0) {
			$query = $query . " AND model_year=$entered_model_year ";
        }
        if (!empty($keyword) and $keyword != '(input search keyword)' and trim($keyword) != '') {
            $query = $query . " AND (" .
            "manufacturer_name LIKE '%$keyword%' " .
            "OR model_year LIKE '%$keyword%' " .
            "OR model_name LIKE '%$keyword%' " .
            "OR vehicle_description LIKE '%$keyword%' " .
            ") ";
        }
        if (!empty($entered_vin) and $entered_vin != '(input VIN)' and trim($entered_vin) != '') {
            $query = $query . " AND Vehicle.vin='$entered_vin' ";
        }
		$query = $query . ") ";
	}

    $query = $query . " GROUP BY Vehicle.vin ORDER BY Vehicle.vin ASC";
	$result = mysqli_query($db, $query);
    include('lib/show_queries.php');

    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
    } else {
        array_push($error_msg,  "SELECT ERROR: employee search <br>" . __FILE__ ." line:". __LINE__ );
    }

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "SELECT ERROR:Failed to find vehicles ... <br>" . __FILE__ ." line:". __LINE__ );
    }
}
?>

<?php include("lib/header.php"); ?>
<title>Vehicle Search for Inventory Clerk</title>
</head>
<body>
    <div id="main_container">
    <?php include("lib/menu.php"); ?>
        <div class="center_content">
			<div class="center_left">
                <div class="features">
                <div class='profile_section'>
					    <div class='subtitle'>You are an inventory clerk</div>
				    </div>
                    <div class='profile_section'>
					    <div class='subtitle'>Number of vehicles (for inventory clerk view)</div>
					    <?php

                        echo "<br>Number of vehicles with repair pending or in progress: {$car2}</br>";

                        echo "<br>Number of vehicles available for purchase: {$car3}</br>";

                        ?>
				    </div>
					<div class="profile_section">
						<div class="subtitle">Search for Vehicles</div>
						<form name="searchform" action="employee_search_clerk.php" method="POST">
                            <table>
                                <tr>
                                    <td class="item_label">Vehicle Type</td>
                                    <td>
                                        <select name="type_name">
                                            <option value='select' selected="true">Please select</option>
                                            <?php
                                                foreach($VEHICLE_TYPES_LIST as $var) {
                                            ?>
                                            <option value='<?php echo $var;?>'><?php echo $var;?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="item_label">Manufacturer</td>
                                    <td>
                                        <select name="manufacturer_name">
                                            <option value='select' selected="true">Please select</option>
                                            <?php
                                                foreach($MANUFACTURER_LIST as $var) {
                                            ?>
                                            <option value= '<?php echo $var;?>'><?php echo $var;?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="item_label">Model year</td>
                                    <td>
                                        <select name="model_year">
                                            <option value=0 selected="true">Please select</option>
                                            <?php
                                                for($n_year=2020; $n_year>=1900; $n_year--) {
                                            ?>
                                            <option value= '<?php echo $n_year;?>'><?php echo $n_year;?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="item_label">Color</td>
                                    <td>
                                        <select name="vehicle_color">
                                            <option value='select' selected="true">Please select</option>
                                            <?php
                                                foreach($COLORS_LIST as $var) {
                                            ?>
                                            <option value= '<?php echo $var;?>'><?php echo $var;?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
									<td class="item_label">VIN</td>
									<td><input type="text" name="vin" value="(input VIN)"
										onclick="if(this.value=='(input VIN)'){this.value=''}"
										onblur="if(this.value==''){this.value='(input VIN)'}"/></td>
								</tr>
								<tr>
									<td class="item_label">keyword</td>
									<td><input type="text" name="keyword" value="(input search keyword)"
										onclick="if(this.value=='(input search keyword)'){this.value=''}"
										onblur="if(this.value==''){this.value='(input search keyword)'}"/></td>
								</tr>

							</table>
							<a href="javascript:searchform.submit();" class="fancy_button">Search</a>
						</form>
					</div>

				    <div class='profile_section'>
					    <div class='subtitle'>Search Results</div>
                        <?php
                            if($if_search && $count>0) {
                                echo "<tr> {$count} car(s) are found. </tr>";
                            } elseif($if_search && $count==0) {
                                echo "<tr> Sorry, it looks like we don't have that in stock! </tr>";
                            }
                        ?>
					    <table>
						    <tr>
							    <td class='heading'>VIN</td>
                                <td class='heading'>Vehicle Type</td>
                                <td class='heading'>Model Year</td>
                                <td class='heading'>Manufacturer</td>
                                <td class='heading'>Model</td>
                                <td class='heading'>Color</td>
                                <td class='heading'>Mileage</td>
                                <td class='heading'>Sale Price</td>
                            </tr>
                            <?php
							    if (isset($result)) {
								    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['vin']}</td>";
                                        print "<td>{$row['type_name']}</td>";
                                        print "<td>{$row['model_year']}</td>";
                                        print "<td>{$row['manufacturer_name']}</td>";
                                        print "<td>{$row['model_name']}</td>";
                                        print "<td>{$row['color']}</td>";
                                        print "<td>{$row['vehicle_mileage']}</td>";
                                        print "<td>{$row['sale_price']}</td>";
                                        $get_url="view_vehicle_detail_clerk.php?vin={$row['vin']}";
                                        print "<td><a href={$get_url}>View detail</a></td>";
                                        print "</tr>";
								    }
                                }
                                //print "<tr> <a href='add_vehicle.php'>Add Vehicle</a></tr>";
                            ?>
					    </table>
				    </div>
                    <div class='profile_section'>
					    <div class='subtitle'><a href='add_vehicle.php'>Add Vehicle</a></div>
				    </div>
                </div>
            </div>
        <?php include("lib/error.php"); ?>
		<div class="clear"></div>
	</div>

	<?php include("lib/footer.php"); ?>
</body>
</html>
