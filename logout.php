<?php
include 'top.php';

// Destroying the session will remove the username, deauthenticating the user
// User authentication is predicated on the use of the username session variable
session_destroy();
header("Location: index.php");
?>