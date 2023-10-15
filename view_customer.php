<?php
include('lib/common.php');
// written by czhang613
    $enteredcustomer_id = 'Rich102';
    $query = "SELECT Customer.customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip, driver_license_number, customer_first_name, customer_last_name, tax_identification_number, business_name, primary_contact_name, primary_contact_title
    FROM Customer
    LEFT OUTER JOIN Person ON Customer.customer_id = Person.customer_id
    LEFT OUTER JOIN Business ON Customer.customer_id = Business.customer_id
    WHERE Customer.customer_id = '$enteredcustomer_id'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');

    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to fetch Vehicle Detail Information... <br>".  __FILE__ ." line:". __LINE__ );
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
					<!-- <div class="title_name"><?php //print $row['first_name'] . ' ' . $row['last_name']; ?></div> -->
					<div class="features">
                           <div class="View customer section">
							<div class="subtitle">View Customer Detail</div>

              <table>
                  <tr>
                      <td class="item_label">Customer ID</td>
                      <td>
                          <?php print $row['customer_id']; ?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Phone Number</td>
                      <td>
                          <?php print $row['phone_number'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Email</td>
                      <td>
                          <?php print $row['email'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Street Address</td>
                      <td>
                          <?php print $row['customer_street'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">City</td>
                      <td>
                          <?php print $row['customer_city'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">State</td>
                      <td>
                          <?php print $row['customer_state'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Zip Code</td>
                      <td>
                          <?php print $row['customer_zip'];?>
                      </td>
                  </tr>
                  <?php
                  if (!empty($row['driver_license_number']) && empty($row['tax_identification_number'])) {
                    // print person table
                    echo "<tr><td class=\"item_label\">Driver License Number</td><td>";
                    echo $row['driver_license_number'];
                    echo "</td></tr>";
                    echo "<tr><td class=\"item_label\">First Name</td><td>";
                    echo $row['customer_first_name'];
                    echo "</td></tr>";
                    echo "<tr><td class=\"item_label\">Last Name</td><td>";
                    echo $row['customer_last_name'];
                    echo "</td></tr>";
                  }
                  else if (empty($row['driver_license_number']) && !empty($row['tax_identification_number'])) {
                    // print person table
                    echo "<tr><td class=\"item_label\">Tax Identification Number</td><td>";
                    echo $row['tax_identification_number'];
                    echo "</td></tr>";
                    echo "<tr><td class=\"item_label\">Business Name</td><td>";
                    echo $row['business_name'];
                    echo "</td></tr>";
                    echo "<tr><td class=\"item_label\">Primary Contact Name</td><td>";
                    echo $row['primary_contact_name'];
                    echo "</td></tr>";
                    echo "<tr><td class=\"item_label\">Primary Contact Title</td><td>";
                    echo $row['primary_contact_title'];
                    echo "</td></tr>";
                  }

                  // pass data through url
                  print "<tr>";
                  $get_url="edit_customer.php?customer_id={$row['customer_id']}";
                  print "<td><a href={$get_url}>Edit Customer</a></td>";
                  print "</tr>";
                  // print "<tr> <a href='edit_customer.php'>Edit Customer</a></tr>";
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
