<?php session_start(); ?>

<?php
    require_once('db.php');
    try{
        if(empty($_POST['meal_name'])||empty($_POST['price'])||empty($_POST['quantity'])){
            throw new Exception("請輸入商品明和價錢和數量");
        }
        else if($_FILES['myFile']['size'] == 0){
            throw new Exception("請輸入商品照片");
        }
        else if($_FILES['myFile']['size'] > 0){
            $meal_name = $_POST['meal_name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $ID = $_SESSION['店名'];
    
            //開啟圖片檔
            $file = fopen($_FILES["myFile"]["tmp_name"], "rb");
            // 讀入圖片檔資料
            $fileContents = fread($file, filesize($_FILES["myFile"]["tmp_name"])); 
            //關閉圖片檔
            fclose($file);
            //讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
            $fileContents = base64_encode($fileContents);
            //組合查詢字串
            $imgType=$_FILES["myFile"]["type"];
            $sql = "INSERT INTO  `food` (`店名`,`foodname` ,`Img` , `ImgType`, `price`,`quantity`) VALUE ('$ID', '$meal_name'  , '$fileContents' ,'$imgType', $price,'$quantity') ";

            // 用mysqli_query方法執行(sql語法)將結果存在變數中
            $result = mysqli_query($link,$sql);

            // 如果有異動到資料庫數量(更新資料庫)
            if (mysqli_affected_rows($link)>0) {
                // 如果有一筆以上代表有更新
                // mysqli_insert_id可以抓到第一筆的id
                $new_id= mysqli_insert_id ($link);
                echo "新增成功";
                header('location: nav.php');
            }
            elseif(mysqli_affected_rows($link)==0) {
                echo "無資料新增";
            }
            else {
                echo "{$sql} 語法執行失敗，錯誤訊息: " . mysqli_error($link);
            }
            mysqli_close($link); 
            //header('location: nav.php');
            //exit();

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
    
    $conn=null;

    
 ?>





