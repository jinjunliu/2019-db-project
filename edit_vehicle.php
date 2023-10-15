<?php

include('lib/common.php');
// written by zxie86


    if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 4)) {
        header('Location: index.php');
        exit();
    }

$query = "SELECT login_first_name, login_last_name " .
    " FROM Users WHERE Users.username = '{$_SESSION['username']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if(!is_bool($result) && (mysqli_num_rows($result) > 0)){
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    $user_name = $row['login_first_name'] . " " . $row['login_last_name'];
}else{
    array_push($error_msg, "Query Error: Failed to get User profile... <br>" . __FILE__ . "line:". __LINE__);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
    $enteredvehicle_mileage =  $_POST['vehicle_mileage'];
    $enteredvehicle_description = mysqli_real_escape_string($db, $_POST['vehicle_description']);
    $enteredmodel_name = mysqli_real_escape_string($db, $_POST['model_name']);
    $enteredmodel_year = $_POST['model_year'];
    $enteredtype_name = mysqli_real_escape_string($db, $_POST['type_name']);
    $enteredmanufacturer_name = mysqli_real_escape_string($db, $_POST['manufacturer_name']);
    $enteredsale_price = $_POST['sale_price'];
    $enteredVehicle_Color = $_POST['vehicle_color'];

    if (empty($enteredVin)) {
        array_push($error_msg, "Please enter a validate VIN number.");
    }

    if (!empty($enteredvehicle_mileage)) {
        $query = "UPDATE Vehicle " . "SET vehicle_mileage = '$enteredvehicle_mileage' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Vehicle Mileage Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredvehicle_description)) {
        $query = "UPDATE Vehicle " . "SET vehicle_description = '$enteredvehicle_description' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: End Date Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredmodel_name)) {
        $query = "UPDATE Vehicle " . "SET model_name = '$enteredmodel_name' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Model Name Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredmodel_year)) {
        $query = "UPDATE Vehicle " . "SET model_year = '$enteredmodel_year' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Model Year Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredVendor_name)) {
        $query = "UPDATE Vehicle " . " SET manufacturer_name = '$enteredmanufacturer_name' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Manufacturer Name Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if ($enteredsale_price > 0) {
        $temp_result1 = mysqli_query($db, "SELECT purchase_price from Buy WHERE vin = '$enteredVin'");
        $temp_row1 = mysqli_fetch_array($temp_result1, MYSQLI_ASSOC);
        $previous_purchase_price = $temp_row1['purchase_price'];
        $change_purchase_price = floatval($enteredsale_price - $previous_purchase_price)*1.25;

        $temp_result2 = mysqli_query($db, "SELECT sale_price from Vehicle WHERE vin = '$enteredVin'");
        $temp_row2 = mysqli_fetch_array($temp_result2, MYSQLI_ASSOC);
        $previous_sale_price = $temp_row2['sale_price'];
        $new_sale_price = $previous_sale_price + $change_purchase_price;

        $query = "UPDATE Vehicle " . " SET sale_price = $new_sale_price WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);

        include('lib/show_queries.php');
        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Sale Price Error... <br>" . __FILE__ . " line:" . __LINE__);
        }else{
            mysqli_query($db, "UPDATE Buy SET purchase_price = $enteredsale_price WHERE vin = '$enteredVin'");
            include('lib/show_queries.php');
        }
    }

    if(!empty($enteredVehicle_Color)){
        $query = "INSERT INTO VehicleColor (vin, vehicle_color) VALUES ('$enteredVin', '$enteredVehicle_Color') ";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "INSERT ERROR: Vehicle Color  Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }
}//end of POST


if (!empty($_GET['delete_color'])) {
    $color = mysqli_real_escape_string($db, $_GET['delete_color']);
    $vin = mysqli_real_escape_string($db, $_GET['vin']);

    $query = "DELETE FROM VehicleColor " .
        "WHERE vin = '$vin' " .
        "AND vehicle_color = '$color'";

    $result = mysqli_query($db, $query);

    include('lib/show_queries.php');

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg, "DELETE ERROR:  Vehicle Color <br>".  __FILE__ ." line:". __LINE__ );
    }
}
?>

<?php include("lib/header.php"); ?>
<head>
    <title>Edit Vehicle Info</title>
