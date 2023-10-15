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
<title>View Repair</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>
            <div class="features">

                <div class="View Repair section">
                    <div class="subtitle">View Repair Info</div>

                    <form name="view_repair_info" action="view_repair.php" method="get">
                        <table>
                            <tr>
                                <td class="item_label">Vendor Name</td>
                                <td>
                                    <input type="text" name="vendor_name" list="vendor_name_list">
                                    <datalist id = 'vendor_name_list'>
                                        <?php
                                        foreach($VENDOR_LIST as $var) {
                                            ?>
                                            <option value= '<?php echo $var;?>' <?php if ($_GET['vendor_name'] == $var) { print 'selected="true"';} else if($_POST['vendor_name'] == $var){print 'selected="true"';} ?> ><?php echo $var;?></option>
                                            <?php
                                        }
                                        ?>
                                    </datalist>

<!--                                    <a href='add_vendor.php' target='_blank'>Add A New Vendor</a>-->
                                </td>
                            </tr>


                            <tr>
                                <td>Vin Number</td>
                                <td>
                                    <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td>Start Date</td>
                                <td>
                                    <input name = "start_date" type = "date" id = "start_date" value="<?php if ($_GET['start_date']) { print $_GET['start_date']; } ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td>End Date</td>
                                <td>
                                    <input name = "end_date" type = "date" id = "end_date" value="<?php if ($_GET['end_date']) { print $_GET['end_date']; } ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td> Repair Status </td>
                                <td>
                                    <select name = "repair_status">
                                        <option value="pending" <?php if ($_GET['repair_status'] == 'pending') { print 'selected="true"';} ?> >pending</option>
                                        <option value="in progress" <?php if ($_GET['repair_status'] == 'in progress') { print 'selected="true"';} ?> >in progress</option>
                                        <option value="completed" <?php if ($_GET['repair_status'] == 'completed') { print 'selected="true"';} ?> >completed</option>
                                        <option value="All">All</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Repair Description</td>
                                <td>
                                    <input name = "repair_description" type = "text" id = "repair_description" value="<?php if ($_GET['repair_description']) { print $_GET['repair_description']; } ?>" >
                                </td>
                            </tr>



                            <tr>
                                <td>Repair Cost</td>
                                <td>
                                    <input name = "repair_cost" type = "number" id = "repair_cost" value="<?php if ($_GET['repair_cost']) { print $_GET['repair_cost']; } ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>NHTSA Recall Compaign Number</td>
                                <td>
                                    <input name = "nhtsa_recall_compaign_number" type = "text" id = "nhtsa_recall_compaign_number" value="<?php if ($_GET['nhtsa_recall_compaign_number']) { print $_GET['nhtsa_recall_compaign_number']; } ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input name = "view" type = "submit" id = "view" value = "View">
                                    <input type="button" value="Cancel" onclick="history.go(-1)">
                                </td>
                                <td>
                                    <a href='add_repair.php?inventory_clerk_permission=<?php if($_GET['inventory_clerk_permission']) {print $_GET['inventory_clerk_permission'];} ?>&nhtsa_recall_compaign_number=<?php if($_GET['nhtsa_recall_compaign_number']) {print $_GET['nhtsa_recall_compaign_number'];} ?>&repair_cost=<?php if($_GET['repair_cost']) {print $_GET['repair_cost'];} ?>&vendor_name=<?php if($_GET['vendor_name']) {print $_GET['vendor_name'];} ?>&repair_description=<?php if($_GET['repair_description']) {print $_GET['repair_description'];} ?>&repair_status=<?php if($_GET['repair_status']) {print $_GET['repair_status'];} ?>&end_date=<?php if($_GET['end_date']) {print $_GET['end_date'];} ?>&start_date=<?php if($_GET['start_date']) {print $_GET['start_date'];} ?>&vin=<?php if($_GET['vin']) {print $_GET['vin'];} else if($_POST['vin']) {print $_POST['vin'];}?>&nhtsa_recall_compaign_number=<?php if($_GET['NHTSA_recall_compaign_number']) {print $_GET['NHTSA_recall_compaign_number'];} else if($_POST['NHTSA_recall_compaign_number']) {print $_POST['NHTSA_recall_compaign_number'];} ?>' target="_blank">Add A New Repair Record!</a>

                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $enteredVin = mysqli_real_escape_string($db, $_GET['vin']);
            $enteredStart_date = mysqli_real_escape_string($db, $_GET['start_date']);
            $enteredEnd_date = mysqli_real_escape_string($db, $_GET['end_date']);
            $enteredRepair_status = mysqli_real_escape_string($db, $_GET['repair_status']);
            $enteredRepair_Description = mysqli_real_escape_string($db, $_GET['repair_description']);
            $enteredVendor_name = mysqli_real_escape_string($db, $_GET['vendor_name']);
            $enteredRepair_cost = mysqli_real_escape_string($db, $_GET['repair_cost']);
            $enteredNHTSA_recall_campagin_Number = mysqli_real_escape_string($db, $_GET['nhtsa_recall_compaign_number']);
            $enteredInventory_clerk_permssion = mysqli_real_escape_string($db, $_GET['inventory_clerk_permission']);

            $query = "SELECT vin, start_date, end_date, repair_status, repair_description, vendor_name, repair_cost, nhtsa_recall_compaign_number, inventory_clerk_permission ";
            $from = " FROM Repair ";
            $where = " WHERE vin = '$enteredVin' ";

            if(empty($enteredVin)){
                $query = "SELECT * FROM Repair ORDER BY start_date DESC ";
                //array_push($error_msg,  "INPUT ERROR: Please input validate  Vin number ... <br>".  __FILE__ ." line:". __LINE__ );
            }else {
                if (!empty($enteredStart_date)) {
                    $where .= " AND start_date = '$enteredStart_date'";
                }
                if (!empty($enteredEnd_date)) {
                    $where .= " AND end_date = '$enteredEnd_date' ";
                }
                if (!empty($enteredRepair_status) && $enteredRepair_status != 'All' ) {
                    $where .= " AND repair_status = '$enteredRepair_status' ";
                }
                if (!empty($enteredRepair_Description)) {
                    $where .= " AND repair_description = '$enteredRepair_Description' ";
                }
                if (!empty($enteredVendor_name)) {
                    $where .= " AND vendor_name = '$enteredVendor_name' ";
                }
                if (!empty($enteredRepair_cost)) {
                    $where .= " AND repair_cost = '$enteredRepair_cost' ";
                }
                if (!empty($enteredNHTSA_recall_campagin_Number)) {
                    $where .= " AND nhtsa_recall_compaign_number = '$enteredNHTSA_recall_campagin_Number' ";
                }
                if (!empty($enteredInventory_clerk_permssion)) {
                    $where .= " AND inventory_clerk_permission = '$enteredInventory_clerk_permssion' ";
                }
                $query = $query . $from . $where . " ORDER BY start_date DESC";
            }
            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                array_push($error_msg,  "Query ERROR: Failed to get Repair information..." . __FILE__ ." line:". __LINE__ );
            }
            echo "<div>";
            echo "<table>";
            echo "<tr>";
            echo "<td class=\"heading\">Vin</td>";
            echo "<td class=\"heading\">Start Date</td>";
            echo "<td class=\"heading\">End Date</td>";
            echo "<td class=\"heading\">Repair Status</td>";
            echo "<td class=\"heading\">Repair Rescription</td>";
            echo "<td class=\"heading\">Vendor Name</td>";
            echo "<td class=\"heading\">Repair Cost</td>";
            echo "<td class=\"heading\">NHTSA Recall Compaign Number</td>";
            echo "</tr>";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                print "<tr>";
                print "<td>" . $row['vin'] . "</td>";
                print "<td>" . $row['start_date'] . "</td>";
                print "<td>" . $row['end_date'] . "</td>";
                print "<td>" . $row['repair_status'] . "</td>";
                print "<td>" . $row['repair_description'] . "</td>";
                print "<td>" . $row['vendor_name'] . "</td>";
                print "<td>" . $row['repair_cost'] . "</td>";
                print "<td>" . $row['nhtsa_recall_compaign_number'] . "</td>";
                echo "<td><a href='edit_repair.php?vin=".$row['vin']."&start_date=".strftime("%Y-%m-%d", strtotime($row['start_date']))."&end_date=".strftime("%Y-%m-%d", strtotime($row['end_date'])). "&repair_status=" .$row['repair_status']. "&repair_description=" .$row['repair_description']. "&repair_cost=" .$row['repair_cost']. "&vendor_name=" .$row['vendor_name']. "&nhtsa_recall_compaign_number=" .$row['nhtsa_recall_compaign_number']."' target='_blank'>Edit</a></td>";
                echo "<td><a href='delete_repair.php?vin=".$row['vin']."&start_date=".strftime("%Y-%m-%d", strtotime($row['start_date']))."&end_date=".strftime("%Y-%m-%d", strtotime($row['end_date'])). "&repair_status=" .$row['repair_status']. "&repair_description=" .$row['repair_description']. "&repair_cost=" .$row['repair_cost']. "&vendor_name=" .$row['vendor_name']. "&nhtsa_recall_compaign_number=" .$row['nhtsa_recall_compaign_number']."' target='_blank'>Delete</a></td>";
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
