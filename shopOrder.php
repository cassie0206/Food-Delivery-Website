
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
<script>  
    $(document).ready(function(){  
            $('.view_data4').click(function(){  
                var oid = $(this).attr("id");  
                $.ajax({  
                    url:"selectorder.php",  
                    method:"post",  
                    data:{oid:oid},  
                    success:function(data){ 
                      // alert(oid); 
                        $('#food').html(data); 
                        //$('#Hamburger-1').modal("show");     
                    }  
                });  
            });  
    });  
</script>
<script>  
    $(document).ready(function(){  
            $('.view_data5').click(function(){  
                var oid = $(this).attr("id");  
                $.ajax({  
                    url:"selectorder.php",  
                    method:"post",  
                    data:{oid:oid},  
                    success:function(data){  
                      //alert(oid);
                        $('#food').html(data); 
                        //$('#Hamburger-1').modal("show");     
                    }  
                });  
            });  
    });  
</script>
<script>
    $(document).ready(function(){
      $(document).on('click','#ab',(function(){
        //alert($(this).attr("data-id"));
        var o = $(this).attr("data-id");
        $.ajax({
          url:'orderdetail.php',
          method:"post",
          data:{o:o},
          success: function(data){
            //alert(o);
            console.log(data);
            $('#order_detail').html(data);
            console.log('"#' + shop_name + '"');
           $('#od').modal("show");
          },
          error: function(){
            console.log("failed");
          }
        });
      }));

    });
  </script>
<!DOCTYPE html>
<hr>
<!-- <div id="menu1" class="tab-pane fade"> -->
<div class="row">
        <div class="col-xs-12">
        <form class="form-horizontal" action="orderFindShop.php" method="post">
            <label class="control-label col-sm-1" for="distance">Status</label>
                <div class="col-sm-3">

                    
                    <select class="form-control" name ="desiredStatus"  id="desiredStatus">
                    <option>All</option>
                    <option>Finished</option>
                    <option>Not Finish</option>
                    <option>Cancel</option>
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
                  <th scope="col">Order ID</th>
                  <th scope="col">Status</th>
                  <th scope="col">Start</th>
                  <th scope="col">End</th>
                  <th scope="col">Shop name</th>
                  <th scope="col">Total Price</th>
                  <th scope="col">Order Details</th>
                  <th scope="col">Done</th>
                  <th scope="col">Cancel</th>
                
                </tr>
              </thead>
              <tbody>
                <?php 
                    // $i = 1;
                    $num = count($_SESSION['orderList1']);
                     //echo $num;
                    foreach($_SESSION['orderList1'] as $key => $val):
                ?>

                <?php if($val['sta']==0): ?>
                <tr>
                <th scope="row"><?php echo($key)?></th>
                    <td><?php echo ("Not Finish")?></td>
                    <td><?php echo $val['start']?></td>
                    <td><?php
                            if($val['sta'] != 0){
                                echo $val['end'];
                            } 
                        ?></td>
                    <td><?php echo $val['shopname']?></td> 
                    <td><?php echo $val['totalprice']?></td>                 
                    <td>  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#od" id='ab' data-id = "<?php echo $key; ?>" name = "open">order details</button></td>
                    <td> 
                        <form action="done.php"  method="post" enctype="multipart/form-data" > 
                        <button type="submit" class="btn btn-success view_data4" id = "<?php echo $key; ?>" name = "done" >Done</button>
                        </form>
                    </td>
                    <td>
                        <form action="cancel.php"  method="post" enctype="multipart/form-data" >
                            <button type="submit" class="btn btn-danger view_data5" id = "<?php echo $key; ?>" name = "cancel" >Cancel</button>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if($val['sta']!=0): ?>
                <tr>
                <th scope="row"><?php echo($key)?></th>
                    <td><?php echo $val['sta']?></td>
                    <td><?php echo $val['start']?></td>
                    <td><?php
                            if($val['sta'] != 0){
                                echo $val['end'];
                            } 
                        ?></td>
                    <td><?php echo $val['shopname']?></td> 
                    <td><?php echo $val['totalprice']?></td>                 
                    <td>  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#od" id='ab' data-id = "<?php echo $key; ?>" name = "open">order details</button></td>
                    
                </tr>
                <?php endif; ?>


                <?php 
                        if($num != 0){
                        //unset($_SESSION['nameCategory']);
                        $_SESSION['orderList1'] = array();
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