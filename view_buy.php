<?php

include('lib/common.php');
// written by zxie86

if (!isset($_SESSION['username'])) {
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

<?php include("lib/header.php"); ?>
<title>View Buy Information</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">View Buy Info</div>

                    <form name="view_buy_info" action="view_buy.php" method="get">
                        <table>
                            <tr>
                                <td class ="item_label">VIN Number</td>
                                <td>
                                    <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" />
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
                                    <input type="text" name = "inventory_clerk_permission" value="<?php if ($_GET['inventory_clerk_permission']) { print $_GET['inventory_clerk_permission']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Purchase Date</td>
                                <td>
                                    <input type="date" name= "purchase_date" value="<?php if ($_GET['purchase_date']) { print $_GET['purchase_date']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Purchase Price</td>
                                <td>
                                    <input type="number" name= "purchase_price" value="<?php if ($_GET['purchase_price']) { print $_GET['purchase_price']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Purchase Condition</td>
                                <td>
                                    <select name="purchase_condition">
                                        <option value='All'>All</option>
                                        <option value='select' selected="true">Please select</option>
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
                                    <input type="number" name= "KBB_value" value="<?php if ($_GET['KBB_value']) { print $_GET['KBB_value']; } ?>" />
                                </td>
                            </tr>
                            <tr>
				 <td>
                                <input name = "view" type = "submit" id = "view" value = "View">
                                <input type="button" value="Cancel" onclick="history.go(-1)">
				  </td>
                            </tr>

                        </table>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
            $enteredCustomer_id = mysqli_real_escape_string($db, $_POST['customer_id']);
            $enteredInventory_clerk_permission = mysqli_real_escape_string($db, $_POST['inventory_clerk_permission']);
            $enteredPurchase_date = mysqli_real_escape_string($db, $_POST['purchase_date']);
            $enteredPurchase_price = $_POST['purchase_price'];
            $enteredPurchase_condition = mysqli_real_escape_string($db, $_POST['purchase_condition']);
            $enteredKBB_value = $_POST['KBB_value'];

            $query = "";
            if(empty($enteredVin)){
                $query = "SELECT * FROM Buy ORDER BY purchase_date DESC ";
                //array_push($error_msg,  "INPUT ERROR: Please input validate  Vin number ... <br>".  __FILE__ ." line:". __LINE__ );
            }else{
                $query = "SELECT * ";
                $from = " FROM Buy ";
                $where = " WHERE vin = $enteredVin ";
                if(!empty($enteredCustomer_id)){
                    $where .= " AND customer_id = '$enteredCustomer_id'";
                }
                if(!empty($enteredInventory_clerk_permission)){
                    $where .= " AND inventory_clerk_permission = '$enteredInventory_clerk_permission' ";
                }
                if(!empty($enteredPurchase_date)){
                    $where .= " AND purchase_date = '$enteredPurchase_date' ";
                }
                if(!empty($enteredPurchase_price)){
                    $where .= " AND purchase_price = $enteredPurchase_price ";
                }
                if(!empty($enteredPurchase_condition) && $enteredPurchase_condition != 'All'){
                    $where .= " AND purchase_condition = '$enteredPurchase_condition' ";
                }
                if(!empty($enteredKBB_value)){
                    $where .= " AND KBB_value = '$enteredKBB_value' ";
                }
                $query = $query . $from . $where . " ORDER BY purchase_date DESC";
            }

            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                array_push($error_msg,  "Query ERROR: Failed to get Buy information..." . __FILE__ ." line:". __LINE__ );
            }
            echo "<div>";
            echo "<table>";
            echo "<tr>";
            echo "<td class=\"heading\">Vin</td>";
            echo "<td class=\"heading\">Customer ID</td>";
            echo "<td class=\"heading\">Inventory Clerk Permission </td>";
            echo "<td class=\"heading\">Purchase Date</td>";
            echo "<td class=\"heading\">Purchase Price</td>";
            echo "<td class=\"heading\">Purchase Condition</td>";
            echo "<td class=\"heading\">KKB Value</td>";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                print "<tr>";
                print "<td>" . $row['vin'] . "</td>";
                print "<td>" . $row['customer_id'] . "</td>";
                print "<td>" . $row['inventory_clerk_permission'] . "</td>";
                print "<td>" . $row['purchase_date'] . "</td>";
                print "<td>" . $row['purchase_price'] . "</td>";
                print "<td>" . $row['purchase_condition'] . "</td>";
                print "<td>" . $row['KBB_value'] . "</td>";
                echo "<td><a href='edit_buy.php?vin=".$row['vin']."&customer_id=".$row['customer_id']. "&KBB_value=". $row['KBB_value']. "&purchase_condition=". $row['purchase_condition']. "&purchase_price=". $row['purchase_price']. "&purchase_date=".strftime("%Y-%m-%d", strtotime($row['purchase_date'])). "&inventory_clerk_permission=" .$row['inventory_clerk_permission']."'>Edit</a></td>";
                echo "<td><a href='delete_buy.php?vin=".$row['vin']."&customer_id=".$row['customer_id']. "&KBB_value=". $row['KBB_value']. "&purchase_condition=". $row['purchase_condition']. "&purchase_price=". $row['purchase_price']. "&purchase_date=".strftime("%Y-%m-%d", strtotime($row['purchase_date'])). "&inventory_clerk_permission=" .$row['inventory_clerk_permission']."'>Delete</a></td>";
                print "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
        ?>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>

    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>
