<?php
session_start();

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
//echo "Connected successfully";
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//prepare

$stmt = $conn -> prepare("select 店名 from shop where account=:account");
$stmt -> execute(array('account' => $_SESSION['Account']));


if($stmt->rowCount() == 0){
    $_SESSION['role'] = 'user';
}
else{
  $_SESSION['role'] = 'manager';
}
$link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);


          if($_SESSION['role'] == 'user')
          {
              require_once "shopregisterform.php";
          }
          else if( $_SESSION['role'] == 'manager')
          {
               require_once "shopready.php";
          }

          
      ?>