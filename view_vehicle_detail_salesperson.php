<?php
include('lib/common.php');
// written by czhang613
// setup permission and login info
if (!isset($_SESSION['username'])) {
	header('Location: view_vehicle_detail_public.php');
	exit();
} else {
    if($_SESSION['permission'] == 1){
        header("Location: view_vehicle_detail_clerk.php");
        exit();
    }
    if($_SESSION['permission'] == 3){
        header("Location: view_vehicle_detail_manager.php");
        exit();
    }
    if($_SESSION['permission'] == 4){
        header("Location: view_vehicle_detail_owner.php");
        exit();
    }
}

    $enteredVIN = $_GET['vin'];
    
    $query = "SELECT Vehicle.vin, vehicle_mileage, vehicle_description, model_name, model_year, manufacturer_name, GROUP_CONCAT(DISTINCT vehicle_color SEPARATOR ', ') AS color, sale_price "
    . "FROM Vehicle LEFT JOIN VehicleColor ON Vehicle.vin = VehicleColor.vin "
    . "LEFT JOIN Repair ON Vehicle.vin = Repair.vin "
    . "WHERE repair_status = 'completed' AND Vehicle.vin = '$enteredVIN'";
    
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to fetch Vehicle Detail Information... <br>".  __FILE__ ." line:". __LINE__ );
    }
?>

<?php include("lib/header.php"); ?>
		<title>View Vehicle Detail for Salesperson</title>
	</head>

	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>

			<div class="center_content">
				<div class="center_left">
					<!-- <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div> -->
					<div class="features">
                           <div class="View vehicle detail section">
							<div class="subtitle">View Vehicle Detail</div>
              <table>
              <th class="subtitle">Basic Information</th>
                  <tr>
                      <td class="item_label">VIN</td>
                      <td>
                          <?php print $row['vin']; ?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Mileage</td>
                      <td>
                          <?php print $row['vehicle_mileage'];?>
                      </td>
                  </tr>
                  
                  <tr>
                      <td class="item_label">Model name</td>
                      <td>
                          <?php print $row['model_name'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Model year</td>
                      <td>
                          <?php print $row['model_year'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Manufacturer Name</td>
                      <td>
                          <?php print $row['manufacturer_name'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Model year</td>
                      <td>
                          <?php print $row['model_year'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Vehicle Color</td>
                      <td>
                          <?php print $row['color'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Vehicle Description</td>
                      <td>
                          <?php print $row['vehicle_description'];?>
                      </td>
                  </tr>

                  <tr>
                      <td class="item_label">Sale Price</td>
                      <td>
                          <?php print $row['sale_price'];?>
                      </td>
                  </tr>
<?php

                  print "<tr>";
                  $get_url1="search_customer.php?vin={$row['vin']}";
                  // echo $row['vin'];
                  print "<td><a href={$get_url1}>Sell This Car</a></td>";
                  print "</tr>";
?>
              </table>
                          </div>
					 </div>
				</div>

                <?php include("lib/error.php"); ?>

				<div class="clear"></div>
			</div>

               <?php include("lib/footer.php"); ?>

		</div>
	</body>
</html>
