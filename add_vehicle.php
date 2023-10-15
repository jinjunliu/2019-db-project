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
    $enteredvehicle_mileage = intval($_POST['vehicle_mileage']);
    $enteredvehicle_description = mysqli_real_escape_string($db, $_POST['vehicle_description']);
    $enteredmodel_name = mysqli_real_escape_string($db, $_POST['model_name']);
    $enteredmodel_year = intval($_POST['model_year']);
    $enteredtype_name = mysqli_real_escape_string($db, $_POST['type_name']);
    $enteredmanufacturer_name = mysqli_real_escape_string($db, $_POST['manufacturer_name']);
    $enteredsale_price = floatval($_POST['sale_price']);

    if (empty($enteredVin)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate VIN number... <br>" . __FILE__ . " line: " . __LINE__);
    }else if (empty($enteredvehicle_mileage)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate Vehicle Mileage... <br>" . __FILE__ . " line: " . __LINE__);
    }else if (empty($enteredvehicle_description)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate Vehicle Description... <br>" . __FILE__ . " line: " . __LINE__);
    }else if (empty($enteredmodel_name)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate Model Name... <br>" . __FILE__ . " line: " . __LINE__);
    }else if (empty($enteredmodel_year)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate Model Year... <br>" . __FILE__ . " line: " . __LINE__);
    }else if (empty($enteredtype_name)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate Type Name... <br>" . __FILE__ . " line: " . __LINE__);
    }else if (empty($enteredmanufacturer_name)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate Manufacturer Name... <br>" . __FILE__ . " line: " . __LINE__);
    }else if ($enteredsale_price < 0) {
        array_push($error_msg, "ADD ERROR: Please enter a validate Sale Price <br>" . __FILE__ . " line: " . __LINE__);
    }else{
        $sale_new_price = $enteredsale_price * 1.25;
        //$total_repair_cost = mysqli_query($db, "SELECT SUM(repair_cost) FROM Repair WHERE vin = $enteredVin");
        /*if(!empty($total_repair_cost)){
            $sale_new_price = $sale_new_price + floatval($total_repair_cost)*1.1;
        }*/
        $query = "INSERT INTO Vehicle ".
            "VALUES('$enteredVin', $enteredvehicle_mileage, '$enteredvehicle_description', ".
            "'$enteredmodel_name', $enteredmodel_year, '$enteredtype_name',".
            "'$enteredmanufacturer_name', $sale_new_price )";
        $result = mysqli_query($db, $query);

        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "ADD ERROR: Vehicle Table form   <br>".  __FILE__ ." line:". __LINE__ );
        }
    }
}
?>


<?php include("lib/header.php"); ?>
<title>Add Vehicle Info</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>
            <div class="features">

                <div class="Add Vehicle section">
                    <div class="subtitle">Add Vehicle Info</div>

                    <form name = "add" action = "add_vehicle.php" method="post">
                        <table>
                            <tr>
                                <td class ="item_label">VIN Number</td>
                                <td>
                                    <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } else if($_POST['vin']) { print $_POST['vin'];} ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label"> Vehicle Mileage </td>
                                <td>
                                    <input type="number" name= "vehicle_mileage" value="<?php if ($_GET['vehicle_mileage']) { print $_GET['vehicle_mileage']; }else if($_POST['vehicle_mileage']) { print $_POST['vehicle_mileage'];} ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Vehicle Description</td>
                                <td>
                                    <input type="text" name= "vehicle_description" value="<?php if ($_GET['vehicle_description']) { print $_GET['vehicle_description']; } else if($_POST['vehicle_description']) { print $_POST['vehicle_description'];}?>" />
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="item_label">Model year</td>
                                <td>
                                    <input type="number" name="model_year" list="model_year_list" min = "1900" max = "2020" value = "<?php if ($_GET['model_year']) { print $_GET['model_year'];}else if($_POST['model_year']){print $_POST['model_year'];} ?>">
                                    <datalist id="model_year_list">
                                        <option value=0 >Please select</option>
                                        <?php
                                            for($n_year=2020; $n_year>=1900; $n_year--) {
                                        ?>
                                        <option value= '<?php echo $n_year;?>'><?php echo $n_year;?></option>
                                        <?php
                                            }
                                        ?>
                                    </datalist>
                                </td>
                             </tr>

                            <tr>
                                <td class="item_label">Model Name</td>
                                <td>
                                    <input type="text" name="model_name" value = "<?php if ($_GET['model_name']) { print $_GET['model_name'];}else if($_POST['model_name']){print $_POST['model_name'];} ?>" list="model_name_list">
                                    <datalist id = 'model_name_list'>
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
                                <td class="item_label">Vehicle Type</td>
                                <td>
                                    <select name="type_name">
                                        <option value='select' selected="true">Please select</option>
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
                                        <option value='select' selected="true">Please select</option>
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
                                <td class = "item_label">Purchase Price (Sale price will automatically be calculated)</td>
                                <td>
                                    <input type="number" name = "sale_price" value ="<?php if($_GET['sale_price']) {print $_GET['sale_price'];}else if($_POST['sale_price']) { print $_POST['sale_price'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Vehicle Color</td>
                                <td>
                                    If you want to add Vehicle Color, please first click 'Submit Vehicle Info' and then Click the following link:
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
                                        $sale_price_tmp = $_POST['sale_price'];
                                        if ($_GET['sale_price']) { $sale_price = $_GET['sale_price']; } else if($_POST['sale_price']){$sale_price = $_POST['sale_price'];}
                                        $vehicle_description = "";
                                        if ($_GET['vehicle_description']) { $vehicle_description = $_GET['vehicle_description']; } else if($_POST['vehicle_description']){$vehicle_description = $_POST['vehicle_description'];}
                                        echo "<a href='edit_vehicle.php?vin=".$vin."&vehicle_description=".$vehicle_description."&manufacturer_name=".$manufacturer_name."&type_name=".$type_name."&model_year=".$model_year."&model_name=".$model_name."&vehicle_mileage=".$vehicle_mileage."' target=\"_blank\">Add Color(s) for this Vehicle</a>";

                                    ?>
                                </td>
                            </tr>

                            <tr>
                               
                                <td><input name = "add" type = "submit" id = "add" value = " Submit Vehicle Info ">
                                <input type="button" value=" Cancel " onclick="history.go(-1)">
                                <button type="reset" value=" Reset ">Reset</button></td>
                            <tr>
                                <td><a href='add_buy.php?customer_id=<?php if($_GET['customer_id']) {print $_GET['customer_id'];} else if($_POST['customer_id']) {print $_POST['customer_id'];} ?>&vin=<?php if ($_GET['vin']) { print $_GET['vin']; } else if($_POST['vin']) { print $_POST['vin'];} ?>&sale_price=<?php if($_GET['sale_price']) {print $_GET['sale_price'];} else if($_POST['sale_price']) {print $_POST['sale_price'];} ?>' target="_blank">Add Buy Information!</a></td>
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
