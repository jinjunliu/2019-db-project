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
    if($_SESSION['permission'] == 2){
        header("Location: view_vehicle_detail_salesperson.php");
        exit();
    }
    if($_SESSION['permission'] == 4){
        header("Location: view_vehicle_detail_owner.php");
        exit();
    }
}


    // $enteredVIN = '0OZ6776OUA0065975';//person
		// $enteredVIN = '036JZFZ8I3K433701';//business
		$enteredVIN = $_GET['vin'];

// query to get basic info
    $query = "SELECT Vehicle.vin, vehicle_mileage, vehicle_description, model_name, model_year, "
    . "manufacturer_name, sale_price, GROUP_CONCAT(DISTINCT vehicle_color SEPARATOR ', ') AS color "
		. "FROM Vehicle "
		. "LEFT JOIN VehicleColor ON Vehicle.vin = VehicleColor.vin "
    . "WHERE Vehicle.vin = '$enteredVIN'";
    
		$result = mysqli_query($db, $query);
		include('lib/show_queries.php');
		$result1 = $result;

		if (!is_bool($result1) && (mysqli_num_rows($result1) > 0) ) {
        $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to fetch Basic Information... <br>".  __FILE__ ." line:". __LINE__ );
    }


// query to get repair
$query = "SELECT start_date, end_date, repair_status, repair_description, repair_cost, vendor_name, Repair.nhtsa_recall_compaign_number, Buy.inventory_clerk_permission, purchase_price "
. "FROM Vehicle LEFT JOIN Buy on Vehicle.vin = Buy.vin "
. "LEFT JOIN Repair on Vehicle.vin = Repair.vin "
. " WHERE Vehicle.vin = '$enteredVIN' ORDER BY start_date DESC";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
$result5 = $result;


// query to get purchase
    $query = "SELECT Buy.inventory_clerk_permission, purchase_price, purchase_condition, purchase_date, Buy.customer_id AS seller_customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip, "
    . "Users.login_first_name AS login_first_name1, Users.login_last_name AS login_last_name1 "
    . "FROM Vehicle "
    . "LEFT JOIN Buy ON Vehicle.vin = Buy.vin "
    . "LEFT JOIN Customer ON Buy.customer_id = Customer.customer_id "
    . "LEFT JOIN InventoryClerk ON InventoryClerk.inventory_clerk_permission= Buy.inventory_clerk_permission "
    . "LEFT JOIN Users ON InventoryClerk.username = Users.username "
    . "WHERE Vehicle.vin = '$enteredVIN'";

		$result = mysqli_query($db, $query);
		include('lib/show_queries.php');
		$result2 = $result;
		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);


// query to get sell
    $query = "SELECT Vehicle.vin, Sell.salesperson_permission, Sell.customer_id AS buyer_customer_id, sale_date, phone_number, email, customer_street, customer_city, customer_state, customer_zip, "
    . "login_first_name AS login_first_name2, login_last_name AS login_last_name2 "
    . "FROM Vehicle "
    . "LEFT JOIN Sell ON Vehicle.vin = Sell.vin "
    . "LEFT JOIN Customer ON Sell.customer_id = Customer.customer_id "
    . "LEFT JOIN Salesperson ON Salesperson.salesperson_permission = Sell.salesperson_permission "
    . "LEFT JOIN Users ON Salesperson.username = Users.username "
    . "WHERE Vehicle.vin = '$enteredVIN'";

		$result = mysqli_query($db, $query);
		include('lib/show_queries.php');
		$result3 = $result;
    $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);


// query to get total cost
$query = "SELECT SUM(repair_cost) AS totalcost FROM Repair WHERE vin = '$enteredVIN' GROUP BY vin";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');
$result4 = $result;
$row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);

?>


