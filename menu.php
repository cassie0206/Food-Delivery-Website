<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<!doctype html>
<html>
  <?php 
    session_start(); 
    if (!isset($_SESSION['Authenticated']) ||$_SESSION['Authenticated'] != true)
    {
      $msg = "Please sign in first!";
      echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
                <script>
                alert("$msg");
                window.location.replace("index.php");
                </script>
            </body>
            </html>
        EOT;
    }
  ?>

<div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">menu</h4>
        </div>
        <form action="order.php" method="post">
        <div class="modal-body">
         <!--  -->
        
         <div class="row">
          <div class="  col-xs-12">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Picture</th>
                 
                  <th scope="col">meal name</th>
               
                  <th scope="col">price</th>
                  <th scope="col">Quantity</th>
                
                  <th scope="col">Order check</th>
                </tr>
              </thead>
              <tbody>
              
              <?php 
              $dbservername='localhost';
              $dbname='hw2';
              $dbusername='root';
              $dbpassword='000000';
              $link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);
              $a= $_POST['shop_name'];
              $_SESSION['shop_name'] = $a;        //訂購的店名是在$_SESSION['shop_name']
              $sql1 = "SELECT * FROM `food` WHERE 店名 = '$a' ";
              $result1 = mysqli_query($link,$sql1);
              $datas = array();
              if ($result1) {
                //echo 'www';
                  // mysqli_num_rows方法可以回傳我們結果總共有幾筆資料
                  if (mysqli_num_rows($result1)>0) {
                      // 取得大於0代表有資料
                      // while迴圈會根據資料數量，決定跑的次數
                      // mysqli_fetch_assoc方法可取得一筆值
                      
                      while ($row = mysqli_fetch_assoc($result1)) {
                          // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                          //echo 'www';
                          $datas[] = $row;
                      }
                  }
                  // 釋放資料庫查到的記憶體
                  mysqli_free_result($result1);
              }

          foreach ($datas as $key1 => $row) : 
        ?>
                <tr>
                  <th scope="row"><?php echo($key1 +1 ); ?></th>
                  <td><?php $img=$row['Img'];
                            $logodata = $img;
              echo '<img src="data:'.$row['ImgType'].';base64,' . $logodata . '" width="70" height="70" / >'; ?></td>
                  
                  <td><?php echo($row['foodname'] ); ?></td>
                
                  <td><?php echo($row['price'] ); ?> </td>
                  <td><?php echo($row['quantity'] ); ?></td>

                  <td> 
                      <input type="number" value="0" name=<?php echo $row['foodname'];?> min="0" >
                  </td>
                </tr>
                <?php endforeach;
      mysqli_close($link); 
    ?>
              
              </tbody>
            </table>
          </div>

         </div>
        

         <!--  -->
        </div>
        
        <div class="modal-footer">
          <div style="font-size:20px;">取貨方式
          <select style="font-size:20px;" class = "justify-content-center" name="way">
              <option value="">----</option>
              <option value="Delivery">Delivery</option>
              <option value="Pick-up">Pick-up</option>
          </select>
          </div>

          <button type="submit" class="btn btn-info ">Calculate the price</button>
        </div>
      </form>



</html>