</head>
<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['login_first_name'] . ' ' . $row['login_last_name']; ?></div>
            <div class="features">
                <div class = "profile_section">
                    <div class = "subtitle">Edit Vehicle Info</div>
                    <form name = "confirm_edit_vehicle" action = "edit_vehicle.php" method="post">
                        <table>
                            <tr>
                                <td class ="item_label">VIN Number</td>
                                <td>
                                    <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } else if($_POST['vin']) { print $_POST['vin']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label"> Vehicle Mileage </td>
                                <td>
                                    <input type="number" name= "vehicle_mileage" value="<?php if ($_GET['vehicle_mileage']) { print $_GET['vehicle_mileage']; }else if  ($_POST['vehicle_mileage']) { print $_POST['vehicle_mileage'];} ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Vehicle Description</td>
                                <td>
                                    <input type="text" name= "vehicle_description" value="<?php if ($_GET['vehicle_description']) { print $_GET['vehicle_description']; } else if ($_POST['vehicle_description']) { print $_POST['vehicle_description']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Model Name</td>
                                <td>
                                    <input type="text" name="model_name" list="model_name_list" value = "<?php if ($_GET['model_name']) { print $_GET['model_name'];}else if($_POST['model_name']){print $_POST['model_name'];} ?>" >
                                    <datalist id = "model_name_list">
                                        <?php
                                            foreach($MODEL_NAME_LIST as $var) {
                                        ?>
                                        <option value= '<?php echo $var;?>' ><?php echo $var;?></option>
                                        <?php
                                            }
                                        ?>
                                    </datalist>
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Model year</td>
                                <td>
                                    <input type="number" name="model_year" list="model_year_list" min = "1900" max = "2020" value = "<?php if ($_GET['model_year'] ) { print $_GET['model_year'];} else if($_POST['model_year']){print $_POST['model_year'];} ?>">
                                    <datalist id="model_year_list">
                                        <?php
                                            for($n_year=2020; $n_year>=1900; $n_year--) {
                                        ?>
                                        <option value= '<?php echo $n_year;?>' ><?php echo $n_year;?></option>
                                        <?php
                                            }
                                        ?>
                                    </datalist>
                                </td>
                             </tr>


                            <tr>
                                <td class="item_label">Vehicle Type</td>
                                <td>
                                    <select name="type_name">
                                        <?php
                                            foreach($VEHICLE_TYPES_LIST as $var) {
                                        ?>
                                        <option value= '<?php echo $var;?>' <?php if ($_GET['type_name'] == $var) { print 'selected="true"';}else if($_POST['type_name'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="item_label">Manufacturer Name</td>
                                <td>
                                    <select name="manufacturer_name">
                                        <?php
                                            foreach($MANUFACTURER_LIST as $var) {
                                        ?>
                                        <option value= '<?php echo $var;?>' <?php if ($_GET['manufacturer_name'] == $var) { print 'selected="true"';}else if($_POST['manufacturer_name'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Purchase Price (Sale Price will be automatically calculated)</td>
                                <td>
                                    <input type="number" name = "sale_price" value ="<?php if($_GET['sale_price']) {print $_GET['sale_price'];} else if($_POST['sale_price']) {print $_POST['sale_price'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Vehicle Color</td>
                                <td>
                                    <ul>
                                        <?php
                                        $vin = "";
                                        if ($_GET['vin']) { $vin = $_GET['vin']; } else if($_POST['vin']){$vin = $_POST['vin'];}
                                        $vehicle_mileage = "";
                                        if ($_GET['vehicle_mileage']) { $vehicle_mileage = $_GET['vehicle_mileage']; } else if($_POST['vehicle_mileage']){$vehicle_mileage = $_POST['vehicle_mileage'];}
                                        $model_name = "";
                                        if ($_GET['model_name']) { $model_name = $_GET['model_name']; } else if($_POST['model_name']){$model_name = $_POST['model_name'];}
                                        $model_year = "";
                                        if ($_GET['model_year']) { $model_year = $_GET['model_year']; } else if($_POST['model_year']){$model_year = $_POST['model_year'];}
                                        $type_name = "";
                                        if ($_GET['type_name']) { $type_name = $_GET['type_name']; } else if($_POST['type_name']){$type_name = $_POST['type_name'];}
                                        $manufacturer_name = "";
                                        if ($_GET['manufacturer_name']) { $manufacturer_name = $_GET['manufacturer_name']; } else if($_POST['manufacturer_name']){$manufacturer_name = $_POST['manufacturer_name'];}
                                        $temp_sale_price = $_GET['sale_price'];
                                        if ($_GET['sale_price']) { $temp_sale_price = $_GET['sale_price']; } else if($_POST['sale_price']){$temp_sale_price = $_POST['sale_price'];}
                                        $vehicle_description = "";
                                        if ($_GET['vehicle_description']) { $vehicle_description = $_GET['vehicle_description']; } else if($_POST['vehicle_description']){$vehicle_description = $_POST['vehicle_description'];}

                                        $query = "SELECT vehicle_color FROM VehicleColor WHERE vin= '$vin'";
                                        $result = mysqli_query($db, $query);
                                        include('lib/show_queries.php');

                                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                            array_push($error_msg,  "Query ERROR: Failed to get Vehicle Color... <br>" . __FILE__ ." line:". __LINE__ );
                                        }

                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            print "<li>{$row['vehicle_color']} <a href='edit_vehicle.php?vehicle_description=".$vehicle_description."&sale_price=".$temp_sale_price."&manufacturer_name=".$manufacturer_name."&type_name=".$type_name."&model_year=".$model_year."&model_name=".$model_name."&vehicle_mileage=".$vehicle_mileage."&vin=".$vin."&delete_color=" .
                                                urlencode($row['vehicle_color']) . "'>delete</a></li>";
                                        }
                                        ?>
                                        <li><input type="text" name="vehicle_color" onclick="if(this.value=='(add color)'){this.value=''}"
                                                   onblur="if(this.value==''){this.value='(add color)'}" list="color_list"/>
                                            <datalist id = 'color_list'>
                                                <?php
                                                foreach($COLORS_LIST as $var) {
                                                    ?>
                                                    <option value= '<?php echo $var;?>' <?php if ($_GET['vehicle_color'] == $var) { print 'selected="true"';}else if($_POST['vehicle_color'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                                    <?php
                                                }
                                                ?>
                                            </datalist>

                                        </li>
                                    </ul>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                <input name = "edit" type = "submit" id = "edit" value = "Confirmed and Edit">
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
</div>

<?php include("lib/footer.php"); ?>
</body>

</html>
