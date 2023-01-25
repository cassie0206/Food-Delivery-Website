<?php
session_start();
 if(isset($_POST["oid"]))  
 {  
    $_SESSION['oid'] = $_POST["oid"];
    echo($_SESSION['oid']);
 }  
 else
 {
     echo "NOONONO";
 }
 
 ?>