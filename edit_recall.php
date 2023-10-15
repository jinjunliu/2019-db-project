<?php

include('lib/common.php');
// written by zxie86



if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 4)) {
    header('Location: index.php');
    exit();
}


$query = "SELECT login_first_name, login_last_name" .
    "FROM Users WHERE Users.username = '{$_SESSION['username']}'";

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
    $enteredrecall_manufacturer = mysqli_real_escape_string($db, $_POST['recall_manufacturer']);
    $enteredrecall_description = mysqli_real_escape_string($db, $_POST['recall_description']);
    $enteredNHTSA_recall_compaign_number = mysqli_real_escape_string($db, $_POST['NHTSA_recall_compaign_number']);

    if (empty($enteredNHTSA_recall_compaign_number)) {
        array_push($error_msg, "Please enter a validate NHTSA Recall Compaign Number.");
    }else {
        if (!empty($enteredrecall_manufacturer)) {
            $query = "UPDATE Recall " . "SET recall_manufacturer = '$enteredrecall_manufacturer' WHERE NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Recall Manufacture Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }
        if (!empty($enteredrecall_description)) {
            $query = "UPDATE Recall " . "SET recall_description = '$enteredrecall_description' WHERE NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Recall Description Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }
    }
}//end of POST

?>


<?php include("lib/header.php"); ?>
<head>
    <title>Edit Recall Information</title>
</head>
<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['login_first_name'] . ' ' . $row['login_last_name']; ?></div>
            <div class="features">
                <div class = "profile_section">
                    <div class = "subtitle">Edit Recall Info</div>
                    <form name = "confirm_edit_repair" action = "edit_recall.php" method="post">
                        <table>
                            <tr>
                                <td class ="item_label">NHTSA Recall Compaign_number</td>
                                <td>
                                    <input type="text" id = "NHTSA_recall_compaign_number" name = "NHTSA_recall_compaign_number" value="<?php if ($_GET['NHTSA_recall_compaign_number']) { print $_GET['NHTSA_recall_compaign_number']; }else if ($_POST['NHTSA_recall_compaign_number']) { print $_POST['NHTSA_recall_compaign_number']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Recall Manufacturer</td>
                                <td>
                                    <select name="recall_manufacturer">
                                        <?php
                                            foreach($MANUFACTURER_LIST as $var) {
                                        ?>
			                      <option value= '<?php echo $var;?>' <?php if ($_GET['recall_manufacturer'] == $var) { print 'selected="true"';}else if($_POST['recall_manufacturer'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Recall Description</td>
                                <td>
                                    <input type="text" name = "recall_description" value ="<?php if($_GET['recall_description']) {print $_GET['recall_description'];}else if($_POST['recall_description']) {print $_POST['recall_description'];}?>" />
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
