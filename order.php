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
<?php session_start(); ?>
    
      <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">訂單 訂購店家：<?php echo($_SESSION['shop_name'] ); ?></h4>
        </div>
        <div class="modal-body">
         <!--  -->
  
         <div class="row">
          <div class="  col-xs-12">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">Picture</th>
                 
                  <th scope="col">meal name</th>
               
                  <th scope="col">price</th>
                  <th scope="col">Order Quantity</th>
                </tr>
              </thead>
              <tbody>
              <?php 
require_once('db.php');
$a= $_SESSION['shop_name'] ;
$lat1 = $_SESSION['latitude'];
$lng1 = $_SESSION['longitude'];
$sql1 = "SELECT * FROM food WHERE 店名 = '$a' ";
    
              $result1 = mysqli_query($link,$sql1);
              $datas = array();
              if ($result1) {
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
            $total = 0;
            $food_exceed = "";
            $order_food = "";
            $order_food_number = "";
            $food_ori_num = "";
            $order_food_price = "";
            $checkzero = 0;
          foreach ($datas as $key1 => $row) : 
            $food = rtrim( $row['foodname']," ");
            
            if($_POST[$food]<= 0){  //跳過沒有訂的
              $checkzero += $_POST[$food];
              continue;
            }
            else{
              
              $checkzero += $_POST[$food];





              $order_food .= "$food";
              $order_food .= " ";
              $order_food_number .= "$_POST[$food]";
              $order_food_number .= " ";
              $sql2 = "SELECT quantity , price FROM food WHERE foodname = '$food' ";
              $result2 = mysqli_query($link,$sql2);
              $food_detail = mysqli_fetch_assoc($result2);
              // echo $food_quantity['quantity'];
              $price = $food_detail['price'];
              $order_food_price .= "$price";
              $order_food_price .= " ";
              $quantity = $food_detail['quantity'];
              $food_ori_num .= "$quantity";
              $food_ori_num .=" ";
              if($_POST[$food] > $food_detail['quantity'])
              {
                $food_exceed .= "$food";
                $food_exceed .= " ";
              }
              $total += (($row['price'])*$_POST[$food]);
            }
        ?>
                <tr>
                  <td><?php $img=$row['Img'];
                            $logodata = $img;
              echo '<img src="data:'.$row['ImgType'].';base64,' . $logodata . '" width="70" height="70" / >'; ?></td>
                  
                  <td><?php echo($row['foodname'] ); ?></td>
                
                  <td><?php echo($row['price'] ); ?> </td>
                  <td><?php echo($_POST[$food] ); ?></td>

                </tr>
                <?php endforeach;
                $_SESSION['order_food'] = $order_food;
                $_SESSION['order_food_number'] = $order_food_number;
                $_SESSION['food_exceed'] = $food_exceed;
                $_SESSION['order_food_price'] = $order_food_price;
                $_SESSION['food_ori_num'] = $food_ori_num;
      
          
      if($checkzero == 0)
      {
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
          <script>
          alert("總商品數量不為零");
          window.location.replace("nav.php");
          </script>
        </body>
        </html>
      EOT;

      }
    ?>
              </tbody>
            </table>
          </div>

         </div>
        

         <!--  -->
<?php
$findadd = "SELECT latitude , longitude FROM shop WHERE 店名 = '$a' ";
$addr = mysqli_query($link,$findadd);
$row = mysqli_fetch_array($addr);
$lat2 = $row['latitude'];
$lng2 = $row['longitude'];


if($_POST['way'] == "Delivery")
{
$distance = round(6378.138*2*asin(sqrt(pow(sin( ($lat1*pi()/180-$lat2*pi()/180)/2),2)+cos($lat1*pi()/180)*cos($lat2*pi()/180)* pow(sin( ($lng1*pi()/180-$lng2*pi()/180)/2),2)))*1000);
$tax = round($distance/100);
$_SESSION['delivery']=$tax;
if($tax < 10)
{
  $tax = 10;
  $_SESSION['delivery']=$tax;
}
}
elseif($_POST['way'] == "Pick-up")
{
  $tax = 0;
  $_SESSION['delivery']=$tax;
}
elseif($_POST['way'] == "")
{
  echo <<<EOT
          <!DOCTYPE html>
          <html>
          <body>
            <script>
            alert("請輸入正確配送方式");
            window.location.replace("nav.php");
            </script>
          </body>
          </html>
        EOT;
}

mysqli_close($link); 
?>



        </div>
        <div class="modal-footer">
        <div class="container">Subtotal&emsp;&emsp;$<?php echo $total;?></div>
        <div class="container">delivery fee&emsp;&emsp;$<?php echo $tax;?></div>
        <div class="container">Total Price&emsp;&emsp;$<?php $fin_total = $total+$tax; $_SESSION['total'] = $fin_total; echo  $fin_total;?></div>
          <form action="ordercheck.php" >
          <button type="submit" class="btn btn-default" >Order</button>
          </form>
        </div>
        

</html>