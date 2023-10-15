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



$query = "SELECT sellernames.name, ".
    "COUNT(Buy .vin) AS total_vehicle_number_sold_to_us, ".
    "ROUND(AVG(repaircounts.number_of_repair_for_this_vehicle),1) AS repairs_per_vehicle, ".
    "ROUND(AVG(Buy.purchase_price),2) AS avg_purchase_price ".
    "FROM Buy  ".
    "LEFT OUTER JOIN ( ".
    "SELECT Buy.vin, ".
    "COUNT(repair.vin) as number_of_repair_for_this_vehicle ".
    "FROM Buy ".
    "LEFT OUTER JOIN Repair ".
    "ON Buy.vin = Repair.vin ".
    "GROUP BY Buy.vin)repaircounts ".
    "ON Buy.vin = repaircounts.vin ".
    "JOIN ( ".
    "SELECT customer_id, CONCAT(customer_first_name, ' ', customer_last_name) AS name ".
    "FROM Person ".
    "UNION ".
    "SELECT customer_id, business_name AS name ".
    "FROM Business)sellernames ".
    "ON Buy.customer_id = sellernames.customer_id ".
    "GROUP BY sellernames.name ".
    "ORDER BY total_vehicle_number_sold_to_us DESC, avg_purchase_price ASC";

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
                <?php print 'Seller History Report'; ?>
            </div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Seller History</div>
                    <!--                    <p style="color:red;">This is a paragraph.</p>-->
                    <table>
                        <?php
                        echo "<table border='1'>";
                        echo "<tr><td>Customer Name</td><td>Number of Vehicle from Customer</td><td>Average Repair Number per Vehicle</td><td>Average Purchase Price</td></tr>";



                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                            array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                        }
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                        {
                            $style = '';
                            if($row['repairs_per_vehicle']>= 5 )
                                $style = "color:red;";


                            echo "<tr style=$style><td>{$row['name']}</td><td>{$row['total_vehicle_number_sold_to_us']}</td><td>{$row['repairs_per_vehicle']}</td><td>{$row['avg_purchase_price']}</td></tr>";
                            //
                        }
                        echo "</table>";

                        echo "*: For any seller who shows an average of five or more repairs are highlighted in RED. They may be selling lower quality vehicles. We may
want to avoid buying from them in the future!"
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
