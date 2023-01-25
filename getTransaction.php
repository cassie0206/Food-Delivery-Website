
<?php

session_start();

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

try {
    if (!isset($_POST['desiredAction']) || empty($_POST['desiredAction']))
    {
        header("Location: nav.php");
        exit();
    }
    

    $desiredAction = $_POST["desiredAction"];
    $recordList = array();
    $subList = array();
    $totalList =array();
    $account = $_SESSION['Account'];
    $shop = $_SESSION['店名'];


    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare
    $stmt;
    if($desiredAction == 'Payment' || $desiredAction == 'All'){
        $stmt = $conn -> prepare("select TID, totalprice, time, tradershop, action from transaction where (account=:account or accountshop=:accountshop) and (action=:desiredAction)");
        $stmt->execute(array('account' => $account, 'accountshop' => $shop, 'desiredAction' => 'Payment'));
        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                //echo($row['sta']);
                // $subList['OID'] = $row['OID'];
                $subList['totalprice'] = $row['totalprice'];
                $subList['time'] = $row['time'];
                $subList['trader'] = $row['tradershop'];
                $subList['action'] = $row['action'];
                $recordList[$row['TID']] = $subList;
                
            }
        } 
        $msg = count($recordList);
        ksort($recordList);
        $_SESSION['recordList'] = $recordList;
        $totalList = $recordList;
    }
    
    if($desiredAction == 'Receive' || $desiredAction == 'All'){
        // $msg .= 'receive ';
        $stmt = $conn -> prepare("select TID, totalprice, time, trader, action from transaction where (account=:account or accountshop=:accountshop) and (action=:desiredAction)");
        $stmt->execute(array('account' => $account, 'accountshop' => $shop, 'desiredAction' => 'Receive'));
        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                //echo($row['sta']);
                // $subList['OID'] = $row['OID'];
                $subList['totalprice'] = $row['totalprice'];
                $subList['time'] = $row['time'];
                $subList['trader'] = $row['trader'];
                $subList['action'] = $row['action'];
                $recordList[$row['TID']] = $subList;
            }
        }

        ksort($recordList);
        $_SESSION['recordList'] = $recordList;
        $totalList = array_replace($totalList, $recordList);
        ksort($totalList);
    }
    if($desiredAction == 'Recharge' || $desiredAction == 'All'){
        // $msg .= 'recharge ';
        $stmt = $conn -> prepare("select TID, totalprice, time, trader, action from transaction where account=:account and action=:desiredAction");
        $stmt->execute(array('account' => $account, 'desiredAction' => 'Recharge'));
        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                //echo($row['sta']);
                // $subList['OID'] = $row['OID'];
                $subList['totalprice'] = $row['totalprice'];
                $subList['time'] = $row['time'];
                $subList['trader'] = $row['trader'];
                $subList['action'] = $row['action'];
                $recordList[$row['TID']] = $subList;
            }
        }

        ksort($recordList);
        $_SESSION['recordList'] = $recordList;
        $totalList = array_replace($totalList, $recordList);
        ksort($totalList);
    }
    if($desiredAction == 'All'){
        $_SESSION['recordList'] = $totalList;
    }


    // $stmt = $conn -> prepare("select OID, account, shopname, start, end, totalprice from foodorder where status=:desiredStatus");
    // $stmt->execute(array('desiredStatus' => $desiredStatus));
    //check
    // if(num($subList)){
    //     while($row = $stmt -> fetch()){
    //         //echo($row['sta']);
    //         // $subList['OID'] = $row['OID'];
    //         $subList['start'] = $row['start'];
    //         $subList['end'] = $row['end'];
    //         $subList['shopname'] = $row['shopname'];
    //         $subList['totalprice'] = $row['totalprice'];
    //         if($desiredStatus == 'All'){
    //             $subList['sta'] = $row['sta'];
    //         }
    //         else{
    //             $subList['sta'] = $desiredStatus;
    //         }
    //         $orderList[$row['OID']] = $subList;
    //     }
    //     // echo 'ha';
        // ksort($orderList);
        // $_SESSION['orderList1'] = $orderList;
        
    // }
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