<?php
    session_start();
    require_once('db.php');
    $add = $_POST['value'];
    $account = $_SESSION['Account'];
    try{
      //floor($add)==$add && $add>0
      if(preg_match("/^[1-9][0-9]*$/" ,$add))
      {
        $update = "UPDATE user
        SET 
            balance = balance + $add
        WHERE
            user.account = '$account';";
        mysqli_query($link,$update);
        $stradd = '+'.$add;
        $sql = "INSERT INTO  `transaction` (account,`action`,`trader`, `totalprice`) VALUE ('$account', 'Recharge', '$account' , '$stradd' ) ";    
        $result = mysqli_query($link,$sql);

        throw new Exception("儲值成功");
      }
      else
      {
        throw new Exception("請輸入正整數");
      }
    }
    catch(Exception $e){
      $msg=$e->getMessage();
      // session_unset(); 
      // session_destroy(); 
    
      echo <<<EOT
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
?>