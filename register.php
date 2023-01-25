<?php
session_start();

$_SESSION['Authenticated']=false;

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

$errors = array();
$flag = false;
$formErrors = array();
$formFlag = false;

try {
  //empty
    if (empty($_POST['name'])){
      array_push($errors, 'Please input name.'."\\n");
      $flag = true;
    }
    if (empty($_POST['phonenumber'])){
      array_push($errors, 'Please input phone number.'."\\n");
      $flag = true;
    }
    if (empty($_POST['Account'])){
      array_push($errors, 'Please input account.'."\\n");
      $flag = true;
    }
    if (empty($_POST['password'])){
      array_push($errors, 'Please input password.'."\\n");
      $flag = true;
    }
    if (empty($_POST['re-password'])){
      array_push($errors, 'Please type password again.'."\\n");
      $flag = true;
    }
    if (empty($_POST['latitude'])){
      array_push($errors, 'Please input latitude.'."\\n");
      $flag = true;
    }
    if (empty($_POST['longitude'])){
      array_push($errors, 'Please input longitude.'."\\n");
      $flag = true;
    }
    if($flag){
      throw new Exception("".join(array_values($errors)));
    }

    //input form wrong  
    $searchString = " ";
    $replaceString = "";
    $outputString = str_replace($searchString, $replaceString, $_POST['name']);

    if (!ctype_alnum($outputString)){
      array_push($formErrors, 'name can only contain alphbets or numbers.'."\\n");
      $formFlag = true;
    }
    if (strlen($_POST['phonenumber']) != 10 || !ctype_digit(strval($_POST['phonenumber']))){
      array_push($formErrors, 'phone number can only be 10-digits.'."\\n");
      $formFlag = true;
    }
    if (!ctype_alnum($_POST['Account'])){
      array_push($formErrors, 'account can only contain alphbets or numbers.'."\\n");
      $formFlag = true;
    }
    if (!ctype_alnum($_POST['password'])){
      array_push($formErrors, 'password can only contain alphbets or numbers.'."\\n");
      $formFlag = true;
    }
    if (!ctype_alnum($_POST['re-password'])){
      array_push($formErrors, 'password can only contain alphbets or numbers.'."\\n");
      $formFlag = true;
    }
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

    //password != repassword
    if($_POST['password'] != $_POST['re-password']){
      throw new Exception('password and retype one are not identical.');
    }


    $name = $_POST['name'];
    $phonenumber = $_POST['phonenumber'];
    $account = $_POST['Account'];
    $pwd = $_POST['password'];
    //$repwd = $_POST['re-password'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //prepare
    $stmt = $conn -> prepare("select account from user where account=:account");
    $stmt->execute(array('account' => $account));
    //check
    if($stmt -> rowCount() == 0){
      $salt = strval(rand(1000, 9999));
      $hashValue = hash('sha256', $salt.$pwd);
      $point = 'POINT(' . $longitude . ' ' . $latitude . ')';
      $balance = 0;

      // $stmt = $conn -> prepare("insert into user(account, password, username, phone, location, balance, salt) values(:account, :password, :username, :phone, ST_GeomFromText(:point), :balance, :salt)");
      // $stmt -> execute(array('account' => $account, 'password' => $hashValue, 'username' => $name, 'phone' => $phonenumber, 'point' => $point, 'balance' => $balance, 'salt' => $salt));
      
      $stmt = $conn -> prepare("insert into user(account, password, username, phone,location, latitude, longitude, balance, salt) values(:account, :password, :username, :phone, ST_GeomFromText(:point), :latitude, :longitude, :balance, :salt)");
      $stmt -> execute(array('account' => $account, 'password' => $hashValue, 'username' => $name, 'phone' => $phonenumber,'point' => $point, 'latitude' => $latitude,'longitude' => $longitude, 'balance' => $balance, 'salt' => $salt));

      
      $_SESSION['Authenticated'] = true;
      $_SESSION['Account'] = $account;
      echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
          <script>
          alert("Register success !!");
          window.location.replace("index.php");
          </script>
        </body>
        </html>
      EOT;
      exit();
    }
    else{
      throw new Exception('Account has been registered.');
    }
} 
catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
catch(Exception $e){
  $msg=$e->getMessage();
  session_unset(); 
  session_destroy(); 

  echo <<<EOT
    <!DOCTYPE html>
    <html>
    <body>
      <script>
      alert("$msg");
      window.location.replace("sign-up.php");
      </script>
    </body>
    </html>
  EOT;

}

$conn = null;
?>