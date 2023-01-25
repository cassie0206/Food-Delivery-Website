<?php

session_start();

$_SESSION['Authenticated']=false;

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';


try{
    $result = array();
    if(!isset($_REQUEST['shopName']) || empty($_REQUEST['shopName'])){
        //$result['comment'] = 'fail';
        echo 'fail';
        exit();
    }
    
    $name = $_REQUEST['shopName'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn ->prepare("select 店名 from 店家 where 店名=:name");
    $stmt -> execute(array('name' => $name));
    
    
    if($stmt -> rowCount() == 0){
        //$result['comment'] = 'Account is available';
        echo 'Shop name is available';
    }
    else{
        //$result['comment'] = 'Account has been registered';
        echo 'Shop name has been registered';
    }
    //echo json_encode($result);
}
catch(PDOException $e){
    //echo 'something wrong !';
}

?>