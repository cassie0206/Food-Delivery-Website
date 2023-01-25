<?php
$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';
$link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);
if($link){
    mysqli_query($link,'SET NAMES utf8');
    // echo "正確連接資料庫";
}
else {
    // echo "不正確連接資料庫</br>" . mysqli_connect_error();
}
?>