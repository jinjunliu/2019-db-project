<?php
// written by jliu788

include('lib/common.php');

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$entered_username = mysqli_real_escape_string($db, $_POST['username']);
    $entered_password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($entered_username)) {
            array_push($error_msg,  "Please enter an username.");
    }

	if (empty($entered_password)) {
			array_push($error_msg,  "Please enter a password.");
	}
	
    if ( !empty($entered_username) && !empty($entered_password)){

        $query = "SELECT password FROM Users WHERE username='$entered_username'";
        
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result);
        
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $stored_password = $row['password']; 
            
            $options = [
                'cost' => 8,
            ];
             //convert the plaintext passwords to their respective hashses
             // 'michael123' = $2y$08$kr5P80A7RyA0FDPUa8cB2eaf0EqbUay0nYspuajgHRRXM9SgzNgZO
            $stored_hash = password_hash($stored_password, PASSWORD_DEFAULT , $options);   //may not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $entered_hash = password_hash($entered_password, PASSWORD_DEFAULT , $options); 
            
            if($showQueries){
                array_push($query_msg, "Plaintext entered password: ". $entered_password);
                //Note: because of salt, the entered and stored password hashes will appear different each time
                array_push($query_msg, "Entered Hash:" . $entered_hash);
                array_push($query_msg, "Stored Hash:  " . $stored_hash . NEWLINE);  //note: change to storedHash if tables store the plaintext password value
                //unsafe, but left as a learning tool uncomment if you want to log passwords with hash values
                //error_log('email: '. $enteredEmail  . ' password: '. $enteredPassword . ' hash:'. $enteredHash);
            }
            
            //depends on if you are storing the hash $storedHash or plaintext $storedPassword 
            if (password_verify($entered_password, $stored_hash) ) {
                array_push($query_msg, "Password is Valid! ");
                $_SESSION['username'] = $entered_username;
                array_push($query_msg, "logging in... ");

                $query="SELECT EXISTS(SELECT * FROM InventoryClerk WHERE username='$entered_username') as if_exists";
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');
                $permission_clerk = mysqli_fetch_assoc($result);

                $query="SELECT EXISTS(SELECT * FROM Salesperson WHERE username='$entered_username') as if_exists";
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');
                $permission_sales = mysqli_fetch_assoc($result);

                $query="SELECT EXISTS(SELECT * FROM Manager WHERE username='$entered_username') as if_exists";
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');           
                $permission_manager = mysqli_fetch_assoc($result);

                if($permission_clerk['if_exists'] == 1 AND $permission_sales['if_exists'] == 0 AND $permission_manager['if_exists'] == 0) {
                    $_SESSION['permission'] = 1;
                    array_push($query_msg, "Logged in user is an inventory clerk...");
                    header(REFRESH_TIME . 'url=employee_search_clerk.php');		//to view the password hashes and login success/failure
                }
                if($permission_clerk['if_exists'] == 0 AND $permission_sales['if_exists'] == 1 AND $permission_manager['if_exists'] == 0) {
                    $_SESSION['permission'] = 2;
                    array_push($query_msg, "Logged in user is a salesperson...");
                    header(REFRESH_TIME . 'url=employee_search_salesperson.php');		//to view the password hashes and login success/failure
                }
                if($permission_clerk['if_exists'] == 0 AND $permission_sales['if_exists'] == 0 AND $permission_manager['if_exists'] == 1) {
                    $_SESSION['permission'] = 3;
                    array_push($query_msg, "Logged in user is a manager...");
                    header(REFRESH_TIME . 'url=employee_search_manager.php');		//to view the password hashes and login success/failure
                }
                if($permission_clerk['if_exists'] == 1 AND $permission_sales['if_exists'] == 1 AND $permission_manager['if_exists'] == 1) {
                    $_SESSION['permission'] = 4;
                    array_push($query_msg, "Logged in user is Mr. Burdell (owner)...");
                    header(REFRESH_TIME . 'url=employee_search_owner.php');		//to view the password hashes and login success/failure
                }
                
            } else {
                array_push($error_msg, "Login failed: " . $entered_username . NEWLINE);
                array_push($error_msg, "To demo enter: ". NEWLINE . "burdell". NEWLINE ."burdell");
            }
            
        } else {
                array_push($error_msg, "The username entered does not exist: " . $entered_username);
            }
    }
}
?>

<?php include("lib/header.php"); ?>
<title>Employee Login</title>
</head>
<body>
    <div id="main_container">
        <div id="header">
            <div class="logo">
                <img src="img/team22_logo.png" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="Team22 Logo"/>
            </div>
        </div>

        <div class="center_content">
            <div class="text_box">

                <form action="login.php" method="post" enctype="multipart/form-data">
                    <div class="title">Employee Login</div>
                    <div class="login_form_row">
                        <label class="login_label">Username:</label>
                        <input type="text" name="username" value="burdell" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Password:</label>
                        <input type="password" name="password" value="burdell" class="login_input"/>
                    </div>
                    <input type="image" src="img/login.gif" class="login"/>
                    </form>
                </div>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
            </div>
            
			<?php include("lib/footer.php"); ?>

        </div>
    </body>
</html>