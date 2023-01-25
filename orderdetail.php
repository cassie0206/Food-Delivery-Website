<!doctype html>
<html>
<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">order</h4>
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
              $dbservername='localhost';
              $dbname='hw2';
              $dbusername='root';
              $dbpassword='000000';
              $link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);
              $a= $_POST['o'];
              $sql1 = "SELECT * FROM `detail` WHERE OID = '$a' ";
              $result1 = mysqli_query($link,$sql1);
              $sql2 = "SELECT * FROM `foodorder` WHERE OID = '$a' ";
              $result2 = mysqli_query($link,$sql2);
              $row2 = mysqli_fetch_array($result2);
              if($row2){
                //echo($row2['delivery']);
              }
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
                <td><?php $img=$row['Img'];
                            $logodata = $img;
              echo '<img src="data:'.$row['ImgType'].';base64,' . $logodata . '" width="70" height="70" / >'; ?></td>
                  
                  <td><?php echo($row['foodname'] ); ?></td>
                
                  <td><?php echo($row['price'] ); ?> </td>
                  <td><?php echo($row['quantity'] ); ?></td>

                </tr>
                <?php endforeach;?>
                  
               
              </tbody>
            </table>
          </div>
          </div>
         </div>

         <!--  -->
        
         </div>
        <div class="modal-footer">
          Subtotal  $<?php echo($row2['totalprice']-$row2['delivery']);?>
          <br>
          delivery fee  $<?php echo($row2['delivery']);?>
          <br>
          Total Price  $<?php echo($row2['totalprice']);?>
          <br>
            <!-- <div class="container">Subtotal&emsp;&emsp;$<?php echo($row2['delivery']);?></div>
            <div class="container">delivery fee&emsp;&emsp;$<?php echo($row2['delivery']);?></div>
            <div class="container">delivery fee&emsp;&emsp;$<?php echo($row2['totalprice']);?></div> -->
        </div>

</html>