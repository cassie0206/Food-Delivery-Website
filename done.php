
<?php

session_start();
echo($_SESSION['oid']);
$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

try {

    $OID = $_SESSION['oid'];

    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare
    $stmt = $conn -> prepare("select shopname, start, totalprice, distance from foodorder where OID=:OID");
    $stmt->execute(array('OID' => $OID));
    //echo("aaa");

    if($stmt -> rowCount() == 1){
        $row = $stmt -> fetch();
        date_default_timezone_set("Asia/Taipei");
        $end = date("Y-m-d h:i:sa");
        $end = substr($end, 0, 19);
        
        // $stmt = $conn -> prepare("delete from foodorder where OID=:OID");
        // $stmt->execute(array('OID' => $OID));

        if($stmt -> rowCount() != 0){
            //for order status
            $stmt = $conn -> prepare("update foodorder set sta=:sta where OID=:OID");
            $stmt -> execute(array('OID' => $OID,'sta' => 'Finished'));  
            $stmt = $conn -> prepare("update foodorder set start=:start, end=:end where OID=:OID");
            $stmt -> execute(array('OID' => $OID, 'start' => $row['start'],'end'=> $end));  
            //for detail status
            $stmt = $conn -> prepare("update detail set sta=:sta where OID=:OID");
            $stmt -> execute(array('OID' => $OID, 'sta' => 'Finished'));  
            // $stmt = $conn -> prepare("insert into foodorder(OID, account, shopname, sta, start, end, totalprice, distance, type) values(:OID, :account, :shopname, :sta, :start, :end, :totalprice, :distance, :type)");
            // $stmt -> execute(array('OID' => $OID, 'account' => $_SESSION['Account'], 'shopname' => $row['shopname'], 'sta' => 'Finished', 'start' => $row['start'], 'end' => $end, 'totalprice' => $row['totalprice'], 'distance' => $row['distance'], 'type' => $row['type']));  
        }
        else{
            // echo 'bad';
            throw new Exception('done failed!');
        }

    }
    else{
        throw new Exception('Something error!');
    }

    header('location: nav.php');
    exit();

} 
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
catch(Exception $e){
    $msg=$e->getMessage();
        echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
                <script>
                alert("$msg");
                window.location.replace("nav.php");
                </script>
            </body>
            </html>
        EOT;
  }


$conn = null;
?>