
<?php

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

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

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
//echo "Connected successfully";
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//prepare
?>

<!DOCTYPE html>
<hr>
<!-- <div id="menu1" class="tab-pane fade"> -->
<div class="row">
        <div class="col-xs-12">
        <form class="form-horizontal" action="getTransaction.php" method="post">
            <label class="control-label col-sm-1" for="distance">Action</label>
                <div class="col-sm-3">

                    
                    <select class="form-control" name ="desiredAction"  id="desiredAction">
                    <option>All</option>
                    <option>Payment</option>
                    <option>Receive</option>
                    <option>Recharge</option>
                    </select>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary">Search</button>
        </div>
        </form>
</div>



<div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">Record ID</th>
                  <th scope="col">Action</th>
                  <th scope="col">Time</th>
                  <th scope="col">Trader</th>
                  <th scope="col">Amount Change</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    // $i = 1;
                    $num = count($_SESSION['recordList']);
                    //echo key($_SESSION['recordList']);
                    foreach($_SESSION['recordList'] as $key => $val):
                ?>

                <tr>
                <th scope="row"><?php echo($key)?></th>
                    <td><?php echo $val['action']?></td>
                    <td><?php echo $val['time']?></td>
                    <td><?php echo $val['trader']?></td>
                    <td><?php echo $val['totalprice']?></td> 
                </tr>


                <?php 
                        if($num != 0){
                        //unset($_SESSION['nameCategory']);
                            $_SESSION['recordList'] = array();
                        }

                    endforeach;
                ?>

              </tbody>
            </table>
            </table>
            <div class="modal fade" id="od"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content" id="order_detail">   
                </div>
      
              </div>
            </div>
</div>
</div>

</html>


<?php
$conn = null;
?>