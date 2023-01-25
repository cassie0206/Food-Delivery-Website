<?php

session_start();

$_SESSION['Authenticated']=false;

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';


try{
    $result = array();
    if(!isset($_REQUEST['Account']) || empty($_REQUEST['Account'])){
        //$result['comment'] = 'fail';
        echo 'fail';
        exit();
    }
    
    $account = $_REQUEST['Account'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn ->prepare("select account from user where account=:account");
    $stmt -> execute(array('account' => $account));
    
    
    if($stmt -> rowCount() == 0){
        //$result['comment'] = 'Account is available';
        echo 'Account is available';
    }
    else{
        //$result['comment'] = 'Account has been registered';
        echo 'Account has been registered';
    }
    //echo json_encode($result);
}
catch(PDOException $e){
    //echo 'something wrong !';
}

?>