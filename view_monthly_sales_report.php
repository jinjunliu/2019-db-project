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



$query = "SELECT ".
            "YEAR(Sell.sale_date) AS sale_year, ".
            "COUNT( Sell .vin) AS num_of_vehicle_sold, ".
            "SUM( Vehicle .sale_price) AS total_sale_income, ".
            "(SUM( Vehicle .sale_price)- SUM( Buy .purchase_price) - SUM(repairsum.totalrepaircost)) AS net_income ".
            "FROM Sell ".
            "JOIN Vehicle ".
            "ON Vehicle .vin = Sell .vin ".
            "JOIN Buy ".
            "ON Sell .vin = Buy .vin ".
            "JOIN ( ".
            "SELECT Repair.vin, ".
            "SUM(Repair.repair_cost) AS totalrepaircost ".
            "FROM Repair ".
            "GROUP BY Repair.vin)repairsum ".
            "ON Sell .vin = repairsum.vin ".
            "GROUP BY Sale_year ".
            "ORDER BY Sale_year DESC";

$result = mysqli_query($db, $query);
            include('lib/show_queries.php');
$result_y = $result;
            

$query = "SELECT ".
        "CONCAT(YEAR(Sell.sale_date), '-', MONTH(Sell.sale_date)) AS sale_month, ".
        "COUNT( Sell .vin) AS num_of_vehicle_sold, ".
        "SUM( Vehicle .sale_price) AS total_sale_income, ".
        "(SUM( Vehicle .sale_price)- SUM( Buy .purchase_price) - SUM(repairsum.totalrepaircost)) AS net_income ".
        "FROM Sell JOIN Vehicle ".
        "ON Vehicle .vin = Sell .vin ".
        "JOIN Buy ".
        "ON Sell .vin = Buy .vin ".
        "JOIN ( ".
        "SELECT Repair.vin, ".
        "SUM(Repair.repair_cost) AS totalrepaircost ".
        "FROM Repair ".
        "GROUP BY Repair.vin)repairsum ".
        "ON Sell .vin = repairsum.vin ".
        "GROUP BY Sale_month ".
        "ORDER BY Sale_month DESC";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
$result_m = $result;


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
                <?php print 'Sales Report'; ?>
            </div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Yearly Sales</div>
<!--                    <p style="color:red;">This is a paragraph.</p>-->
                    <table>
                        <?php
                        echo "<table border='1'>";
                        echo "<tr><td>Year</td><td>Number of Vehicle Sold</td><td>Total Sale Income</td><td>Net Income</td></tr>";
                        //                        echo "<tr><td>Year</td><td>Number of Vehicle Sold</td><td>Total Sale Income</td><td>Net Income</td><td>Detailed Report</td></tr>";



                        if (is_bool($result_y) && (mysqli_num_rows($result_y) == 0) ) {
                            array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                        }
                        $array_y = array();
                        while ($row = mysqli_fetch_array($result_y, MYSQLI_ASSOC))
                        {

                            echo "<tr><td>{$row['sale_year']}</td><td>{$row['num_of_vehicle_sold']}</td><td>{$row['total_sale_income']}</td><td>{$row['net_income']}</td></tr>";
                            array_push($array_y, $row['sale_year']);
//                            echo "<tr><td>{$row['sale_year']}</td><td>{$row['num_of_vehicle_sold']}</td><td>{$row['total_sale_income']}</td><td>{$row['net_income']}</td><td><form><select><option>{$row['sale_year']}</option></select></form></td></tr>";
                        }
                        echo "</table>";



                        echo "View detailed yearly report:";
                        echo '<form method="post" action="view_detailed_yearly_report.php"><section class="year_report"><select name="yearly">';
                        for ($i = 0; $i < count($array_y); $i++)
                        {
                            echo '<option>'.$array_y[$i].'</option>';
                        }
                        echo '</select></section><section class="submission"><input type = "submit" value = "Submit"></section></form>';

                        ?>



                    </table>


                    <div class="subtitle">Monthly Sales</div>
                    <!--                    <p style="color:red;">This is a paragraph.</p>-->
                    <table>
                        <?php
                        echo "<table border='1'>";
                        echo "<tr><td>Month</td><td>Number of Vehicle Sold</td><td>Total Sale Income</td><td>Net Income</td></tr>";



                        if (is_bool($result_m) && (mysqli_num_rows($result_m) == 0) ) {
                            array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                        }
                        $array_m = array();
                        while ($row = mysqli_fetch_array($result_m, MYSQLI_ASSOC))
                        {

                            echo "<tr><td>{$row['sale_month']}</td><td>{$row['num_of_vehicle_sold']}</td><td>{$row['total_sale_income']}</td><td>{$row['net_income']}</td></tr>";
                            array_push($array_m, $row['sale_month']);
                        }
                        echo "</table>";


                        echo "View detailed monthly report";
                        echo '<form method="post" action="view_detailed_monthly_report.php"><section class="month_report"><select name="monthly">';
                        for ($i = 0; $i < count($array_m); $i++)
                        {
                            echo '<option>'.$array_m[$i].'</option>';
                        }
                        echo '</select></section><section class="submission"><input type = "submit" value = "Submit"></section></form>';

                        ?>



                    </table>
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
