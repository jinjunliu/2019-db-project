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
    "SELECT vehicletype.type_name AS AAA, IFNULL(otbl.average_time_in_inventory, 'N/A') AS BBB ".
    "FROM vehicletype LEFT JOIN ( ".
        "SELECT Vehicle.type_name AS type_name, ".
	    "ROUND(AVG(tbl.dateDiff),1) AS average_time_in_inventory ".
	    "FROM (SELECT Sell.vin AS vin, DATEDIFF(Sell.sale_date, Buy.purchase_date) AS dateDiff ".
            "FROM Sell LEFT JOIN Buy ON Sell.vin = Buy.vin ".
            "WHERE Buy.purchase_date IS NOT NULL AND Sell.sale_date IS NOT NULL)tbl ".
	    "LEFT JOIN Vehicle ON Vehicle.vin = tbl.vin ".
	    "GROUP BY Vehicle.type_name ".
	    "ORDER BY Vehicle.type_name)otbl ".
    "ON vehicletype.type_name = otbl.type_name";
//		 "WHERE User.email='{$_SESSION['email']}'";


//    $query = "SELECT vin, type_name " .
//		 "FROM vehicle";
////		 "WHERE User.email='{$_SESSION['email']}'";

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
        <div class="center_left">
            <div class="title_name">
                <?php print 'Average Time In Inventory Report'; ?>
            </div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Average Time in Inventory (day)</div>
                    <table>
                        <?php
                        echo "<table border='1'>";
                        echo "<tr><td>Vehicle Type</td><td>Average Time in Inventory (day) </td></tr>";




                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                            array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                        }
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//                            print_r($row);
//                            echo"<br>";

                            echo "<tr><td>{$row['AAA']}</td><td>{$row['BBB']}</td></tr>";
                        }
                        echo "</table>";

                        echo "*: If a vehicle type has no sale history, the report display “N/A” for that vehicle type."
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
