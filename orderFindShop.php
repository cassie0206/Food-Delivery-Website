
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
        $stmt = $conn -> prepare("select OID, sta, shopname, start, end, totalprice from foodorder where shopname=:myShop");
        $stmt->execute(array('myShop' => $_SESSION['myShop']));
    }
    
    else if($desiredStatus == 'Not Finish'){
        $desiredStatus =0;
        $stmt = $conn -> prepare("select OID, shopname, start, end, totalprice from foodorder where sta=:desiredStatus and shopname=:myShop");
        $stmt->execute(array('desiredStatus' => $desiredStatus, 'myShop' => $_SESSION['myShop']));

    }
    else{
        $stmt = $conn -> prepare("select OID, shopname, start, end, totalprice from foodorder where sta=:desiredStatus and shopname=:myShop");
        $stmt->execute(array('desiredStatus' => $desiredStatus, 'myShop' => $_SESSION['myShop']));
    }

    // $stmt = $conn -> prepare("select OID, account, shopname, start, end, totalprice from foodorder where status=:desiredStatus");
    // $stmt->execute(array('desiredStatus' => $desiredStatus));
    //check
    if($stmt -> rowCount() != 0){
        while($row = $stmt -> fetch()){
            //echo($row['sta']);
            // $subList['OID'] = $row['OID'];
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
        $_SESSION['orderList1'] = $orderList;
        
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

    header('location: nav.php#shopOrder');
    exit();

} 
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}



$conn = null;
?>