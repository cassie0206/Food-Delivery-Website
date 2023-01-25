<!DOCTYPE html>
<?php
    require_once('db.php');

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

    $account=$_SESSION['Account'];
    $sql = "SELECT shop.* FROM user LEFT JOIN shop ON user.account=shop.account  WHERE user.account = '$account' ";
    $result = mysqli_query($link,$sql);
    if(mysqli_num_rows($result) > 0){
      while($row = $result -> fetch_assoc()){
        $_SESSION['店名'] = $row['店名'];
        $_SESSION['餐點類型'] = $row['餐點類型'];
      };
  }

    // $row = mysqli_fetch_assoc($result);
    // $_SESSION['店名'] = $row['店名'];
    // $_SESSION['餐點類型'] = $row['餐點類型'];
?>
<html>
<!-- <hr>
<div id="menu1" class="tab-pane fade"> -->
        <h3> <?php echo $_SESSION['店名']; ?></h3>

        <form method="post" action="shopregister.php">  
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-2">
                <label for="ex5">shop name</label>
                <input class="form-control" id="ex5" name="shopName" oninput="check_shop(this.value);" placeholder=<?php echo $_SESSION['店名'] ;?> type="text" disabled>
                <label id="shopMsg" style="color:red; font-size:40%;"></label>
              </div>
              <div class="col-xs-2">
                <label for="ex5">shop category</label>
                <input class="form-control" id="ex5" name="shopCategory" placeholder=<?php echo $_SESSION['餐點類型'] ;?> type="text" disabled>
              </div>
              <div class="col-xs-2">
                <label for="ex6">latitude</label>
                <input class="form-control" id="ex6" name="latitude" placeholder=<?php echo $_SESSION['latitude'] ;?> type="text" disabled>
              </div>
              <div class="col-xs-2">
                <label for="ex8">longitude</label>
                <input class="form-control" id="ex8" name="longitude" placeholder=<?php echo $_SESSION['longitude'] ;?> type="text" disabled>
              </div>
            </div>
          </div>
        </form>

        <h3>ADD</h3>

    <form action="add.php" method="post" enctype="multipart/form-data">
    <div class="form-group ">
      <div class="row">

        <div class="col-xs-6">
          <label for="ex3">meal name</label>
          <input class="form-control" id="ex3" type="text"  name="meal_name">
        </div>
      </div>
      <div class="row" style=" margin-top: 15px;">
        <div class="col-xs-3">
          <label for="ex7">price</label>
          <input class="form-control" id="ex7" type="text" name="price">
        </div>
        <div class="col-xs-3">
          <label for="ex4">quantity</label>
          <input class="form-control" id="ex4" type="text" name="quantity">
        </div>
      </div>


      <div class="row" style=" margin-top: 25px;">

        <div class=" col-xs-3">
          <label for="ex12">上傳圖片</label>
          <input id="myFile" type="file" name="myFile" multiple class="file-loading">

        </div>
        <div class=" col-xs-3">

          <button style=" margin-top: 15px;" type="submit" class="btn btn-primary">Add</button>
        </div>
      </div>
    </div>
    </form>
   
<!-- </div>     -->
<?php
        include_once ('display.php');
    ?>
</html>
