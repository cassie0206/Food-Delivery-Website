<?php

session_start();

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';
try {
    if (!isset($_POST['desiredStatus']) || empty($_POST['desiredStatus']))
    {
        header("Location: nav.php");
        exit();
    }

    $desiredStatus = $_POST["desiredStatus"];
    $orderList = array();
    $subList = array();

    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare
    $stmt;
    if($desiredStatus == 'All'){
        $stmt = $conn -> prepare("select OID, account, sta, shopname, start, end, totalprice from foodorder where account=:Account");
        $stmt->execute(array('Account' => $_SESSION['Account']));
    }
    else if($desiredStatus == 'Not Finish'){
        $desiredStatus =0;
        $stmt = $conn -> prepare("select OID, account, shopname, start, end, totalprice from foodorder where sta=:desiredStatus and account=:Account");
        $stmt->execute(array('desiredStatus' => $desiredStatus, 'Account' => $_SESSION['Account']));

    }
    else{
        $stmt = $conn -> prepare("select OID, account, shopname, start, end, totalprice from foodorder where sta=:desiredStatus and account=:Account");
        $stmt->execute(array('desiredStatus' => $desiredStatus, 'Account' => $_SESSION['Account']));
    }

    // $stmt = $conn -> prepare("select OID, account, shopname, start, end, totalprice from foodorder where status=:desiredStatus");
    // $stmt->execute(array('desiredStatus' => $desiredStatus));
    //check
    if($stmt -> rowCount() != 0){
        while($row = $stmt -> fetch()){
            // $subList['OID'] = $row['OID'];
            echo(123);
            echo($row['sta']);
            $subList['start'] = $row['start'];
            $subList['end'] = $row['end'];
            $subList['shopname'] = $row['shopname'];
            $subList['totalprice'] = $row['totalprice'];
            if($desiredStatus == 'All'){
                $subList['sta'] = $row['sta'];
            }
            else{
                $subList['sta'] = $desiredStatus;
            }
            $orderList[$row['OID']] = $subList;
            // echo 'ba';
        }
        // echo 'ha';
        ksort($orderList);
        $_SESSION['orderList'] = $orderList;
        
    }
    // $msg = $desiredStatus;
    // var_dump($subList);
    // var_dump($orderList[1]['shopname']);
    // echo <<< EOT
    //     <!DOCTYPE html>
    //     <html>
    //     <body>
    //         <script>
    //         alert("$msg");
    //         window.location.replace("index.php");
    //         </script>
    //     </body>
    //     </html>
    //  EOT;

    header('location: nav.php#myOrder');
    exit();

} 
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}



$conn = null;
?>