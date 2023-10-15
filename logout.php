<?php

// written by jliu788

/* destroy session data */
session_start();
session_destroy();
$_SESSION = array();

/* redirect to public search page */
header('Location: public_search.php');

?>