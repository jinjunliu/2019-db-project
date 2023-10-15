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
    $enteredCustomer_id = mysqli_real_escape_string($db, $_POST['customer_id']);
    $enteredInventory_clerk_permission = mysqli_real_escape_string($db, $_POST['inventory_clerk_permission']);
    $enteredPurchase_date = mysqli_real_escape_string($db, $_POST['purchase_date']);
    $enteredPurchase_price = $_POST['purchase_price'];
    $enteredPurchase_condition = mysqli_real_escape_string($db, $_POST['purchase_condition']);
    $enteredKBB_value = $_POST['KBB_value'];

    if (empty($enteredVin)) {
        array_push($error_msg, "ADD ERROR: Please enter a validate VIN number... <br>" . __FILE__ . " line: " . __LINE__);
    }else{
        if (!empty($enteredCustomer_id)) {
            $query = "UPDATE Buy " . "SET customer_id = '$enteredCustomer_id' WHERE vin = '$enteredVin'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Sell Customer ID Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }
        if (!empty($enteredInventory_clerk_permission)) {
            $query = "UPDATE Buy " . "SET inventory_clerk_permission = '$enteredInventory_clerk_permission' WHERE vin = '$enteredVin'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Buy Inventory Clerk Permission Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }

        if (!empty($enteredPurchase_date)) {
            $query = "UPDATE Buy " . "SET purchase_date = '$enteredPurchase_date' WHERE vin = '$enteredVin'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Buy Purchase Date Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }

        /*if ($enteredPurchase_price > 0) {
            $previous_purchase_price = floatval(mysqli_query($db, "SELECT purchase_price FROM Buy WHERE vin = '$enteredVin' "));
            $change_purchase_price = ($enteredPurchase_price - $previous_purchase_price)*1.25;
            $previous_sale_price = floatval(mysqli_query($db, "SELECT sale_price FROM Vehicle WHERE vin = '$enteredVin' "));
            $new_sale_price = $previous_sale_price + $change_purchase_price;

            $query = "UPDATE Buy " . "SET purchase_price = $enteredPurchase_price  WHERE vin = '$enteredVin'";
            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Buy Purchase Price Error... <br>" . __FILE__ . " line:" . __LINE__);
            }else{
                $query = "UPDATE Vehicle " . "SET sale_price = $new_sale_price  WHERE vin = '$enteredVin'";
                $result = mysqli_query($db, $query);

                include('lib/show_queries.php');
                if (mysqli_affected_rows($db) == -1) {
                    array_push($error_msg, "UPDATE ERROR: Failed to update the vehicle sale price Error... <br>" . __FILE__ . " line:" . __LINE__);
                }
            }
        }*/

        if (!empty($enteredPurchase_condition)) {
            $query = "UPDATE Buy " . "SET purchase_condition = '$enteredPurchase_condition' WHERE vin = '$enteredVin'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Buy Purchase Condition Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }

        if ($enteredKBB_value > 0) {
            $query = "UPDATE Buy " . "SET KBB_value = '$enteredKBB_value' WHERE vin = '$enteredVin' ";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Buy KKB Value Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }
    }
}
?>


<?php include("lib/header.php"); ?>
<head>
    <title>Edit Buy Information</title>
</head>
<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['login_first_name'] . ' ' . $row['login_last_name']; ?></div>
            <div class="features">
                <div class = "profile_section">
                    <div class = "subtitle">Edit Buy Info</div>
                    <form name = "confirm_edit_buy" action = "edit_buy.php" method="post">
                        <table>
                            <tr>
                                <td class ="item_label">VIN Number</td>
                                <td>
                                    <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } else if ($_POST['vin']) { print $_POST['vin']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Customer ID</td>
                                <td>
                                    <input type="text" name="customer_id" list="customer_id_list">
                                    <datalist id = 'customer_id_list'>
                                        <?php
                                            foreach($CUSTOMER_ID_LIST as $var) {
                                        ?>
                                        <option value= '<?php echo $var;?>' <?php if ($_GET['customer_id'] == $var) { print 'selected="true"';}else if($_POST['customer_id'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                        <?php
                                            }
                                        ?>
                                    </datalist>
                                    <a href='add_customer.php' target='_blank'> Add A Customer </a>
                                </td>
                            </tr>


                            <tr>
                                <td class = "item_label"> Inventory Clerk Permission </td>
                                <td>
                                    <input type="text" name = "inventory_clerk_permission" value="<?php if ($_GET['inventory_clerk_permission']) { print $_GET['inventory_clerk_permission']; } else if ($_POST['inventory_clerk_permission']) { print $_POST['inventory_clerk_permission']; }?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Purchase Date</td>
                                <td>
                                    <input type="date" name= "purchase_date" value="<?php if ($_GET['purchase_date']) { print $_GET['purchase_date']; }else if ($_POST['purchase_date']) { print $_POST['purchase_date']; }  ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Purchase Price</td>
                                <td>
                                    If you want update the Purchase Price, please use the Vehicle Form.
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Purchase Condition</td>
                                <td>
                                    <select name="purchase_condition">
                                        <?php
                                            foreach($PURCHASE_CONDITION_LIST as $var) {
                                        ?>
			                                <option value= '<?php echo $var;?>' <?php if ($_GET['purchase_condition'] == $var) { print 'selected="true"';}else if($_POST['purchase_condition'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">KBB Value</td>
                                <td>
                                    <input type="number" name= "KBB_value" value="<?php if ($_GET['KBB_value']) { print $_GET['KBB_value']; }else if ($_POST['KBB_value']) { print $_POST['KBB_value']; }  ?>" />
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
