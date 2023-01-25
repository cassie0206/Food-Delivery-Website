<?php
if(!isset($_SESSION)) 
{ 
    $msg = 'Please sign in first.';
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

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';
$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
//echo "Connected successfully";
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//prepare

$stmt = $conn -> prepare("select 店名 from shop where account=:account");
$stmt -> execute(array('account' => $_SESSION['Account']));
?>
<!DOCTYPE html>

<html>
<!-- <div id="menu1" class="tab-pane fade"> -->
<div class="row">
        <div class="col-xs-12">
            <label class="control-label col-sm-1" for="distance">Status</label>
                <div class="col-sm-3">


                    <select class="form-control" name ="sel1"  id="sel1">
                    <option>All</option>
                    <option>Finished</option>
                    <option>Not Finished</option>
                    <option>Cancel</option>
                    </select>
                </div>
          
        </div>

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
                  <th scope="col">Action</th>
                
                </tr>
              </thead>
              </tbody>
            </table>
          </div>
</div>
</html>


<?php
$conn = null;
?>