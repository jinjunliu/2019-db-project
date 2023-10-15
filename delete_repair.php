<?php

include('lib/common.php');
#written by zixe86


if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 4)) {
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

<?php

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $enteredVin = mysqli_real_escape_string($db, $_GET['vin']);
        $enteredStart_date = mysqli_real_escape_string($db, $_GET['start_date']);

        if(empty($enteredVin) || empty($enteredStart_date)){
            header('Location: view_repair.php');
            exit();
        }

        $t = mysqli_query($db, "SELECT repair_status from Repair WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date' AND repair_status = 'completed' ");
        if(mysqli_num_rows($t) > 0){//current repair is completed
            header('Location: view_repair.php');
            exit();
        }
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $vin = mysqli_real_escape_string($db, $_POST['vin']);
        $start_date = mysqli_real_escape_string($db, $_POST['start_date']);

        if(empty($vin) || empty($start_date)){
            header('Location: view_repair.php');
            exit();
        }

        $t = mysqli_query($db, "SELECT repair_status from Repair WHERE vin = '$vin' AND start_date = '$start_date' AND repair_status = 'completed' ");
        if(mysqli_num_rows($t) > 0){//current repair is completed
            header('Location: view_repair.php');
            exit();
        }

        if(empty($vin) || empty($start_date)){
            array_push($error_msg,  "INPUT ERROR: Please input validate start time and Vin number... <br>".  __FILE__ ." line:". __LINE__ );
        }else{
            $previous_repair_cost = $_POST['repair_cost'] * 1.1;
            //echo $change_repair_cost;

            $temp_result2 = mysqli_query($db, "SELECT sale_price FROM Vehicle WHERE vin = '$vin' ");
            $temp_row2 = mysqli_fetch_array($temp_result2, MYSQLI_ASSOC);
            $previous_sale_price = $temp_row2['sale_price'];
            //echo $previous_sale_price;

            $new_sale_price = $previous_sale_price - $previous_repair_cost;

            $query = "DELETE FROM Repair WHERE vin = '$vin' AND start_date = '$start_date' ";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if(mysqli_affected_rows($db) == -1){
                array_push($error_msg,  "DELETE ERROR: Failed to delete the repair history... <br>".  __FILE__ ." line:". __LINE__ );
            }else{
                $query = "UPDATE Vehicle " . " SET sale_price = $new_sale_price WHERE vin = '$vin'" ;
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');

                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                echo "Deleted data successfully! ";

            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Delete Repair Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>          
					<div class="features">   
						
                        <div class="Delete Repair section">
							<div class="subtitle">Delete Repair Info</div>
                            
							<form name="confirm_delete_repair" action="delete_repair.php" method="post">
								<table>
									<tr>
										<td>Vin Number</td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <input type="text" name= "vin" value="<?php if ($_GET['vin']) {print $_GET['vin']; } else if ($_POST['vin']) { print $_POST['vin']; }else{print "ERROR! No Vin Number information!";} ?>" readonly/>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td>Start Date</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="date" name= "start_date" value="<?php if ($_GET['start_date']) { print $_GET['start_date'];} else if ($_POST['start_date']) { print $_POST['start_date'];} else {print "ERROR! No Start Date information!";} ?>" readonly/>
                                        </td>
                                    </tr>

                                    <td>End Date</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name = "end_date" type = "date" id = "end_date" value="<?php if ($_GET['end_date']) { print $_GET['end_date']; }  else if ($_POST['end_date']) { print $_POST['end_date'];} else {print "ERROR! No End Date information!";} ?>" readonly/>
                                        </td>
                                    </tr>

                                    <td> Repair Status </td>
                                    <tr>

                                        <td>
                                            <input name = "repair_status" type = "text" id = "repair_status" value="<?php if ($_GET['repair_status']) { print $_GET['repair_status']; }  else if ($_POST['repair_status']) { print $_POST['repair_status'];} else {print "ERROR! No Repair Status information!";} ?>" readonly/>
                                        </td>
                                    </tr>

                                    <td>Repair Description</td>
                                    <tr>

                                        <td>
                                            <input name = "repair_description" type = "text" id = "repair_description" value="<?php if ($_GET['repair_description']) { print $_GET['repair_description']; }  else if ($_POST['repair_description']) { print $_POST['repair_description'];} else {print "ERROR! No Repair Description information!";} ?>" readonly/>
                                        </td>
                                    </tr>

                                    <td>Vendor Name</td>
                                    <tr>

                                        <td>
                                            <input name = "vendor_name" type = "text" id = "vendor_name" value="<?php if ($_GET['vendor_name']) { print $_GET['vendor_name']; }  else if ($_POST['vendor_name']) { print $_POST['vendor_name'];} else {print "ERROR! No Vendor Name information!";} ?>" readonly/>
                                        </td>
                                    </tr>

                                    <td>Repair Cost</td>
                                    <tr>

                                        <td>
                                            <input name = "repair_cost" type = "number" id = "repair_cost" value="<?php if ($_GET['repair_cost']) { print $_GET['repair_cost']; }  else if ($_POST['repair_cost']) { print $_POST['repair_cost'];} else {print "ERROR! No Repair Cost information!";} ?>" readonly/>
                                        </td>
                                    </tr>

                                    <td>NHTSA Recall Compaign Number</td>
                                    <tr>

                                        <td>
                                            <input name = "nhtsa_recall_compaign_number" type = "text" id = "nhtsa_recall_compaign_number" value="<?php if ($_GET['nhtsa_recall_compaign_number']) { print $_GET['repair_cost']; }  else if ($_POST['nhtsa_recall_compaign_number']) { print $_POST['nhtsa_recall_compaign_number'];} else {print "ERROR! No nhtsa_recall_compaign_number information!";} ?>" readonly/>
                                        </td>
                                    </tr>


                                    <tr>
                                        <input name = "delete" type = "submit" id = "delete" value = "Confirmed and Delete">
                                        <input type="button" value="Cancel" onclick="history.go(-1)">
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
