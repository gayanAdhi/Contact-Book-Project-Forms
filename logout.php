<?php

//Start the session to access session variables
 session_start();

 //Unset the 'user_id' session variable
 unset($_SESSION['user_id']);
 unset($_SESSION['name']);


 //Destroy the session
 session_destroy();

 //Redirect the user to the login page
 header("Location: login.php");

 ?>