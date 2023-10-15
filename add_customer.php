<?php

include('lib/common.php');
// written by czhang613

// setup permission
if (!isset($_SESSION['username'])) {
	header('Location: public_search.php');
	exit();
} else {
    if($_SESSION['permission'] == 3){
        header("Location: employee_search_manager.php");
        exit();
    }
}
?>

<?php
$enteredVIN = $_GET['vin'];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $enteredcustomer_id = mysqli_real_escape_string($db, $_POST['customer_id']);
        $enteredphone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
        $enteredemail = mysqli_real_escape_string($db, $_POST['email']);
        $enteredcustomer_street = mysqli_real_escape_string($db, $_POST['customer_street']);
        $enteredcustomer_city = mysqli_real_escape_string($db, $_POST['customer_city']);
        $enteredcustomer_state = mysqli_real_escape_string($db, $_POST['customer_state']);
        $enteredcustomer_zip = $_POST['customer_zip'];
        $entereddriver_license_number = mysqli_real_escape_string($db, $_POST['driver_license_number']);
        $enteredcustomer_first_name = mysqli_real_escape_string($db, $_POST['customer_first_name']);
        $enteredcustomer_last_name = mysqli_real_escape_string($db, $_POST['customer_last_name']);
        $enteredtax_identification_number = mysqli_real_escape_string($db, $_POST['tax_identification_number']);
        $enteredbusiness_name = mysqli_real_escape_string($db, $_POST['business_name']);
        $enteredprimary_contact_name = mysqli_real_escape_string($db, $_POST['primary_contact_name']);
        $enteredprimary_contact_title = mysqli_real_escape_string($db, $_POST['primary_contact_title']);

        if (empty($enteredcustomer_id)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Customer ID... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredphone_number)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Phone number... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredcustomer_street)) {
            array_push($error_msg, "ADD ERROR: Please enter a street address... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredcustomer_city)) {
            array_push($error_msg, "ADD ERROR: Please enter a city Name... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredcustomer_state)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate state... <br>" . __FILE__ . " line: " . __LINE__);
        }else if ($enteredcustomer_zip < 10000 || $enteredcustomer_zip > 99999) {
            array_push($error_msg, "ADD ERROR: Please enter a validate zip <br>" . __FILE__ . " line: " . __LINE__);
        }
        else if (empty($entereddriver_license_number) && empty($enteredtax_identification_number)) {
            array_push($error_msg, "ADD ERROR: Please enter a driver license number as person, or enter a tax identification number as business <br>" . __FILE__ . " line: " . __LINE__);
        }
        else if (!empty($entereddriver_license_number) && !empty($enteredtax_identification_number)) {
            array_push($error_msg, "ADD ERROR: Please enter either driver license number as person, or enter a tax identification number as business <br>" . __FILE__ . " line: " . __LINE__);
        }
        else if (!empty($entereddriver_license_number) && empty($enteredcustomer_first_name)){
            array_push($error_msg, "ADD ERROR: Please enter a first name <br>" . __FILE__ . " line: " . __LINE__);
        }
        else if (!empty($entereddriver_license_number) && empty($enteredcustomer_last_name)){
            array_push($error_msg, "ADD ERROR: Please enter a last name <br>" . __FILE__ . " line: " . __LINE__);
        }
        else if (!empty($enteredtax_identification_number) && empty($enteredbusiness_name)){
            array_push($error_msg, "ADD ERROR: Please enter a business name <br>" . __FILE__ . " line: " . __LINE__);
        }
        else if (!empty($enteredtax_identification_number) && empty($enteredprimary_contact_name )){
            array_push($error_msg, "ADD ERROR: Please enter a primary contact name <br>" . __FILE__ . " line: " . __LINE__);
        }
        else if (!empty($enteredtax_identification_number) && empty($enteredprimary_contact_title)){
            array_push($error_msg, "ADD ERROR: Please enter a primary contact title <br>" . __FILE__ . " line: " . __LINE__);
        }
        else{
            $query_id = "SELECT MAX(customer_id) AS largestid FROM Customer";
            $result_id = mysqli_query($db, $query_id);
            $row1 = mysqli_fetch_assoc($result_id);

            $query = "INSERT INTO Customer (customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip)"
                    ."VALUES('$enteredcustomer_id', '$enteredphone_number', '$enteredemail', ".
                "'$enteredcustomer_street', '$enteredcustomer_city', '$enteredcustomer_state',".
                "'$enteredcustomer_zip')";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');
            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR: Customer Table form  <br>".  __FILE__ ." line:". __LINE__ );
            }

            if(!empty($entereddriver_license_number)){
                $query1 = "INSERT INTO Person (customer_id, driver_license_number, customer_first_name, customer_last_name)"
                        ."VALUES('$enteredcustomer_id', '$entereddriver_license_number', '$enteredcustomer_first_name', ". "'$enteredcustomer_last_name')";
                $result1 = mysqli_query($db, $query1);
                include('lib/show_queries.php');
                if (mysqli_affected_rows($db) == -1) {
                    array_push($error_msg, "ADD ERROR:  Person Table form   <br>".  __FILE__ ." line:". __LINE__ );
                }
            }

            if(!empty($enteredtax_identification_number)){
                $query2 = "INSERT INTO Business (customer_id, tax_identification_number, business_name, primary_contact_name, primary_contact_title)"
                        ."VALUES('$enteredcustomer_id', '$enteredtax_identification_number', '$enteredbusiness_name', ". "'$enteredprimary_contact_name', '$enteredprimary_contact_title')";
                $result2 = mysqli_query($db, $query2);
                include('lib/show_queries.php');
                if (mysqli_affected_rows($db) == -1) {
                    array_push($error_msg, "ADD ERROR:  Business Table form   <br>".  __FILE__ ." line:". __LINE__ );
                }
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>GTOnline Edit Profile</title>
	</head>

	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>

			<div class="center_content">
				<div class="center_left">
					<!-- <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div> -->
					<div class="features">

            <div class="Add Customer section">
							<div class="subtitle">Add Customer Info</div>

                            <form name = "add_customer" action = "add_customer.php?vin=<?php echo $enteredVIN;?>&customer_id=<?php echo $enteredcustomer_id;?>" method="post">
                                <table>
                                  <!-- <tr>
                                      <td class ="item_label">Current Largest Customer ID</td>
                                      <td>
                                          <?php
                                          // echo $row1['largestid'];
                                          ?>
                                      </td>
                                  </tr> -->
                                    <tr>
                                        <td class ="item_label">Customer ID</td>
                                        <td>
                                            <input type="text" name = "customer_id" value="<?php if ($_POST['customer_id']) { print $_POST['customer_id']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Phone Number</td>
                                        <td>
                                            <input type="item_label" name= "phone_number" value="<?php if ($_POST['phone_number']) { print $_POST['phone_number']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Email</td>
                                        <td>
                                            <input type="text" name = "email" value ="<?php if($_POST['email']) {print $_POST['email'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Street Address</td>
                                        <td>
                                            <input type="text" name = "customer_street" value ="<?php if($_POST['email']) {print $_POST['email'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">City</td>
                                        <td>
                                            <input type="text" name = "customer_city" value ="<?php if($_POST['customer_city']) {print $_POST['customer_city'];}?>" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class = "item_label">State</td>
                                        <td>
                                            <input type="text" name = "customer_state" value ="<?php if($_POST['customer_state']) {print $_POST['customer_state'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Zip Code</td>
                                        <td>
                                            <input type="number" name = "customer_zip" min = "10000" max = "99999" value ="<?php if($_POST['customer_zip']) {print $_POST['customer_zip'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                      <td class = "subtitle">Enter following for a PERSON:  </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Driver License Number</td>
                                        <td>
                                            <input type="text" name = "driver_license_number" value ="<?php if($_POST['driver_license_number']) {print $_POST['driver_license_number'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">First Name</td>
                                        <td>
                                            <input type="text" name = "customer_first_name" value ="<?php if($_POST['customer_first_name']) {print $_POST['customer_first_name'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Last Name</td>
                                        <td>
                                            <input type="text" name = "customer_last_name" value ="<?php if($_POST['customer_last_name']) {print $_POST['customer_last_name'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                      <td class = "subtitle">Enter following for a BUSINESS: </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Tax Identification Number</td>
                                        <td>
                                            <input type="text" name = "tax_identification_number" value ="<?php if($_POST['tax_identification_number']) {print $_POST['tax_identification_number'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Business Name</td>
                                        <td>
                                            <input type="text" name = "business_name" value ="<?php if($_POST['business_name']) {print $_POST['business_name'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Primary Contact Name</td>
                                        <td>
                                            <input type="text" name = "primary_contact_name" value ="<?php if($_POST['primary_contact_name']) {print $_POST['primary_contact_name'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Primary Contact Title</td>
                                        <td>
                                            <input type="text" name = "primary_contact_title" value ="<?php if($_POST['primary_contact_title']) {print $_POST['primary_contact_title'];}?>" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td><input name = "add" type = "submit" id = "add" value = "Add"></td>
                                    </tr>
                                    <?php
																		if (!empty($enteredVIN)){
																			print "<tr>";
	                                    $get_url2="sale_order.php?vin={$enteredVIN}&customer_id={$enteredcustomer_id}";
	                                    print "<td><a href={$get_url2}>Sell This Car</a></td>";
	                                    print "</tr>";
																		}
																		if (empty($enteredVIN)){
																			print "<tr>";
	                                    $get_url2="add_vehicle.php?vin={$enteredVIN}&customer_id={$enteredcustomer_id}";
	                                    print "<td><a href={$get_url2}>Add Vehicle</a></td>";
	                                    print "</tr>";
																		}

                                    ?>
                                </table>
                            </form>
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
