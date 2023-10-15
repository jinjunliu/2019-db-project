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

echo $_POST;
echo '\n';
echo '/n';
print_r($_POST);

$query = "SELECT ".
	        "MAX(Users.login_first_name) AS top_seller_first_name, ".
	        "MAX(Users.login_last_name) AS top_seller_last_name, ".
	        "COUNT(Sell.vin) AS num_vehicle_sold, ".
	        "SUM(Vehicle.sale_price) AS total_sales ".
        "FROM Sell ".
        "JOIN Vehicle ".
        "ON Vehicle.vin = Sell.vin ".
        "JOIN Salesperson ".
        "ON Sell.salesperson_permission = Salesperson.salesperson_permission ".
        "JOIN Users ".
        "ON Salesperson.username = Users.username ".
        "WHERE YEAR(Sell.sale_date) = '{$_POST["yearly"]}' ".
        "GROUP BY Salesperson.username ".
        "ORDER BY  ".
        "num_vehicle_sold DESC, ".
        "total_sales DESC ";

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
                <?php print 'Detailed Yearly Sales Report'; ?>
            </div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Salespeople Performance of the Year</div>
                    <!--                    <p style="color:red;">This is a paragraph.</p>-->
                    <table>
                        <?php
                        echo "<table border='1'>";
                        echo "<tr><td>First Name</td><td>Last Name</td><td>Vehicles Sold</td><td>Total Sales</td></tr>";



                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                            array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                        }
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                        {

                            echo "<tr><td>{$row['top_seller_first_name']}</td><td>{$row['top_seller_last_name']}</td><td>{$row['num_vehicle_sold']}</td><td>{$row['total_sales']}</td></tr>";
                            //
                        }
                        echo "</table>";


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
