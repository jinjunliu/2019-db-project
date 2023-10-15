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
        $enteredCustomer_id = mysqli_real_escape_string($db, $_POST['customer_id']);
        $enteredInventory_clerk_permission = mysqli_real_escape_string($db, $_POST['inventory_clerk_permission']);
        $enteredPurchase_date = mysqli_real_escape_string($db, $_POST['purchase_date']);
        $enteredPurchase_price = $_POST['purchase_price'];
        $enteredPurchase_condition = mysqli_real_escape_string($db, $_POST['purchase_condition']);
        $enteredKBB_value = $_POST['KBB_value'];

        if(empty($enteredVin)){
            array_push($error_msg,  "INPUT ERROR: Please input validate Vin Number... <br>".  __FILE__ ." line:". __LINE__ );
        }else{
            $query = "DELETE FROM Buy WHERE vin = $enteredVin";
            $result = mysqli_query($db, $query);
            if(! $result){
                array_push($error_msg,  "DELETE ERROR: Failed to delete the Buy history... <br>".  __FILE__ ." line:". __LINE__ );
            }else{
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                echo "Deleted data successfully! ";
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Delete Buy Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>          
					<div class="features">   
						
                        <div class="Delete Recall section">
							<div class="subtitle">Delete Buy Info</div>
                            
							<form name="confirm_delete_buy" action="delete_buy.php" method="POST">
								<table>
                                    <tr>
                                        <td class ="item_label">VIN Number</td>
                                        <td>
                                            <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label"> Customer ID </td>
                                        <td>
                                            <input type="text" name = "customer_id" value="<?php if ($_GET['customer_id']) { print $_GET['customer_id']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label"> Inventory Clerk Permission </td>
                                        <td>
                                            <input type="text" name = "inventory_clerk_permission" value="<?php if ($_GET['inventory_clerk_permission']) { print $_GET['inventory_clerk_permission']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Purchase Date</td>
                                        <td>
                                            <input type="date" name= "purchase_date" value="<?php if ($_GET['purchase_date']) { print $_GET['purchase_date']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Purchase Price</td>
                                        <td>
                                            <input type="number" name= "purchase_price" value="<?php if ($_GET['purchase_price']) { print $_GET['purchase_price']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Purchase Condition</td>
                                        <td>
                                            <input type="text" name= "purchase_condition" value="<?php if ($_GET['purchase_condition']) { print $_GET['purchase_condition']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">KBB Value</td>
                                        <td>
                                            <input type="number" name= "KBB_value" value="<?php if ($_GET['KBB_value']) { print $_GET['KBB_value']; } ?>" readonly/>
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
