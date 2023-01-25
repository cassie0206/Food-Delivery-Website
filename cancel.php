<?php

session_start();
// echo($_SESSION['oid']);
$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';
//echo($_SESSION['oid']);
try {
    $OID = $_SESSION['oid'];
    $sta="cancel";

    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare
    $stmt = $conn -> prepare("select account, shopname, start, totalprice, distance from foodorder where OID=:OID");
    $stmt->execute(array('OID' => $OID));


    if($stmt -> rowCount() == 1){
        $row = $stmt -> fetch();
        date_default_timezone_set("Asia/Taipei");
        $end = date("Y-m-d h:i:sa");
        $end = substr($end, 0, 19);

        $useraccount = $row['account'];
        $shopname = $row['shopname'];
        $start = $row['start'];
        $totalprice = $row['totalprice'];
        $distance = $row['distance'];

        $stmt = $conn -> prepare("select account from shop where 店名=:shopname");
        $stmt->execute(array('shopname' => $shopname));
        $row = $stmt -> fetch();
        $shopaccount =$row['account'];
        
        // $stmt = $conn -> prepare("delete from foodorder where OID=:OID");
        // $stmt->execute(array('OID' => $OID));

        if($stmt -> rowCount() != 0){

            //check money amount
            $innerstmt = $conn -> prepare("select balance from user where account=:account");
            $innerstmt->execute(array('account' => $shopaccount));
            if($innerstmt -> rowCount() == 1){
                //echo(111);
                $innerrow = $innerstmt -> fetch();
                if(!empty($innerrow['balance'])){
                    //echo($innerrow['balance']);
                    if($innerrow['balance'] < $row['totalprice'] ){
                        throw new Exception('balance not enough!');
                    }

                }
                
            }
            else{
                throw new Exception('cancel failed!');
            }

            //for money user
            $stmt = $conn -> prepare("update user set balance = balance + :totalprice where account=:account");
            $stmt->execute(array('totalprice' => $totalprice, 'account' => $useraccount));
            //for money shop
            $stmt = $conn -> prepare("update user set balance = balance - :totalprice where account=:account");
            $stmt->execute(array('totalprice' => $totalprice, 'account' => $shopaccount));
            
            //for order status
            $stmt = $conn -> prepare("update foodorder set sta=:sta where OID=:OID");
            $stmt -> execute(array('OID' => $OID, 'sta' => $sta));  
            $stmt = $conn -> prepare("update foodorder set start=:start, end=:end where OID=:OID");
            $stmt -> execute(array('OID' => $OID, 'start' => $start,'end'=>$end));  

            //for detail status
            $stmt = $conn -> prepare("update detail set sta=:sta where OID=:OID");
            $stmt -> execute(array('OID' => $OID, 'sta' => $sta));  
            // $stmt = $conn -> prepare("insert into foodorder(OID, account, shopname, sta, start, end, totalprice, distance, type) values(:OID, :account, :shopname, :sta, :start, :end, :totalprice, :distance, :type)");
            // $stmt -> execute(array('OID' => $OID, 'account' => $row['account'], 'shopname' => $row['shopname'], 'sta' => 'Cancel', 'start' => $row['start'], 'end' => $end, 'totalprice' => $row['totalprice'], 'distance' => $row['distance'], 'type' => $row['type']));  
            
            //for transaction payment
            // $sql = "INSERT INTO  `transaction` (account,`action`,`trader`, `totalprice`) VALUE ('$account', 'Recharge', '$account' , $add ) ";    
            // $result = mysqli_query($link,$sql);
            //$TID = 1;
            $stmt = $conn -> prepare("insert into transaction( account, action, trader, totalprice) values( :account, :action, :trader, :totalprice)");
            $stmt->execute(array( 'account' => $useraccount, 'action' => 'Receive','trader' => $shopname, 'totalprice' => '+'.$totalprice));
            //$TID++;
            $stmt = $conn -> prepare("insert into transaction(accountshop, action, tradershop, totalprice) values( :accountshop, :action, :tradershop, :totalprice)");
            $stmt->execute(array( 'accountshop' =>$shopname, 'action' => 'Payment', 'tradershop'=>$useraccount, 'totalprice' => '-'.$totalprice));

        //      //買家
        // $sql = "INSERT INTO  transaction (account,`action`,`trader`, tradershop , totalprice) VALUE ('$account', 'Payment' , '$shopuser', '$shop' , -$total ) ";    
        // $result = mysqli_query($link,$sql);
        // //賣家
        // $sql = "INSERT INTO  transaction (account,`accountshop`,`action`,`trader`, totalprice) VALUE ('$shopuser','$shop', 'Receive', '$account' , $total ) ";    
        // $result = mysqli_query($link,$sql);



            //for food quantity 
            $stmt = $conn -> prepare("select shopname, foodname, quantity from detail where OID=:OID");
            $stmt->execute(array('OID' => $OID));
            if($stmt -> rowCount() != 0){
                $msg = $stmt -> rowCount();
                while($row = $stmt -> fetch()){
                    $innerstmt = $conn -> prepare("update food set quantity = quantity + :quantity where 店名=:shopname and foodname=:foodname");
                    $innerstmt->execute(array('quantity' => $row['quantity'], 'shopname' => $row['shopname'], 'foodname' => $row['foodname']));
                }
            }
        }
        else{
            // echo 'bad';
            throw new Exception('cancel failed!');
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