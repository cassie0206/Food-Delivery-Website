<?php
session_start();
 if(isset($_POST["foodname"]))  
 {  
    $_SESSION['foodname'] = $_POST["foodname"];
 }  
 else
 {
     echo "NOONONO";
 }
 
 ?>