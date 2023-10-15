<?php

include('lib/common.php');
// written by zxie86

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
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $vin = mysqli_real_escape_string($db, $_POST['vin']);
        if(empty($vin)){
            array_push($error_msg,  "INPUT ERROR: Please input validate Vin number... <br>".  __FILE__ ." line:". __LINE__ );
        }else{
            $query = "DELETE FROM Vehicle WHERE vin = $vin";
            $result = mysqli_query($db, $query);
            if(! $result){
                array_push($error_msg,  "DELETE ERROR: Failed to delete the vehicle record... <br>".  __FILE__ ." line:". __LINE__ );
            }else{
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                echo "Deleted data successfully! ";
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Delete Vehicle Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>          
					<div class="features">   
						
                        <div class="Delete Vehicle section">
							<div class="subtitle">Delete Vehicle Info</div>

                            <form name = "confirm_delete_vehicle" action = "delete_vehicle.php" method="post">
                                <table>
                                    <tr>
                                        <td class ="item_label">VIN Number</td>
                                        <td>
                                            <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label"> Vehicle Mileage </td>
                                        <td>
                                            <input type="number" name= "vehicle_mileage" value="<?php if ($_GET['vehicle_mileage']) { print $_GET['vehicle_mileage']; }  ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Vehicle Description</td>
                                        <td>
                                            <input type="text" name= "vehicle_description" value="<?php if ($_GET['vehicle_description']) { print $_GET['vehicle_description']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Model Name</td>
                                        <td>
                                            <input type="text" name = "model_name" value ="<?php if($_GET['model_name']) {print $_GET['model_name'];}?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Model Year</td>
                                        <td>
                                            <input type="number" name = "model_year" value ="<?php if($_GET['model_year']) {print $_GET['model_year'];}?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Type Name</td>
                                        <td>
                                            <input type="text" name = "type_name" value ="<?php if($_GET['type_name']) {print $_GET['type_name'];}?>" readonly/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">Manufacturer Name</td>
                                        <td>
                                            <input type="text" name = "manufacturer_name" value ="<?php if($_GET['manufacturer_name']) {print $_GET['manufacturer_name'];}?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Purchase Price</td>
                                        <td>
                                            <input type="number" name = "sale_price" value ="<?php if($_GET['sale_price']) {print $_GET['sale_price'];}?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Sale Price</td>
                                        <td>
                                            <input type="number" name = "sale_price" value ="<?php $vin = $_GET['vin'];
                                            $sale_price = floatval(mysqli_query($db, "SELECT sale_price FROM Vehicle WHERE vin = $vin")); print $sale_price;?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Vehicle Color</td>
                                        <td>
                                            <ul>
                                                <?php
                                                $vin = "";
                                                if ($_GET['vin']) { $vin = $_GET['vin']; } else if($_POST['vin']){$vin = $_POST['vin'];}
                                                $query = "SELECT vehicle_color FROM VehicleColor WHERE vin= '$vin'";
                                                $result = mysqli_query($db, $query);
                                                include('lib/show_queries.php');

                                                if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get Vehicle Color... <br>" . __FILE__ ." line:". __LINE__ );
                                                }

                                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                                    print $row['vehicle_color'];
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>

                                    <tr>
                                        <input name = "delete" type = "submit" id = "delete" value = "Confirmed and Delete">
                                        <input type="button" value="Cancel" onclick="history.go(-1)">
                                        <button type="reset" value="Reset">Reset</button>
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
