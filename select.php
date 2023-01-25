<?php
session_start();
 if(isset($_POST["foodname"]))  
 {  
    $_SESSION['foodname'] = $_POST["foodname"];
    echo($_SESSION['foodname']);
 }  
 else
 {
     echo "NOONONO";
 }
 
 ?>