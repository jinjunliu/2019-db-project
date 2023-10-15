<?php
include('lib/common.php');
// written by czhang613
// setup permissino
if (!isset($_SESSION['username']) OR $_SESSION['permission'] == 1 OR $_SESSION['permission'] == 3) {
    header('Location: index.php');
	exit();
}

$enteredVIN = $_GET['vin'];
$enteredcustomer_id = $_GET['customer_id'];
$enteredsalesperson_permission = 'salesperson_permission_'. $_SESSION['username'];

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $enteredsale_date = mysqli_real_escape_string($db, $_POST['sale_date']);
            $query = "INSERT INTO Sell (vin, customer_id, salesperson_permission, sale_date)"
                    . "VALUES('$enteredVIN', '$enteredcustomer_id', '$enteredsalesperson_permission', '$enteredsale_date')";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');
            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR: Customer Table form  <br>".  __FILE__ ." line:". __LINE__ );
            } else {
?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
            <script>
            $(document).ready(function() {
	        $(function() {
            $("#my_dialog" ).dialog();
            });
            })
            </script>
            <div id="my_dialog" title="Message">
            <p> <?php echo "This vehicle was sold successfully!"?></p>
            </div>
<?php
            }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Sale Order</title>
	</head>

	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>

			<div class="center_content">
				<div class="center_left">
					<div class="features">

            <div class="profile_section">
							<div class="subtitle">Sale Order Form</div>

                            <form name = "sale_order" action = "sale_order.php?vin=<?php echo $enteredVIN;?>&customer_id=<?php echo $enteredcustomer_id;?>" method="post">
                                <table>

                                    <tr>
                                        <td class ="item_label">VIN</td>
                                        <td>
                                            <?php
                                            echo $enteredVIN;
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Sale Date</td>
                                        <td>
                                            <input name = "sale_date" type = "date" id = "sale_date" value="<?php if ($_POST['sale_date']) { print $_POST['sale_date']; } ?>" >
                                        </td>
                                    </tr>
                                </table>
                                <tr>
                                    <input name = "add" type = "submit" id = "add" value = "Confirm Sale!">
                                </tr>
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
