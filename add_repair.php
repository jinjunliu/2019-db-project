<?php
include('lib/common.php');
// written by zxie86

    
if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 4)) {
    header('Location: index.php');
    exit();
}
    
    $query = "SELECT login_first_name, login_last_name " .
		 " FROM Users " .
		 " WHERE Users.username = '{$_SESSION['username']}'";
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User Profile... <br>".  __FILE__ ." line:". __LINE__ );
    }
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
        /*if(empty($enteredVin)){
            header('Location: view_repair.php');
            exit();
        }
        $t = mysqli_query($db, "SELECT repair_status from Repair WHERE vin = '$enteredVin' AND repair_status != 'completed' ");
        if(mysqli_num_rows($t) > 0){//current repair has pening or in program repair
            header('Location: view_repair.php');
            exit();
        }*/
        $enteredStart_date = mysqli_real_escape_string($db, $_POST['start_date']);
        $enteredEnd_date = mysqli_real_escape_string($db, $_POST['end_date']);
        $enteredRepair_status = mysqli_real_escape_string($db, $_POST['repair_status']);
        $enteredRepair_Description = mysqli_real_escape_string($db, $_POST['repair_description']);
        $enteredVendor_name = mysqli_real_escape_string($db, $_POST['vendor_name']);
        $enteredRepair_cost = $_POST['repair_cost'];
        $enteredNHTSA_recall_campagin_Number = mysqli_real_escape_string($db, $_POST['nhtsa_recall_compaign_number']);
        $enteredInventory_clerk_permssion = mysqli_real_escape_string($db, $_POST['inventory_clerk_permission']);
        if (empty($enteredVin)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate VIN number... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredStart_date)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Start date... <br>" . __FILE__ . " line: " . __LINE__);
        }else if(!empty($enteredEnd_date) && $enteredStart_date > $enteredEnd_date){
            array_push($error_msg, "ADD ERROR: Start date could not late than End Date... <br>" . __FILE__ . " line: " . __LINE__);
        } else if (empty($enteredRepair_status)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Repair Status... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredRepair_Description)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Repair Description... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredVendor_name)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor Name... <br>" . __FILE__ . " line: " . __LINE__);
        }else if ($enteredRepair_cost <= 0) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Repair Cost... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (!empty($enteredinventory_clerk_permssion)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Inventory Clerk Permssion <br>" . __FILE__ . " line: " . __LINE__);
        }else if(empty($enteredEnd_date) && $enteredRepair_status == 'completed'){
	    array_push($error_msg, "ADD ERROR: The end date cannot be empty for a completed repair. <br>" . __FILE__ . " line: " . __LINE__);
	}else if(!empty($enteredEnd_date) && $enteredRepair_status != 'completed'){
	    array_push($error_msg, "ADD ERROR: The end date cannot be entered for a pending/in progress repair. <br>" . __FILE__ . " line: " . __LINE__);
	}else{
            if(empty($enteredEnd_date)){
                $enteredEnd_date = "2029-08-25T17:00:00";
            }
            $query = "INSERT INTO Repair (vin, start_date, repair_status, repair_description, vendor_name, repair_cost, inventory_clerk_permission, end_date)"
                    ."VALUES('$enteredVin', '$enteredStart_date', '$enteredRepair_status', ".
                "'$enteredRepair_Description', '$enteredVendor_name', '$enteredRepair_cost',".
                "'$enteredInventory_clerk_permssion', '$enteredEnd_date')";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');
            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR:  Repair Table form   <br>".  __FILE__ ." line:". __LINE__ );
            }else {
                $repair_cost = floatval($enteredRepair_cost *1.1);
                $temp_result = mysqli_query($db,"SELECT sale_price from Vehicle WHERE vin = '$enteredVin' ");
                $temp_row = mysqli_fetch_array($temp_result, MYSQLI_ASSOC);
                $previous_sale_price = $temp_row['sale_price'];
                $new_sale_price = $previous_sale_price + $repair_cost;
                $update_query = "UPDATE Vehicle SET sale_price = $new_sale_price WHERE vin = '$enteredVin' ";
                $result = mysqli_query($db, $update_query);
                include('lib/show_queries.php');
                if (mysqli_affected_rows($db) == -1) {
                    array_push($error_msg, "ADD ERROR:  Failed to update Sale Price <br>" . __FILE__ . " line:" . __LINE__);
                }
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Add Repair Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $_row['first_name'] . ' ' . $_row['last_name']; ?></div>
					<div class="features">

					    <button onclick="myFunction()">Open/Close NHTSA Recall Number List</button>
						<div class ="Check NHTSA Recall Campagin Number" id = "check_recall">
							<div class="subtitle">Please Check NHTSA Recall Campagin Number First!</div>
							<form name = "add_repair_check_NHTSA" action = "add_repair.php" method="get">
								<table>
									<tr>
										<td class ="item_label">NHTSA Recall Campagin Number</td>
										<td>
											<input type="text" name = "nhtsa_recall_compaign_number"  value ="<?php if($_GET['nhtsa_recall_compaign_number']) {print $_GET['nhtsa_recall_compaign_number'];}?>" />
										</td>
									</tr>
									<tr>
                                        <td>
                                        <input name = "check_nhtsa" type = "submit" id = "check_nhtsa" value = "Submit">
                                        <button type="reset" value="Reset">Reset</button>
                                        </td>
                                    </tr>
								</table>
							</form>
							
							<?php
								if($_SERVER['REQUEST_METHOD'] == 'GET'){
									$enteredNHTSA_recall_compaign_number = mysqli_real_escape_string($db, $_GET['nhtsa_recall_compaign_number']);
									$query  = "SELECT recall_manufacturer, recall_description, NHTSA_recall_compaign_number ";
									$from = " FROM Recall ";
									$where = "";
									if(empty($enteredNHTSA_recall_compaign_number)){
										$where = "";
										//array_push($error_msg,  "INPUT ERROR: Please input validate NHTSA Recall Compaign Number... <br>".  __FILE__ ." line:". __LINE__ );
									}else{
										$where = " WHERE NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'";
									}
									$query = $query . $from . $where;
									$result = mysqli_query($db, $query);
									include('lib/show_queries.php');
									if (mysqli_num_rows($result) == 0) {
										//array_push($error_msg,  "Query ERROR: Failed to get Recall information..." . __FILE__ ." line:". __LINE__ );
										echo "Sorry, there is no such NHTSA Information, please add this Recall information to the list first.";
										echo "<td><a href='add_recall.php?NHTSA_recall_compaign_number=".$enteredNHTSA_recall_compaign_number."'> Add Recall</a></td>";
									}else{
										echo "We have the NHTSA Information: ";
										echo "<div>";
										echo "<table>";
										echo "<tr>";
										echo "<td class=\"heading\">Recall Manufacturer</td>";
										echo "<td class=\"heading\">Recall Description</td>";
										echo "<td class=\"heading\">NHTSA Recall Compaign Number</td>";
										echo "</tr>";
										while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
											print "<tr>";
											print "<td>" . $row['recall_manufacturer'] . "</td>";
											print "<td>" . $row['recall_description'] . "</td>";
											print "<td>" . $row['NHTSA_recall_compaign_number'] . "</td>";
										}
										echo "</table>";
										echo "</div>";
									}
								}
								?>
						</div>
						
                        <div class="Add Repair section">
							<div class="subtitle">Add Repair Info</div>

                            <form name = "add_repair" action = "add_repair.php" method="post">
                                <table>

                                    <tr>
                                        <td class="item_label">Vendor Name</td>
                                        <td>
                                            <input type="text" name="vendor_name" list="vendor_name_list">
                                            <datalist id = 'vendor_name_list'>
                                                <?php
                                                foreach($VENDOR_LIST as $var) {
                                                    ?>s
                                                    <option value= '<?php echo $var;?>' <?php if ($_GET['vendor_name'] == $var) { print 'selected="true"';}else if($_POST['vendor_name'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                                    <?php
                                                }
                                                ?>
                                            </datalist>
                                            <a href='add_vendor.php' target='_blank'>Add A New Vendor</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class ="item_label">VIN Number</td>
                                        <td>
                                            <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" />
                                        </td>
                                    </tr>
					
				
                                    <tr>
                                        <td class = "item_label"> Repair Status </td>
                                        <td>
                                            <input name = "repair_status" value="pending" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Start Date</td>
                                        <td>
                                            <input type="date" name= "start_date" value="<?php if ($_GET['start_date']) { print $_GET['start_date']; } ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class ="item_label">End Date</td>
                                        <td>
                                            <input type="date" name= "end_date" value="<?php if($_GET['end_date']) {print $_GET['end_date'];} ?>";
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Repair Description</td>
                                        <td>
                                            <input type="text" name = "repair_description" value ="<?php if($_GET['repair_description']) {print $_GET['repair_description'];}?>" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class = "item_label">Repair Cost</td>
                                        <td>
                                            <input type="number" name = "repair_cost" min="0" value ="<?php if($_GET['repair_cost']) {print $_GET['repair_cost'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">NHTSA Recall Campagin Number</td>
                                        <td>
                                            <input type="text" name = "nhtsa_recall_compaign_number"  value ="<?php if($_GET['nhtsa_recall_compaign_number']) {print $_GET['nhtsa_recall_compaign_number'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Inventory Clerk Permission</td>
                                        <td>
                                            <input type="text" name = "inventory_clerk_permission" value ="<?php if($_GET['inventory_clerk_permission']) {print $_GET['inventory_clerk_permission'];}?>" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                        <input name = "add" type = "submit" id = "add" value = "Confirmed and Add!">
                                        <input type="button" value="Cancel" onclick="history.go(-1)">
                                        <button type="reset" value="Reset">Reset</button>
                                        </td>
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
		
<script>
function myFunction() {
  var x = document.getElementById("check_recall");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
		
		
</body>
</html>
