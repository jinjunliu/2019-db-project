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
        $enteredNHTSA_recall_compaign_number = mysqli_real_escape_string($db, $_POST['NHTSA_recall_compaign_number']);
        if(empty($enteredNHTSA_recall_compaign_number)){
            array_push($error_msg,  "INPUT ERROR: Please input validate NHTSA Recall Compaign Number... <br>".  __FILE__ ." line:". __LINE__ );
        }else{
            $query = "DELETE FROM Recall WHERE NHTSA_recall_compaign_number = $enteredNHTSA_recall_compaign_number";
            $result = mysqli_query($db, $query);
            if(! $result){
                array_push($error_msg,  "DELETE ERROR: Failed to delete the Recall history... <br>".  __FILE__ ." line:". __LINE__ );
            }else{
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                echo "Deleted data successfully! ";
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Delete Recall Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>          
					<div class="features">   
						
                        <div class="Delete Recall section">
							<div class="subtitle">Delete Recall Info</div>
                            
							<form name="confirm_delete_recall" action="delete_recall.php" method="POST">
								<table>
									<tr>
										<td class ="item_label">NHTSA Recall Compaign Number</td>
                                        <td>
                                            <input type="text" name= "NHTSA_recall_compaign_number" value="<?php if ($_GET['NHTSA_recall_compaign_number']) {print $_GET['NHTSA_recall_compaign_number']; } else if ($_POST['NHTSA_recall_compaign_number']) { print $_POST['NHTSA_recall_compaign_number']; }else{print "ERROR! No NHTSA Recall Compaign Number information!";} ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class ="item_label">Recall Description</td>
                                        <td>
                                            <input type="text" name= "recall_description" value="<?php if ($_GET['recall_description']) {print $_GET['recall_description']; } else if ($_POST['recall_description']) { print $_POST['recall_description']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class ="item_label">Recall Manufacturer</td>
                                        <td>
                                            <input type="text" name= "recall_manufacturer" value="<?php if ($_GET['recall_manufacturer']) {print $_GET['recall_manufacturer']; } else if ($_POST['recall_manufacturer']) { print $_POST['recall_manufacturer']; } ?>" readonly/>
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
