<!DOCTYPE html>
<html>

<body>



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


require_once('db.php');
$way = $_GET['q'];
$account = $_SESSION['Account'];
$shop = $_SESSION['店名'];

if($way == "ALL")
{   
    $shop = $_SESSION['店名'];
    echo "<div> Payment </div>";
    $sql="SELECT `TID`, `totalprice` ,`time`, `tradershop`, `action` FROM `transaction` WHERE (account = '$account' or accountshop = '$shop') AND `action` = 'Payment'";
    $result = mysqli_query($link,$sql);
    echo '
        <div class="row">
        <div class="  col-xs-14">
        <table class="table" style=" margin-top: 15px;">
        <thead>
        <tr>
        <th scope="col">Record ID</th>
        <th scope="col">Action</th>
        <th scope="col">Time</th>
        <th scope="col">Trader</th>
        <th scope="col">Amount change</th>
        </tr>
        </thead>
        <tbody>';

    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['TID'] . "</td>";
        echo "<td>". $row['action'] . "</td>";
        echo "<td>" . $row['time'] . "</td>";
        echo "<td>" . $row['tradershop'] . "</td>";
        echo "<td>" . $row['totalprice'] . "</td>";
        echo "</tr>";
        }
    echo "</table>";

    echo "<br />"; //空白行

    echo "<div> Receive </div>";
    $shop = $_SESSION['店名'];
    $sql="SELECT `TID`, `totalprice` ,`time`, `trader`, `action` FROM `transaction` WHERE (account = '$account' or accountshop = '$shop') AND `action` = 'Receive'";
    $result = mysqli_query($link,$sql);
    echo '
        <div class="row">
        <div class="  col-xs-14">
        <table class="table" style=" margin-top: 15px;">
        <thead>
        <tr>
        <th scope="col">Record ID</th>
        <th scope="col">Action</th>
        <th scope="col">Time</th>
        <th scope="col">Trader</th>
        <th scope="col">Amount change</th>
        </tr>
        </thead>
        <tbody>';
    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['TID'] . "</td>";
        echo "<td>". $row['action'] . "</td>";
        echo "<td>" . $row['time'] . "</td>";
        echo "<td>" . $row['trader'] . "</td>";
        echo "<td>" . $row['totalprice'] . "</td>";
        echo "</tr>";
        }
    echo "</table>";

    echo "<br />"; //空白行

    echo "<div> Recharge </div>";
    $sql="SELECT `TID`, `totalprice` ,`time`, `action` FROM `transaction` WHERE account = '$account' AND `action` = 'Recharge'";
    $result = mysqli_query($link,$sql);
    echo'
        <div class="row">
        <div class="  col-xs-14">
        <table class="table" style=" margin-top: 15px;">
        <thead>
        <tr>
        <th scope="col">Record ID</th>
        <th scope="col">Action</th>
        <th scope="col">Time</th>
        <th scope="col">Amount change</th>
        </tr>
        </thead>
        <tbody>';
    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['TID'] . "</td>";
        echo "<td>". $row['action'] . "</td>";
        echo "<td>" . $row['time'] . "</td>";
        echo "<td>" . $row['totalprice'] . "</td>";
        echo "</tr>";
        }
    echo "</table>";

    echo "<br />"; //空白行
}
elseif($way == "Payment")
{
    echo "<div> Payment </div>";
    $sql="SELECT `TID`, `totalprice` ,`time`, `tradershop`, `action` FROM `transaction` WHERE (account = '$account' or accountshop = '$shop') AND `action` = 'Payment'";
    $result = mysqli_query($link,$sql);
    echo '
        <div class="row">
        <div class="  col-xs-14">
        <table class="table" style=" margin-top: 15px;">
        <thead>
        <tr>
        <th scope="col">Record ID</th>
        <th scope="col">Action</th>
        <th scope="col">Time</th>
        <th scope="col">Trader</th>
        <th scope="col">Amount change</th>
        </tr>
        </thead>
        <tbody>';
    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['TID'] . "</td>";
        echo "<td>". $row['action'] . "</td>";
        echo "<td>" . $row['time'] . "</td>";
        echo "<td>" . $row['tradershop'] . "</td>";
        echo "<td>" . $row['totalprice'] . "</td>";
        echo "</tr>";
        }
    echo "</table>";

    echo "<br />"; //空白行

}
elseif($way == "Receive")
{
    echo "<div> Receive </div>";
    $shop = $_SESSION['店名'];
    $sql="SELECT `TID`, `totalprice` ,`time`, `trader`, `action` FROM `transaction` WHERE (account = '$account' or accountshop = '$shop') AND `action` = 'Receive'";
    $result = mysqli_query($link,$sql);
    echo'
        <div class="row">
        <div class="  col-xs-14">
        <table class="table" style=" margin-top: 15px;">
        <thead>
        <tr>
        <th scope="col">Record ID</th>
        <th scope="col">Action</th>
        <th scope="col">Time</th>
        <th scope="col">Trader</th>
        <th scope="col">Amount change</th>
        </tr>
        </thead>
        <tbody>';
    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['TID'] . "</td>";
        echo "<td>". $row['action'] . "</td>";
        echo "<td>" . $row['time'] . "</td>";
        echo "<td>" . $row['trader'] . "</td>";
        echo "<td>" . $row['totalprice'] . "</td>";
        echo "</tr>";
        }
    echo "</table>";

    echo "<br />"; //空白行
}
elseif($way == "Recharge")
{
    echo "<div> Recharge </div>";
    $sql="SELECT `TID`, `totalprice` ,`time`, `action` FROM `transaction` WHERE account = '$account' AND `action` = 'Recharge'";
    $result = mysqli_query($link,$sql);
    echo'
        <div class="row">
        <div class="  col-xs-14">
        <table class="table" style=" margin-top: 15px;">
        <thead>
        <tr>
        <th scope="col">Record ID</th>
        <th scope="col">Action</th>
        <th scope="col">Time</th>
        <th scope="col">Amount change</th>
        </tr>
        </thead>
        <tbody>';
    while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['TID'] . "</td>";
        echo "<td>". $row['action'] . "</td>";
        echo "<td>" . $row['time'] . "</td>";
        echo "<td>" . $row['totalprice'] . "</td>";
        echo "</tr>";
        }
    echo "</table>";

    echo "<br />"; //空白行
}

?>
</body>
</html>