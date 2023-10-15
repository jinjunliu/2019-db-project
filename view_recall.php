<?php

include('lib/common.php');
// written by zxie86



if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 3 &&$_SESSION['permission'] != 4)) {
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

<?php include("lib/header.php"); ?>
<title>View Recall Info</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>
            <div class="features">

                <div class="View Recall section">
                    <div class="subtitle">View Recall Info</div>

                    <form name="profileform" action="view_recall.php" method="GET">
                        <table>
                            <tr>
                                <td>NHTSA Recall Compaign Number</td>
                                <td>
                                    <input name = "NHTSA_recall_compaign_number" type = "text" list = "NHTSA_list" value="<?php if ($_GET['NHTSA_recall_compaign_number']) { print $_GET['NHTSA_recall_compaign_number']; } ?>">
                                    <datalist id = 'NHTSA_list'>
                                        <?php
                                        foreach($NHTSA_LIST as $var) {
                                            ?>
                                            <option value= '<?php echo $var;?>' <?php if ($_GET['NHTSA_recall_compaign_number'] == $var) { print 'selected="true"';}else if($_POST['NHTSA_recall_compaign_number'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                            <?php
                                        }
                                        ?>
                                    </datalist>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                <input name = "view" type = "submit" id = "view" value = "View">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $enteredNHTSA_recall_compaign_number = mysqli_real_escape_string($db, $_GET['NHTSA_recall_compaign_number']);
            $query  = "SELECT recall_manufacturer, recall_description, NHTSA_recall_compaign_number ";
            $from = " FROM Recall ";
            $where = "";
            if(empty($enteredNHTSA_recall_compaign_number)){
                $where = "";
                //array_push($error_msg,  "INPUT ERROR: Please input validate NHTSA Recall Compaign Number... <br>".  __FILE__ ." line:". __LINE__ );
            }else{
                $where = " WHERE NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'";
            }
            $query = $query . $from . $where;
            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                array_push($error_msg,  "Query ERROR: Failed to get Recall information..." . __FILE__ ." line:". __LINE__ );
            }
            echo "<div>";
            echo "<table>";
            echo "<tr>";
            echo "<td class=\"heading\">Recall Manufacturer</td>";
            echo "<td class=\"heading\">Recall Description</td>";
            echo "<td class=\"heading\">NHTSA Recall Compaign Number</td>";
            echo "</tr>";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                print "<tr>";
                print "<td>" . $row['recall_manufacturer'] . "</td>";
                print "<td>" . $row['recall_description'] . "</td>";
                print "<td>" . $row['NHTSA_recall_compaign_number'] . "</td>";
                echo "<td><a href='edit_recall.php?NHTSA_recall_compaign_number=".$row['NHTSA_recall_compaign_number']."&recall_description=".$row['recall_description']. "&recall_manufacturer=" .$row['recall_manufacturer']."'>Edit</a></td>";
                echo "<td><a href='delete_recall.php?NHTSA_recall_compaign_number=".$row['NHTSA_recall_compaign_number']."&recall_description=".$row['recall_description']. "&recall_manufacturer=" .$row['recall_manufacturer']."'>Delete</a></td>";
                echo "<td><a href='add_repair.php?NHTSA_recall_compaign_number=".$row['NHTSA_recall_compaign_number']."&recall_description=".$row['recall_description']. "&recall_manufacturer=" .$row['recall_manufacturer']."'>Add Repair</a></td>";
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
