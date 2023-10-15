<?php

include('lib/common.php');
// written by GTusername4


//if (!isset($_SESSION['email'])) {
//	header('Location: login.php');
//	exit();
//}

if (!isset($_SESSION['permission']) OR $_SESSION['permission'] < 3) {
	  header('Location: index.php');
    exit();
}

//if (!isset($_SESSION['permission'])) {
//    header('Location: public_search.php');
//    exit();
//}


$query =
    "SELECT VehicleType.type_name, IFNULL(avg_inventory_age,'N/A') AS avginventoryage, ".
    "IFNULL(max_inventory_age, 'N/A') AS maxinventoryage, IFNULL(min_inventory_age, 'N/A') AS mininventoryage ".
    "FROM VehicleType LEFT JOIN ( ".
    "SELECT Vehicle.type_name, ".
    "ROUND(AVG(DATEDIFF(CURRENT_DATE, Buy.purchase_date)),1) AS avg_inventory_age, ".
    "MAX(DATEDIFF(CURRENT_DATE, Buy.purchase_date)) AS max_inventory_age,  ".
    "MIN(DATEDIFF(CURRENT_DATE, Buy.purchase_date)) AS min_inventory_age ".
    "FROM Vehicle LEFT OUTER JOIN Buy ON Vehicle.vin = Buy.vin ".
    "RIGHT JOIN VehicleType ON Vehicle.type_name = VehicleType .type_name ".
    "WHERE Vehicle.vin NOT IN (SELECT Sell .vin FROM Sell) ".
    "GROUP BY Vehicle .type_name)tbl ".
    "ON VehicleType.type_name = tbl.type_name ".
    "ORDER BY tbl.type_name";


$result = mysqli_query($db, $query);
include('lib/show_queries.php');


?>



<?php include("lib/header.php"); ?>
<!--<title>GTOnline Profile</title>-->
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <?php include("lib/reports_menu.php"); ?>

    <div class="center_content">
        <!--        --><?php //print $row['login_first_name'] . ' ' . $row['login_last_name']. ' ' . $_SESSION['email'].' ' . $row['gender'] ; ?>
        <!--        --><?php //print $row['vin'] . ' ' . $row['type_name']; ?>
        <!--        --><?php //print "<h1>1223456789000000</h1>"; ?>
        <!--        --><?//= $row[1]?>

        <!--        --><?php //print "1223456789000000"; ?>
        <div class="center_left">
            <div class="title_name">
                <?php print 'Inventory Age Report'; ?>
            </div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Average Inventory Age (day)</div>
                    <table>
                        <?php
                        echo "<table border='1'>";
                        echo "<tr><td>Vehicle Type</td><td>Average Inventory Age</td><td>Max Inventory Age</td><td>Min Inventory Age</td></tr>";



                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                            array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                        }
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//                            print_r($row);
//                            echo"<br>";



                            echo "<tr><td>{$row['type_name']}</td><td>{$row['avginventoryage']}</td><td>{$row['maxinventoryage']}</td><td>{$row['mininventoryage']}</td></tr>";
                            //
                        }


                        echo "</table>";

                        echo "*: If a vehicle type has no unsold units, the report display “N/A” for that vehicle type."
                        ?>
                        <!--                        <tr>-->
                        <!--                            <td class="item_label">Gender</td>-->
                        <!--                            <td>-->
                        <!--                                --><?php //if ($row['gender'] == 'Male') { print 'Male';} else {print 'Female';} ?>
                        <!--                            </td>-->
                        <!--                        </tr>-->
                        <!--                        <tr>-->
                        <!--                            <td class="item_label">Birthdate</td>-->
                        <!--                            <td>-->
                        <!--                                --><?php //print $row['birthdate'];?>
                        <!--                            </td>-->
                        <!--                        </tr>-->
                        <!--                        <tr>-->
                        <!--                            <td class="item_label">Current City</td>-->
                        <!--                            <td>-->
                        <!--                                --><?php //print $row['currentcity'];?>
                        <!--                            </td>-->
                        <!--                        </tr>-->

                        <!--                        <tr>-->
                        <!--                            <td class="item_label">Hometown</td>-->
                        <!--                            <td>-->
                        <!--                                --><?php //print $row['hometown'];?>
                        <!--                            </td>-->
                        <!--                        </tr>-->

                        <!--                        <tr>-->
                        <!--                            <td class="item_label">Interests</td>-->
                        <!--                            <td>-->
                        <!--                                <ul>-->
                        <!--                                    --><?php
                        //                                            $query = "SELECT interest FROM UserInterest WHERE email='{$_SESSION['email']}'";
                        //                                            $result = mysqli_query($db, $query);
                        //
                        //                                            include('lib/show_queries.php');
                        //
                        //                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                        //                                                    array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                        //                                             }
                        //
                        //                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        //                                                print "<li>{$row['interest']}</li>";
                        //                                            }
                        //										?>
                        <!--                                </ul>-->
                        <!--                            </td>-->
                        <!--                        </tr>-->
                    </table>
                </div>

                <!--                <div class="profile_section">-->
                <!--                    <div class="subtitle">Education</div>-->
                <!--                    <table>-->
                <!--                        <tr>-->
                <!--                            <td class="heading">School</td>-->
                <!--                            <td class="heading">Year Graduated</td>-->
                <!--                        </tr>-->
                <!---->
                <!--                        --><?php
                //									    $query = "SELECT schoolname, yeargraduated " .
                //											 "FROM Attend " .
                //											 "WHERE email='{$_SESSION['email']}' " .
                //											 "ORDER BY yeargraduated DESC";
                //									    $result = mysqli_query($db, $query);
                //                                        include('lib/show_queries.php');
                //
                //                                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                //                                                    array_push($error_msg,  "Query ERROR: Failed to get School information...<br>" . __FILE__ ." line:". __LINE__ );
                //                                             }
                //
                //									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                //										print "<tr>";
                //										print "<td>" . $row['schoolname'] . "</td>";
                //										print "<td>" . $row['yeargraduated'] . "</td>";
                //										print "</tr>";
                //									}
                //								?>
                <!--                    </table>-->
                <!--                </div>-->

                <!--                <div class="profile_section">-->
                <!--                    <div class="subtitle">Professional</div>-->
                <!--                    <table>-->
                <!--                        <tr>-->
                <!--                            <td class="heading">Employer</td>-->
                <!--                            <td class="heading">Job Title</td>-->
                <!--                        </tr>-->
                <!---->
                <!--                        --><?php
                //                                        $query = "SELECT employername, jobtitle " .
                //											 "FROM Employment " .
                //											"WHERE email='{$_SESSION['email']}' " .
                //											 "ORDER BY employername DESC";
                //									   $result = mysqli_query($db, $query);
                //
                //                                       include('lib/show_queries.php');
                //
                //                                       if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                //                                             array_push($error_msg,  "Query ERROR: Failed to get Employment information..." . __FILE__ ." line:". __LINE__ );
                //                                        }
                //
                //									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                //										print "<tr>";
                //										print "<td>" . $row['employername'] . "</td>";
                //										print "<td>" . $row['jobtitle'] . "</td>";
                //										print "</tr>";
                //									}
                //								?>
                <!--                    </table>-->
                <!--                </div>-->

            </div>
        </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>

    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>
