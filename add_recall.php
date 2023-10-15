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

        $enteredrecall_manufacturer = mysqli_real_escape_string($db, $_POST['recall_manufacturer']);
        $enteredrecall_description = mysqli_real_escape_string($db, $_POST['recall_description']);
        $enteredNHTSA_recall_compaign_number = mysqli_real_escape_string($db, $_POST['NHTSA_recall_compaign_number']);

        if (empty($enteredrecall_manufacturer)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Recall manufacturer... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredrecall_description)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Recall description... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredNHTSA_recall_compaign_number)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate NHTSA Recall Compaign Number... <br>" . __FILE__ . " line: " . __LINE__);
        }else{
            $query = "INSERT INTO Recall VALUES('$enteredrecall_manufacturer','$enteredrecall_description','$enteredNHTSA_recall_compaign_number')";
            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR:  Recall Table form   <br>".  __FILE__ ." line:". __LINE__ );
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Add Recall Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>          
					<div class="features">   
						
                        <div class="Add Recall section">
							<div class="subtitle">Add Recall Info</div>

                            <form name = "add" action = "add_recall.php" method="post">
                                <table>
					<tr>
						<td class="item_label">Recall Manufacturer</td>
						<td>
							<select name="recall_manufacturer">
								<option value='select' selected="true">Please select</option>
								<?php
									foreach($MANUFACTURER_LIST as $var) {
								?>
								<option value= '<?php echo $var;?>'><?php echo $var;?></option>
								<?php
									}
								?>
							</select>
						</td>
					</tr>

                                    <tr>
                                        <td class = "item_label">Recall Description </td>
                                        <td>
                                            <input type="text" name = "recall_description" value="<?php if ($_GET['recall_description']) { print $_GET['recall_description']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">NHTSA Recall Compaign Number</td>
                                        <td>
                                            <input type="text" name= "NHTSA_recall_compaign_number" value="<?php if ($_GET['NHTSA_recall_compaign_number']) { print $_GET['NHTSA_recall_compaign_number']; } ?>" />
                                        </td>
                                    </tr>
                                    <tr>
					<td>
                                        <input name = "add" type = "submit" id = "add" value = "Confirmed and Add">
                                        <input type="button" value="Cancel" onclick="history.go(-1)">
                                        <button type="reset" value="Reset">Reset</button>
					 </td>
                                    </tr>
				    <tr>
					 <td><a href='add_repair.php?nhtsa_recall_compaign_number=<?php if($_GET['NHTSA_recall_compaign_number']) {print $_GET['NHTSA_recall_compaign_number'];} else if($_POST['NHTSA_recall_compaign_number']) {print $_POST['NHTSA_recall_compaign_number'];} ?>' >Add A New Repair of This Recall!</a></td>
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
