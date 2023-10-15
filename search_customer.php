<?php
include('lib/common.php');
// written by czhang613, salesperson search customer

// setup permissino
if (!isset($_SESSION['username']) OR $_SESSION['permission'] == 3) {
	header('Location: index.php');
	exit();
}

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

/*
 query to get a list of id
 $query = "SELECT driver_license_number " .
      "FROM Person UNION " . "SELECT tax_identification_number " . "FROM Business";
 $result = mysqli_query($db, $query);
 include('lib/show_queries.php');
 $idlist = array('ID');

while ($row = mysqli_fetch_assoc($result)) {
   echo $row['driver_license_number'];
  array_push($idlist, $row['driver_license_number']);
   echo "<br>";
}
 var_dump($idlist);

 $typelist = array('Person', 'Business');
 var_dump($typelist);
 $entered_id = 'DL1234567';
*/

/* if form was submitted, then execute query to search for vehicles */

$enteredVIN = $_GET['vin'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$entered_id = mysqli_real_escape_string($db, $_POST['id']);

	if($entered_id == "(input customer ID)") {
		$entered_id = '';
	}

  $query ="SELECT customer_id FROM Person WHERE driver_license_number = '$entered_id'";
  $result = mysqli_query($db, $query);
  include('lib/show_queries.php');
  $result_p = $result;
  $row_p = mysqli_fetch_assoc($result_p);

  $query ="SELECT customer_id FROM Business WHERE tax_identification_number = '$entered_id'";
  $result = mysqli_query($db, $query);
  include('lib/show_queries.php');
  $result_b = $result;
  $row_b = mysqli_fetch_assoc($result_b);
}
?>

<?php include("lib/header.php"); ?>
<title>Customer Search</title>
</head>
<body>
    <div id="main_container">
<?php include("lib/menu.php"); ?>
        <div class="center_content">
			<div class="center_left">
                <div class="features">

					<div class="profile_section">
						<div class="subtitle">Search Customer</div>
						<form name="searchform" action="search_customer.php?vin=<?php echo $enteredVIN;?>" method="POST">
                            <table>

                                <tr>
									<td class="item_label">Enter ID</td>
									<td><input type="text" name="id" value="(input customer ID)"
										onclick="if(this.value=='(input customer ID)'){this.value=''}"
										onblur="if(this.value==''){this.value='(input customer ID)'}"/></td>
								</tr>

							</table>
							<a href="javascript:searchform.submit();" class="fancy_button">Search</a>
						</form>
					</div>

				    <div class='profile_section'>
					    <div class='subtitle'>Search Results</div>
                        <?php
												// when sell vehicle: vin was entered
												if (!empty($enteredVIN)) {
													if(!empty($row_p)) {
															echo "Existing Customer!<br>";
															print "<tr>";
															$get_url1="sale_order.php?vin={$enteredVIN}&customer_id={$row_p['customer_id']}";
															// $get_url1="edit_customer.php?vin={$enteredVIN}&customer_id={$row_p['customer_id']}";
															print "<td><a href={$get_url1}>Sell This Car</a></td>";
															// print "<td><a href={$get_url4}>Edit Customer</a></td>";
															print "</tr>";
													}
													else if(!empty($row_b)) {
															echo "Existing Customer!<br>";
															print "<tr>";
															$get_url2="sale_order.php?vin={$enteredVIN}&customer_id={$row_b['customer_id']}";
															print "<td><a href={$get_url2}>Sell This Car</a></td>";
															print "</tr>";

													}else if (!empty($entered_id)) {
														// echo $entered_id;
														// var_dump($idlist);
															echo "New Customer!<br>";
															print "<tr>";
															$get_url3="add_customer.php?vin={$enteredVIN}";
															print "<td><a href={$get_url3}>Add New Customer</a></td>";
															print "</tr>";
													}
												}
												// when add vehicle: no vin was entered
												if (empty($enteredVIN)) {
													if(!empty($row_p)) {
															echo "Existing Customer!<br>";
															print "<tr>";
															$get_url1="add_vehicle.php?vin={$enteredVIN}&customer_id={$row_p['customer_id']}";
															// $get_url1="edit_customer.php?vin={$enteredVIN}&customer_id={$row_p['customer_id']}";
															print "<td><a href={$get_url1}>Add Vehicle</a></td>";
															// print "<td><a href={$get_url4}>Edit Customer</a></td>";
															print "</tr>";
													}
													else if(!empty($row_b)) {
															echo "Existing Customer!<br>";
															print "<tr>";
															$get_url2="add_vehicle.php?vin={$enteredVIN}&customer_id={$row_b['customer_id']}";
															print "<td><a href={$get_url2}>Add Vehicle</a></td>";
															print "</tr>";

													}else if (!empty($entered_id)) {
														// echo $entered_id;
														// var_dump($idlist);
															echo "New Customer!<br>";
															print "<tr>";
															$get_url3="add_customer.php?vin={$enteredVIN}";
															print "<td><a href={$get_url3}>Add Customer</a></td>";
															print "</tr>";
													}
												}



                        ?>
				    </div>
                </div>
            </div>
        <?php include("lib/error.php"); ?>
		<div class="clear"></div>
	</div>

	<?php include("lib/footer.php"); ?>
</body>
</html>
