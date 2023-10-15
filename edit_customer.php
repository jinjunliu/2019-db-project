<?php

include('lib/common.php');
// written by czhang613

$enteredcustomer_id = $_GET['customer_id'];
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $enteredcustomer_id = $_GET['customer_id'];
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

        if (!empty($entereddriver_license_number) && !empty($enteredtax_identification_number)) {
            array_push($error_msg, "ADD ERROR: Please enter either driver license number as person, or enter a tax identification number as business <br>" . __FILE__ . " line: " . __LINE__);
        }else{
          // update customer
          if (!empty($enteredphone_number)) {
              $query = "UPDATE Customer SET phone_number = '$enteredphone_number' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Phone Number ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredemail)) {
              $query = "UPDATE Customer SET email = '$enteredemail' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Phone Number ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredcustomer_street)) {
              $query = "UPDATE Customer SET customer_street = '$enteredcustomer_street' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Street Address ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredcustomer_city)) {
              $query = "UPDATE Customer SET customer_city = '$enteredcustomer_city' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: City ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredcustomer_state)) {
              $query = "UPDATE Customer SET customer_state = '$enteredcustomer_state' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: State ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredcustomer_zip)) {
              $query = "UPDATE Customer SET customer_zip = '$enteredcustomer_zip' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Zip Code ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          // update person
          if (!empty($entereddriver_license_number)) {
              $query = "UPDATE Person SET driver_license_number = '$entereddriver_license_number' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Driver License Number ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredcustomer_first_name)) {
              $query = "UPDATE Person SET customer_first_name = '$enteredcustomer_first_name' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: First Name ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredcustomer_last_name)) {
              $query = "UPDATE Person SET customer_last_name = '$enteredcustomer_last_name' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Last Name ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          // update business
          if (!empty($enteredtax_identification_number)) {
              $query = "UPDATE Business SET tax_identification_number = '$enteredtax_identification_number' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Tax Identification Number ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredbusiness_name)) {
              $query = "UPDATE Business SET business_name = '$enteredbusiness_name' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Business Name ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredprimary_contact_name)) {
              $query = "UPDATE Business SET primary_contact_name = '$enteredprimary_contact_name' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Primary Contact Name ... <br>" . __FILE__ . " line:" . __LINE__);
              }
          }
          if (!empty($enteredprimary_contact_title)) {
              $query = "UPDATE Business SET primary_contact_title = '$enteredprimary_contact_title' WHERE customer_id = '$enteredcustomer_id'";
              $result = mysqli_query($db, $query);
              include('lib/show_queries.php');

              if (mysqli_affected_rows($db) == -1) {
                  array_push($error_msg, "UPDATE ERROR: Primary Contact Title ... <br>" . __FILE__ . " line:" . __LINE__);
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

					<div class="features">

            <div class="Edit Customer section">
							<div class="subtitle">Edit Customer Info</div>

                            <form name = "edit_customer" action = "edit_customer.php" method="post">
                                <table>
                                    <tr>
                                        <td class ="item_label">Customer ID</td>
                                        <td>
                                            <?php echo ($enteredcustomer_id); ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Phone Number</td>
                                        <td>
                                            <input type="item_label" name= "phone_number" value="<?php if ($_GET['phone_number']) { print $_GET['phone_number']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Email</td>
                                        <td>
                                            <input type="text" name = "email" value ="<?php if($_GET['email']) {print $_GET['email'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Street Address</td>
                                        <td>
                                            <input type="text" name = "customer_street" value ="<?php if($_GET['email']) {print $_GET['email'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">City</td>
                                        <td>
                                            <input type="text" name = "customer_city" value ="<?php if($_GET['customer_city']) {print $_GET['customer_city'];}?>" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class = "item_label">State</td>
                                        <td>
                                            <input type="text" name = "customer_state" value ="<?php if($_GET['customer_state']) {print $_GET['customer_state'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Zip Code</td>
                                        <td>
                                            <input type="number" name = "customer_zip" min = "10000" max = "99999" value ="<?php if($_GET['customer_zip']) {print $_GET['customer_zip'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                      <td class = "subtitle">Enter following for a PERSON:  </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Driver License Number</td>
                                        <td>
                                            <input type="text" name = "driver_license_number" value ="<?php if($_GET['driver_license_number']) {print $_GET['driver_license_number'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">First Name</td>
                                        <td>
                                            <input type="text" name = "customer_first_name" value ="<?php if($_GET['customer_first_name']) {print $_GET['customer_first_name'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Last Name</td>
                                        <td>
                                            <input type="text" name = "customer_last_name" value ="<?php if($_GET['customer_last_name']) {print $_GET['customer_last_name'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                      <td class = "subtitle">Enter following for a BUSINESS: </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Tax Identification Number</td>
                                        <td>
                                            <input type="text" name = "tax_identification_number" value ="<?php if($_GET['tax_identification_number']) {print $_GET['tax_identification_number'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Business Name</td>
                                        <td>
                                            <input type="text" name = "business_name" value ="<?php if($_GET['business_name']) {print $_GET['business_name'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Primary Contact Name</td>
                                        <td>
                                            <input type="text" name = "primary_contact_name" value ="<?php if($_GET['primary_contact_name']) {print $_GET['primary_contact_name'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Primary Contact Title</td>
                                        <td>
                                            <input type="text" name = "primary_contact_title" value ="<?php if($_GET['primary_contact_title']) {print $_GET['primary_contact_title'];}?>" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <input name = "edit" type = "submit" id = "edit" value = "Edit">
                                    </tr>
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