<?php include("lib/header.php"); ?>
		<title>View Vehicle Detail for Manager</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	</head>

	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>

			<div class="center_content">
				<div class="center_left">
					<!-- <div class="title_name"><?php //print $row['first_name'] . ' ' . $row['last_name']; ?></div> -->
					<div class="features">
              <div class="View vehicle detail section">
							<div class="subtitle">View Vehicle Detail</div>

              <table>
                  <th class="subtitle">Basic Information</th>
                  <tr>
                      <td class="item_label">VIN</td>
                      <td>
                          <?php print $row1['vin']; ?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Mileage</td>
                      <td>
                          <?php print $row1['vehicle_mileage'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Vehicle Description</td>
                      <td>
                          <?php
													function popup($var){
														alert($var); // this is the message in ""
													}
													print $row1['vehicle_description'];
													// alert($row1['vehicle_description']);
													?>

                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Model Name</td>
                      <td>
                          <?php print $row1['model_name'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Model Year</td>
                      <td>
                          <?php print $row1['model_year'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Manufacturer Name</td>
                      <td>
                          <?php print $row1['manufacturer_name'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Vehicle Color</td>
                      <td>
                          <?php print $row1['color'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Sale Price</td>
                      <td>
                          <?php print $row1['sale_price'];?>
                      </td>
                  </tr>

                  <th class="subtitle">Repair Information</th>
                  <tr>
                      <td class="item_label">Total Repair Cost: 
                          <?php
													if (empty($row4['totalcost'])){
														$row4['totalcost'] = '0';
														print $row4['totalcost'];
													} else {
														print $row4['totalcost'];
													}
													?>
                        </td>
                  </tr>
                </table>
                <table>
								<table border='1'>
								<tr>
                <td>Vendor</td>
                <td>Start Date</td>
                <td>End Date</td>
                <td>Status</td>
                <td>Cost</td>
                <td>Recall Number</td>
                <td>Repair Description</td>
                </tr>
                <?php
									if (is_bool($result5) && (mysqli_num_rows($result5) == 0) ) {
											array_push($error_msg,  "Query ERROR: Failed to get Repair info...<br>" . __FILE__ ." line:". __LINE__ );
                  }
                  $button_num = 1;
									while ($row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC)) {
                    //$button_id = "#b1";
                    //$dialog_id = "#my_dialog";
                    $button_id = "#b" . "$button_num";
                    $dialog_id = "#my_dialog" . "$button_num";
                    //echo "$dialog_id";
                ?>
								<tr>
                <td><?php echo $row5['vendor_name'] ?></td>
                <td><?php echo $row5['start_date'] ?></td>
                <td><?php echo $row5['end_date'] ?></td>
                <td><?php echo $row5['repair_status']?></td>
                <td><?php echo $row5['repair_cost'] ?></td>
                <td><?php echo $row5['nhtsa_recall_compaign_number'] ?></td>
								<!--dialog box-->
								<td>
                <!--
                <script>$(document).ready(function(){$(function(){$("#my_dialog").dialog({autoOpen: false});});$("#b1").click(function(){$("#my_dialog").dialog("open");})})</script>
                -->
                <script>$(document).ready(function(){$(function(){$(<?php echo '"' . $dialog_id . '"'; ?>).dialog({autoOpen: false});});$(<?php echo '"' . $button_id . '"'; ?>).click(function(){$(<?php echo '"' . $dialog_id . '"'; ?>).dialog("open");})})</script>
								
                <button id=<?php echo '"' . "b" . "$button_num" . '"'; ?>>Repair Description</button>
								<div id=<?php echo '"' . "my_dialog" . "$button_num" . '"'; ?> title="Repair Description">
								<p>
								<?php print $row5['repair_description']; ?>
						    </p>
								</div>
								</td>
								</tr>
                <?php $button_num = $button_num + 1;
                } ?>
								</table>
                </table>

                <table>
                  <th class="subtitle">Purhcase Transaction</th>
                  <tr>
                  <td class="item_label">Clerk's First Name</td>
                    <td>
                      <?php
                      echo $row2['login_first_name1'];
                      ?>
                    </td>
                  </tr>
									<td class="item_label">Clerk's Last Name</td>
                    <td>
                      <?php
                      echo $row2['login_last_name1'];
                      ?>
                    </td>
                  </tr>
                  <tr>
                      <td class="item_label">Purchase Price</td>
                      <td>
                          <?php print $row2['purchase_price'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Purchased Condition</td>
                      <td>
                          <?php print $row2['purchase_condition'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Purchased Date</td>
                      <td>
                          <?php print $row2['purchase_date'];?>
                      </td>
                  </tr>
                  </table>

                  <table>
									<th class="subtitle">Seller Information</th>
<!--                   <tr>
                      <td class="item_label">Customer ID</td>
                      <td>
                          <?php print $row2['seller_customer_id'];?>
                      </td>
                  </tr> -->
									<tr>
                      <td class="item_label">Phone Number</td>
                      <td>
                          <?php print $row2['phone_number'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Email</td>
                      <td>
                          <?php print $row2['email'];?>
                      </td>
                  </tr>
									<tr>
                      <td class="item_label">Street Address</td>
                      <td>
                          <?php print $row2['customer_street'];?>
                      </td>
                  </tr>
									<tr>
                      <td class="item_label">City</td>
                      <td>
                          <?php print $row2['customer_city'];?>
                      </td>
                  </tr>
									<tr>
                      <td class="item_label">State</td>
                      <td>
                          <?php print $row2['customer_state'];?>
                      </td>
                  </tr>
									<tr>
                      <td class="item_label">Zip Code</td>
                      <td>
                          <?php print $row2['customer_zip'];?>
                      </td>
                  </tr>

											<?php
											$current_customer_id = $row2['seller_customer_id'];
											$query = "SELECT Customer.customer_id, customer_first_name, customer_last_name, business_name, primary_contact_name, primary_contact_title FROM Customer LEFT JOIN Person ON Customer.customer_id = Person.customer_id LEFT JOIN business ON Customer.customer_id = Business.customer_id WHERE Customer.customer_id = '$current_customer_id'";

											$result = mysqli_query($db, $query);
											include('lib/show_queries.php');
											$result_seller = $result;
									    $row_seller= mysqli_fetch_array($result_seller, MYSQLI_ASSOC);
											// var_dump($row10);
											if (!is_null($row_seller['customer_first_name'])) {
												echo "<tr>";
												echo "<td class=\"item_label\"> First Name</td>";
												echo "<td>";
												echo $row_seller['customer_first_name'];
												echo "</td>";
												echo "</tr>";
												echo "<tr>";
												echo "<td class=\"item_label\"> Last Name</td>";
												echo "<td>";
												echo $row_seller['customer_last_name'];
												echo "</td>";
												echo "</tr>";
                      } else {
												echo "<tr>";
												echo "<td class=\"item_label\"> Business Name</td>";
												echo "<td>";
												echo $row_seller['business_name'];
												echo "</td>";
												echo "</tr>";
												echo "<tr>";
												echo "<td class=\"item_label\"> Primary Contact Name</td>";
												echo "<td>";
												echo $row_seller['primary_contact_name'];
												echo "</td>";
												echo "</tr>";
												echo "<td class=\"item_label\"> Primary Contact Title</td>";
												echo "<td>";
												echo $row_seller['primary_contact_title'];
												echo "</td>";
												echo "</tr>";
											}

                      ?>


									<?php
									if (mysqli_num_rows($result3) > 0){

									echo "<th class=\"subtitle\">Sale Transaction</th>";
									echo "<tr>";
                  echo "<td class=\"item_label\">Salesperson's First Name</td>";
                	echo "<td>";
                  echo $row3['login_first_name2'];
                  echo "</td>";
                  echo "</tr>";
									echo "<tr>";
									echo "<td class=\"item_label\">Salesperson's Last Name</td>";
                  echo "<td>";
                  echo $row3['login_last_name2'];
                  echo "</td>";
                  echo "</tr>";

									echo "<tr>";
									echo "<td class=\"item_label\"> Sale Date </td>";
									echo "<td>";
                  echo $row3['sale_date'];
                  echo "</td>";
                  echo "</tr>";

                  echo "<th class=\"subtitle\">Buyer Information</th>";
// 									echo "<tr>";
// 									echo "<td class=\"item_label\"> Customer ID </td>";
// 									echo "<td>";
//                   echo $row3['buyer_customer_id'];
//                   echo "</td>";
//                   echo "</tr>";

									echo "<tr>";
									echo "<td class=\"item_label\"> Phone Number </td>";
									echo "<td>";
                  print $row3['phone_number'];
                  echo "</td>";
                  echo "</tr>";
									echo "<tr>";
									echo "<td class=\"item_label\"> Street Address </td>";
									echo "<td>";
                  print $row3['customer_street'];
                  echo "</td>";
                  echo "</tr>";
									echo "<tr>";
									echo "<td class=\"item_label\"> City </td>";
									echo "<td>";
                  print $row3['customer_city'];
									echo "</td>";
                  echo "</tr>";
									echo "<tr>";
									echo "<td class=\"item_label\"> State</td>";
									echo "<td>";
                  print $row3['customer_state'];
									echo "</td>";
                  echo "</tr>";
									echo "<tr>";
									echo "<td class=\"item_label\"> Zip Code</td>";
									echo "<td>";
                  print $row3['customer_zip'];
                  echo "</td>";
                  echo "</tr>";

									$current_customer_id = $row3['buyer_customer_id'];
									$query = "SELECT Customer.customer_id, customer_first_name, customer_last_name, business_name, primary_contact_name, primary_contact_title FROM Customer LEFT JOIN Person ON Customer.customer_id = Person.customer_id LEFT JOIN business ON Customer.customer_id = Business.customer_id WHERE Customer.customer_id = '$current_customer_id'";
									$result = mysqli_query($db, $query);
									include('lib/show_queries.php');
									$result_buyer = $result;
									$row_buyer = mysqli_fetch_array($result_buyer, MYSQLI_ASSOC);
									// var_dump($row10);
									if (!is_null($row_buyer['customer_first_name'])) {
										echo "<tr>";
										echo "<td class=\"item_label\"> First Name</td>";
										echo "<td>";
										echo $row_buyer['customer_first_name'];
										echo "</td>";
										echo "</tr>";
										echo "<tr>";
										echo "<td class=\"item_label\"> Last Name</td>";
										echo "<td>";
										echo $row_buyer['customer_last_name'];
										echo "</td>";
										echo "</tr>";
									} else {
										echo "<tr>";
										echo "<td class=\"item_label\"> Business Name</td>";
										echo "<td>";
										echo $row_buyer['business_name'];
										echo "</td>";
										echo "</tr>";
										echo "<tr>";
										echo "<td class=\"item_label\"> Primary Contact Name</td>";
										echo "<td>";
										echo $row_buyer['primary_contact_name'];
										echo "</td>";
										echo "</tr>";
										echo "<td class=\"item_label\"> Primary Contact Title</td>";
										echo "<td>";
										echo $row_buyer['primary_contact_title'];
										echo "</td>";
										echo "</tr>";
									}
								}
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
