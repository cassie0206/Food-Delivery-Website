<?php
session_start();


$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

$errors = array();
$flag = false;

try{
  
    if(empty($_POST['editlat'])||empty($_POST['editlon'])){
        throw new Exception("請輸入經度和緯度");

    }
    if (!is_float((float)$_POST['editlat'])){
        throw new Exception("經度不正確");
      }
      if($_POST['editlat'] < -90 || $_POST['editlat'] > 90){
        throw new Exception("經度不正確");
      }
      if (!is_float((float)$_POST['editlon'])){
        throw new Exception("緯度不正確");
      }
      if($_POST['editlon'] < -180 || $_POST['editlon'] > 180){
        throw new Exception("緯度不正確");
      }
    else{
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $account = $_SESSION['Account'];
        $latitude = $_POST['editlat'];
        $longitude = $_POST['editlon'];
   
        $sql1="UPDATE user SET latitude = '$latitude' where account = '$account'";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
    
        $sql2="UPDATE user SET longitude = '$longitude' where account = '$account'";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
    
    
        $conn = new mysqli($dbservername,$dbusername,$dbpassword,$dbname);
        $sql = "select * from user where account = '$account'";
        $result = $conn->query($sql);
        
    
        if(mysqli_num_rows($result) > 0){
            while($row = $result -> fetch_assoc()){
                $_SESSION['latitude']=$row['latitude'];
                $_SESSION['longitude']=$row['longitude'];
        
            };
        }
        
        header('location: nav.php');
    }


}

catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}
catch(Exception $e){
    $msg=$e->getmessage();
    //session_unset();
    //session_destroy();
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

$conn=null;

?>