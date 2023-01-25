<?php
session_start();

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

$link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function(){
      $(document).on('click','#a',(function(){
        //alert($(this).attr("data-id"));
        var shop_name = $(this).attr("data-id");
        $.ajax({
          url:'menu.php',
          method:"post",
          data:{shop_name:shop_name},
          success: function(data){
            // alert(data);
            console.log(data);
            $('#menu_detail').html(data);
            console.log('"#' + shop_name + '"');
           $('#macdonald').modal("show");
          },
          error: function(){
            console.log("failed");
          }
        });
      }));

    });
    $(document).off('focusin.modal');
  </script>
  <script>
		function check_shop(shopName){
			if(shopName != ""){
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
					var message;
					//console.log(this.readyState);
					//console.log(this.status);
					if(this.readyState == 4 && this.status == 200){
						document.getElementById("shopMsg").innerHTML = this.responseText;
					}
				};
				
				xhttp.open("POST", "check_shop.php", true);
				//window.location.replace("check_account.php");
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("shopName="+shopName);	
			}
			else{
				documnet.getElementById("shopMsg").innerHTML = "";
			}
		}
	</script>
  <script>  
    $(document).ready(function(){  
            $('.view_data6').click(function(){  
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
      $(document).on('click','#abc',(function(){
        //alert($(this).attr("data-id"));
        var o = $(this).attr("data-id");
        $.ajax({
          url:'orderdetail.php',
          method:"post",
          data:{o:o},
          success: function(data){
            //alert(o);
            console.log(data);
            $('#order_detail1').html(data);
            console.log('"#' + shop_name + '"');
           $('#od1').modal("show");
          },
          error: function(){
            console.log("failed");
          }
        });
      }));

    });
  </script>

  <script>
      function showRecord(str) {
        if (str=="") {
          document.getElementById("txtHint").innerHTML="";
          return;
        }
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
          if (this.readyState==4 && this.status==200) {
            document.getElementById("txtHint").innerHTML=this.responseText;
          }
        }
        xmlhttp.open("GET","getrecord.php?q="+str,true);
        xmlhttp.send();
      }
  </script>


  <title>Hello, world!</title>
</head>

<body>
 
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand " href="#">Ubereats</a>
      </div>

    </div>
  </nav>
  <div class="container">

    <ul class="nav nav-tabs">
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#menu1">shop</a></li>
      <li><a href="#myOrder">My Order</a></li>
      <li><a href="#ShopOrder">Shop Order</a></li>
      <li><a href="#record">Transaction Record</a></li>
      <li><a href="logout.php">Logout</a></li>


    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <h3>Profile</h3>
        <div class="row">
          <div class="col-xs-12">
             Accouont : <?php echo $_SESSION['Account']?><br>
             Username : <?php echo $_SESSION['username']?><br>
             role : <?php echo $_SESSION['role']?><br>   
             PhoneNumber: <?php echo $_SESSION['phone']?><br>  
             location: 
             <?php 
             echo $_SESSION['latitude'];
             echo ' , ';
             echo $_SESSION['longitude'];
             ?>
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
            data-target="#location">edit location</button>
            <!--  -->
            <form action="editlocation.php" method="post" class="fh5co-form animate-box" data-animate-effect="fadeIn">
            <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">edit location</h4>
                  </div>
                  <div class="modal-body">
                    <label class="control-label " for="latitude">latitude</label>
                    <input type="text" class="form-control" name='editlat'id="latitude" placeholder="enter latitude">
                      <br>
                      <label class="control-label " for="longitude">longitude</label>
                    <input type="text" class="form-control" name='editlon'id="longitude" placeholder="enter longitude">
                  </div>
                  <div class="modal-footer">
                    <input type="submit" value="Edit" class="btn btn-primary">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Edit</button> -->
                  </div>
                </div>
              </div>
            </div>
            </form>
            <!--  -->
            wallet balance: 
            <?php 
                  $dbservername='localhost';
                  $dbname='hw2';
                  $dbusername='root';
                  $dbpassword='000000';
                  $link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);
                  $account = $_SESSION['Account'];
                  $walletsql = "SELECT balance FROM user WHERE account = '$account'   ; ";    
                  $result2 = mysqli_query($link,$walletsql);
                  $row2 = mysqli_fetch_assoc($result2);
                  $wallet = $row2['balance'];
                  echo $wallet;
            ?>
            <!-- Modal -->
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Add value</button>
            <!-- <form action="order.php" method="post"> -->
              <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add value</h4>
                  </div>
                  <form action="reload.php" method="post">
                  <div class="modal-body">
                    <input type="text" class="form-control" placeholder="enter add value" name="value">
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Add</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- 
                
             -->
        <h3>Search</h3>
        <div class=" row  col-xs-8">
          <form class="form-horizontal" action="search.php" method="post">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="shopName" placeholder="Enter Shop name">
              </div>
              <label class="control-label col-sm-1" for="distance">distance</label>
              <div class="col-sm-5">
                <select class="form-control" name ="sel1"  id="sel1">
                  <option>---</option>
                  <option>near</option>
                  <option>medium </option>
                  <option>far</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">
                <input type="text" name="lowPrice" class="form-control">
              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">
                <input type="text" name="highPrice" class="form-control">
              </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
                <input type="text" list="Meals" class="form-control" id="Meal" name="typeMeal" placeholder="Enter Meal">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" name="category" id="category" placeholder="Enter shop category">
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary">Search</button>
            </div>
          </form>
          <?php
                //echo $_SESSION['flag']; 
                $num = count($_SESSION['nameCategory']);
                //echo $num;
          ?>
        </div>
        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">shop name</th>
                  <th scope="col">shop category</th>
                  <th scope="col">Distance</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $i = 1;
                    foreach($_SESSION['nameCategory'] as $key => $val):
                ?>
                <tr>
                    <th scope="row"><?php echo($i++)?></th>
                    <td><?php echo $key?></td>
                    <td><?php echo $val?></td>
                    <td>
                      <?php 
                          $close = 500;
                          $far = 1500;
                      
                          $stmt = $conn -> prepare("select ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) as dis from shop where 店名=:shopName");
                          $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'shopName' => $key));
                          $row = $stmt -> fetch();

                          if($row['dis'] < 500){
                            echo 'near';
                          }
                          else if($row['dis'] >= 500 && $row['dis'] <= 1500){
                            echo 'medium';
                          }
                          else{
                            echo 'far';
                          }
      
                      
                      ?> 
                    </td>
                    <td>  <button type="button" class="btn btn-info view_data " data-toggle="modal" data-target="#macdonald" id='a' data-id = "<?php echo $key; ?>" name = "open">Open menu</button></td>
                </tr>
                <?php 
                    if($num != 0){
                      $_SESSION['nameCategory'] = array();
                    }
                    endforeach;
                ?>
              </tbody>
            </table>

                <!-- Modal -->
            <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content" id="menu_detail"> 
                </div> 
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div id="menu1" class="tab-pane fade">
        
        <?php
            if($_SESSION['role'] == 'user')
            {
                require_once ("shopregisterform.php");
            }
            else if( $_SESSION['role'] == 'manager')
            {
                require_once ("shopready.php");
            }
            // header('location: route.php');
            // exit();
        ?>
      </div>

      <div id="myOrder" class="tab-pane fade">
        
        <hr>
        <div class="row">
          <div class="col-xs-12">
            <form class="form-horizontal" action="orderFindMy.php" method="post">
                <label class="control-label col-sm-1" for="distance">Status</label>
                    <div class="col-sm-3">

                        
                        <select class="form-control" name ="desiredStatus"  id="desiredStatus">
                        <option>All</option>
                        <option>Finished</option>
                        <option>Not Finish</option>
                        <!-- <option>0</option> -->
                        <option>Cancel</option>
                        </select>
                    </div>
                    <button type="submit" style="margin-left: 18px;"class="btn btn-primary">Search</button>
            
            </form>
          </div>
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
                    <th scope="col">Cancel</th>
                  
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $num = count($_SESSION['orderList']);
                      //echo($num);
                      foreach($_SESSION['orderList'] as $key => $val):
                  ?>
                  <?php if($val['sta']==0): ?>
                  <tr>
                  <th scope="row"><?php echo($key)?></th>
                      <td><?php echo ('Not Finish')?></td>
                      <td><?php echo $val['start']?></td>
                      <td><?php
                      //echo("b");
                              if($val['sta'] != 0){
                                  echo $val['end'];
                              } 
                          ?></td>
                      <td><?php echo $val['shopname']?></td> 
                      <td><?php echo $val['totalprice']?></td>                 
                      <td>  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#od1" id='abc' data-id = "<?php echo $key; ?>" name = "open">order details</button></td>
                      <td>
                          <form action="cancel.php"  method="post" enctype="multipart/form-data" >
                              <button type="submit" class="btn btn-danger view_data6" id = "<?php echo $key; ?>" name = "cancel" >Cancel</button>
                          </form>
                      </td>
                  </tr>
                  <?php endif; ?>
                  <?php if($val['sta']!=0): ?>
                  <tr>
                  <th scope="row"><?php echo($key)?></th>
                      <td><?php echo $val['sta']?></td>
                      <td><?php echo $val['start']?></td>
                      <td><?php echo $val['end'];?></td>
                      <td><?php echo $val['shopname']?></td> 
                      <td><?php echo $val['totalprice']?></td>                 
                      <td>  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#od1" id='abc' data-id = "<?php echo $key; ?>" name = "open">order details</button></td>
                      
                  </tr>
                  <?php endif; ?>
                  <?php 
                          if($num != 0){
                          //unset($_SESSION['nameCategory']);
                          $_SESSION['orderList'] = array();
                          }

                      endforeach;
                  ?>
                  
                </tbody>
              </table>
              <div class="modal fade" id="od1"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content" id="order_detail1">   
                </div>
      
              </div>
            </div>
            </div>
        </div>
      </div>

      <div id="ShopOrder" class="tab-pane fade">
        <?php
            if($_SESSION['role'] == 'user')
            {
                //include_once ("nonShopOrder.php");
            }
            else if( $_SESSION['role'] == 'manager')
            {
                $num1 = count($_SESSION['orderList1']);
                //echo($num1);
                include_once ("shopOrder.php");
            }
        ?>
      </div>
      <!-- <div id="record" class="tab-pane fade ">
        <hr>
        <body>
          <form>
            <select style="font-size:24px;" name="recordsearch" onchange="showRecord(this.value)" >
                <option value="">---</option>
                <option value="ALL">ALL</option>
                <option value="Payment">Payment</option>
                <option value="Receive">Receive</option>
                <option value="Recharge">Recharge</option>
            </select>
          </form>
          <br>
          <div id="txtHint" style="font-size:24px;"><b>Person info will be listed here.</b></div>
        </body>
      </div>  -->

      <div id="record" class="tab-pane fade">
        <?php
            include_once ("record.php");
        ?>
      </div>
      



    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script>
    $(document).ready(function () {
      $(".nav-tabs a").click(function () {
        $(this).tab('show');
      });
    });
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>