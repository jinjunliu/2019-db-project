<?php
// written by jliu788

session_start();

if (empty($_SESSION['username'])) {
    header("Location: public_search.php");
    die();
} else {
    if ($_SESSION['permission'] == 1){
        header("Location: employee_search_clerk.php");
        die();
    }
    if ($_SESSION['permission'] == 2){
        header("Location: employee_search_salesperson.php");
        die();
    }
    if ($_SESSION['permission'] == 3){
        header("Location: employee_search_manager.php");
        die();
    }
    if ($_SESSION['permission'] == 4){
        header("Location: employee_search_owner.php");
        die();
    }
}

?>