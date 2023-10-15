<?php

include('lib/common.php');
// written by hxia40




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

        $enteredvendor_name = mysqli_real_escape_string($db, $_POST['vendor_name']);
        $enteredvendor_phone_number = mysqli_real_escape_string($db, $_POST['vendor_phone_number']);
        $enteredvendor_street = mysqli_real_escape_string($db, $_POST['vendor_street']);
        $enteredvendor_city = mysqli_real_escape_string($db, $_POST['vendor_city']);
        $enteredvendor_state = mysqli_real_escape_string($db, $_POST['vendor_state']);
        $enteredvendor_zip = mysqli_real_escape_string($db, $_POST['vendor_zip']);

        if (empty(enteredvendor_name)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor Name... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredvendor_phone_number)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor Phone Number... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredvendor_street)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor Street... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredvendor_city)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor City... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredvendor_state)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor State... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredvendor_zip)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor ZIP... <br>" . __FILE__ . " line: " . __LINE__);
        }else{
            $query = "INSERT INTO Vendor VALUES('$enteredvendor_name','$enteredvendor_phone_number','$enteredvendor_street','$enteredvendor_city','$enteredvendor_state','$enteredvendor_zip')";
            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR:  Vendor Table form   <br>".  __FILE__ ." line:". __LINE__ );
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Add New Vendor Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>          
					<div class="features">   
						
                        <div class="Add Recall section">
							<div class="subtitle">Add New Vendor Info</div>

                            <form name = "add" action = "add_vendor.php" method="post">
                                <table>
<!--					<tr>-->
<!--						<td class="item_label">Vendor Name</td>-->
<!--						<td>-->
<!--							<select name="vendor_name">-->
<!--								<option value='select' selected="true">Please select</option>-->
<!--								--><?php
//									foreach($VENDOR_LIST as $var) {
//								?>
<!--								<option value= '--><?php //echo $var;?><!--'>--><?php //echo $var;?><!--</option>-->
<!--								--><?php
//									}
//								?>
<!--							</select>-->
<!--						</td>-->
<!--					</tr>-->

                                    <tr>
                                        <td class="item_label">Vendor Name</td>
                                        <td>
                                            <input type="text" name= "vendor_name" value="<?php if ($_GET['vendor_name']) { print $_GET['vendor_name']; } ?>" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class = "item_label">Vendor Phone </td>
                                        <td>
                                            <input type="text" name = "vendor_phone_number" value="<?php if ($_GET['vendor_phone_number']) { print $_GET['vendor_phone_number']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Vendor Street</td>
                                        <td>
                                            <input type="text" name= "vendor_street" value="<?php if ($_GET['vendor_street']) { print $_GET['vendor_street']; } ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Vendor City</td>
                                        <td>
                                            <input type="text" name= "vendor_city" value="<?php if ($_GET['vendor_city']) { print $_GET['vendor_city']; } ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Vendor State</td>
                                        <td>
                                            <input type="text" name= "vendor_state" value="<?php if ($_GET['vendor_state']) { print $_GET['vendor_state']; } ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Vendor ZIP Code</td>
                                        <td>
                                            <input type="text" name= "vendor_zip" value="<?php if ($_GET['vendor_zip']) { print $_GET['vendor_zip']; } ?>" />
                                        </td>
                                    </tr>
                                    <tr>
					<td>
                                        <input name = "add" type = "submit" id = "add" value = "Confirmed and Add">
                                        <input type="button" value="Cancel" onclick="history.go(-1)">
                                        <button type="reset" value="Reset">Reset</button>
					 </td>
                                    </tr>
<!--				    <tr>-->
<!--					 <td><a href='add_repair.php?nhtsa_recall_compaign_number=--><?php //if($_GET['NHTSA_recall_compaign_number']) {print $_GET['NHTSA_recall_compaign_number'];} else if($_POST['NHTSA_recall_compaign_number']) {print $_POST['NHTSA_recall_compaign_number'];} ?><!--' >Add A New Repair of This Recall!</a></td>-->
<!--				    </tr>-->
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
