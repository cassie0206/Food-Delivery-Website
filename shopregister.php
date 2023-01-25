<?php
$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

$errors = array();
$flag = false;
$formErrors = array();
$formFlag = false;

try{
    if(!isset($_SESSION['Account'])){
      echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
                <script>
                alert("Please sign in first !!");
                window.location.replace("index.php");
                </script>
            </body>
            </html>
        EOT;
        exit();
    }
    //empty
    if (empty($_POST['shopName'])){
        array_push($errors, 'shop name field required !!'."\\n");
        $flag = true;
      }
      if (empty($_POST['shopCategory'])){
        array_push($errors, 'shop category field required !!'."\\n");
        $flag = true;
      }
      if (empty($_POST['latitude'])){
        array_push($errors, 'latitude field required !!'."\\n");
        $flag = true;
      }
      if (empty($_POST['longitude'])){
        array_push($errors, 'longitude field required !!'."\\n");
        $flag = true;
      }
      if($flag){
        throw new Exception("".join(array_values($errors)));
      }

      //input form wrong
      if (!is_float((float)$_POST['latitude'])){
        array_push($formErrors, 'latitude need to be floating point and fall fall between -90 and 90 degrees.'."\\n");
        $formFlag = true;
      }
      if($_POST['latitude'] < -90 || $_POST['latitude'] > 90){
        array_push($formErrors, 'latitude need to be floating point and fall fall between -90 and 90 degrees.'."\\n");
        $formFlag = true;
      }
      if (!is_float((float)$_POST['longitude'])){
        array_push($formErrors, 'longitude need to be floating point and fall fall between -90 and 90 degrees.'."\\n");
        $formFlag = true;
      }
      if($_POST['longitude'] < -180 || $_POST['longitude'] > 180){
        array_push($formErrors, 'longitude need to be floating point and fall fall between -90 and 90 degrees.'."\\n");
        $formFlag = true;
      }
      if($formFlag){
        throw new Exception("".join(array_values($formErrors)));
      }

    $shopName = $_POST['shopName'];
    $shopCategory = $_POST['shopCategory'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $account = $_SESSION['Account'];

    //echo $account;

    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare

    $stmt = $conn -> prepare("select 店名 from shop where 店名=:shopName");
    $stmt -> execute(array('shopName' => $shopName));

    if($stmt->rowCount() == 0){
        $point = 'POINT(' . $longitude . ' ' . $latitude . ')';
        //echo 'test: '.$shopName." ".$account." ".$point." ".$shopCategory;
        $stmt = $conn -> prepare("insert into shop(店名, account, 位置, 餐點類型) values(:shopName, :account, ST_GeomFromText(:point), :shopCategory)");
        $stmt -> execute(array('shopName' => $shopName, 'account' => $account, 'point' => $point, 'shopCategory' => $shopCategory)); 
        $_SESSION['role'] = 'manager';
        $_SESSION['myShop'] = $shopName;
        $_SESSION['orderList'] = array();
        $_SESSION['orderList1'] = array();

        echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
                <script>
                alert("Register success !!");
                window.location.replace("nav.php");
                </script>
            </body>
            </html>
        EOT;
        exit();
    }
    else{
        throw new Exception("Shop name has been registerd !!");
    }
  
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
catch(Exception $e){
    $msg = $e ->getMessage();

    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
            alert("$msg");
            window.location.replace("nav.php#menu1");
            </script>
        </body>
        </html>
    EOT;
}

$conn = null;
?> 