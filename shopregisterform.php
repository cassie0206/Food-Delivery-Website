<?php
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

<!DOCTYPE html>

<html>
<!-- <div id="menu1" class="tab-pane fade"> -->
        <h3> Start a business </h3>

        <form method="post" action="shopregister.php">  
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-2">
                <label for="ex5">shop name</label>
                <input class="form-control" id="ex5" name="shopName" oninput="check_shop(this.value);"  type="text" >
                <label id="shopMsg" style="color:red; font-size:40%;"></label>
              </div>
              <div class="col-xs-2">
                <label for="ex5">shop category</label>
                <input class="form-control" id="ex5" name="shopCategory"  type="text" >
              </div>
              <div class="col-xs-2">
                <label for="ex6">latitude</label>
                <input class="form-control" id="ex6" name="latitude"  type="text" >
              </div>
              <div class="col-xs-2">
                <label for="ex8">longitude</label>
                <input class="form-control" id="ex8" name="longitude"  type="text" >
              </div>
            </div>
          </div>
        
          <div class=" row" style=" margin-top: 25px;">
            <div class=" col-xs-3">
              <button type="submit" class="btn btn-primary"  >register</button>
            </div>
          </div>
        </form>
<!-- </div> -->

</html>
