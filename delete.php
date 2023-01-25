<?php
    session_start(); 
    require_once('db.php');
    //$PID = $_POST['delete'];
    $PID = $_SESSION["foodname"];

    $SID = $_SESSION['店名'];
    try{
    $checksql = "SELECT `sta` FROM detail WHERE foodname = '$PID' "; 
    $result = mysqli_query($link,$checksql);
    while($row = mysqli_fetch_assoc($result))
    {
        if($row['sta'] == 0)
        {
            throw new Exception( "尚有包含此餐點的訂單未完成");
        }
    }


$sql = "DELETE FROM  `food` WHERE foodname = '$PID' AND 店名 = '$SID' ";

// 用mysqli_query方法執行(sql語法)將結果存在變數中
$result = mysqli_query($link,$sql);

// 如果有異動到資料庫數量(更新資料庫)
if (mysqli_affected_rows($link)>0) {
// 如果有一筆以上代表有更新
// mysqli_insert_id可以抓到第一筆的id
$new_id= mysqli_insert_id ($link);
    echo "刪除成功";
    header('location: nav.php#menu1');
}
elseif(mysqli_affected_rows($link)==0) {
    echo "無資料新增";
}
else {
    echo "{$sql} 語法執行失敗，錯誤訊息: " . mysqli_error($link);
}
mysqli_close($link); 
unset($_SESSION['foodname']);
unset($_SESSION['店名']);
    }
    catch(Exception $e){
        $msg = $e ->getMessage();
    
        echo <<<EOT
            <!DOCTYPE html>
            <html>
            <body>
                <script>
                alert("$msg");
                window.location.replace("nav.php#menu1");
                </script>
            </body>
            </html>
        EOT;
    }
?>