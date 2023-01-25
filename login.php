<?php
session_start();

$_SESSION['Authenticated']=false;

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';
try {
    if (empty($_POST['Account']) || empty($_POST['password'])){
        throw new Exception('Please input account or password.');
    }

    $account = $_POST['Account'];
    $pwd = $_POST['password'];
    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare
    $stmt = $conn -> prepare("select account, username, phone, ST_AsText(location), latitude,longitude,balance, password, salt from user where account=:account");
    $stmt->execute(array('account' => $account));
    //check
    if($stmt -> rowCount() == 1){
        $row = $stmt -> fetch();
        //success
        if($row['password'] == hash('sha256', $row['salt'].$pwd)){

            //echo gettype(ST_AsText(location));
            $_SESSION['Authenticated'] = true;
            $_SESSION['Account'] = $account;
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['balance'] = $row['balance'];
            $_SESSION['location'] = $row['ST_AsText(location)'];
            $_SESSION['latitude'] = $row['latitude'];
            $_SESSION['longitude'] = $row['longitude'];
            //$_SESSION['location'] = $row['location'];
            $_SESSION['nameCategory']=array();
            $_SESSION['flag']='';

            $stmt = $conn -> prepare("select 店名 from shop where account=:account");
            $stmt -> execute(array('account' => $_SESSION['Account']));


            if($stmt->rowCount() == 0){
                $_SESSION['role'] = 'user';
                $_SESSION['店名'] = '0';
                $_SESSION['orderList'] = array();
                $_SESSION['orderList1'] = array();
            }
            else{
                $row = $stmt -> fetch();
                $_SESSION['role'] = 'manager';
                $_SESSION['myShop'] = $row['店名'];
                $_SESSION['orderList'] = array();
                $_SESSION['orderList1'] = array();
            }
            //for transaction record
            $_SESSION['recordList'] = array();

            //echo gettype($_SESSION['location']);


            //jump to home
            header('location: nav.php');
            exit();
        }
        else{
            throw new Exception('login failed !!');
        }
    }
    else{
        throw new Exception('login failed !!');
    }
} 
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
catch(Exception $e){
    $msg=$e->getmessage();
    session_unset();
    session_destroy();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
            alert("$msg");
            window.location.replace("index.php");
            </script>
        </body>
        </html>
     EOT;


}


$conn = null;
?>