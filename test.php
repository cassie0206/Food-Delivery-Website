<?php

session_start();

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';
try {
    if (!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated']!=true){
        echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
                <script>
                alert("Please sign in first !!");
                </script>
            </body>
            </html>
        EOT;
    }

    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $flag = false;
    //unset($_SESSION['nameCategory']);
    //$_SESSION['nameCategory'] = array();

    $close = 500;
    $far = 1500;
    
    if(!empty($_POST['shopName'])){
        $_SESSION['flag'] = 'shop';
        $nameCategory = array();
        $stmt = $conn -> prepare("select 店名, 餐點類型 from shop where upper(店名) like upper(:shopName)");
        $stmt -> execute(array('shopName' => '%'.$_POST['shopName'].'%'));
 
        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                $nameCategory[$row['店名']] = $row['餐點類型'];
            }
            $flag = true;
            $_SESSION['nameCategory'] = $nameCategory;
        }
    }
    if($_POST['sel1'] != '---'){
        $_SESSION['flag'] = 'dis';
        $nameCategory = array();
        $dis = $_POST['sel1'];

        if($dis == 'near'){//近
            $nameCategory = array();
            $stmt = $conn -> prepare("select 店名, 餐點類型
                                        from shop
                                        where ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) < :close");
            $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'close' => $close));
            
            if($stmt -> rowCount() != 0){
                while($row = $stmt -> fetch()){
                    $nameCategory[$row['店名']] = $row['餐點類型'];
                }
                if($flag){
                    $_SESSION['nameCategory'] = array_intersect_key($_SESSION['nameCategory'], $nameCategory);
                }
                else{
                    $flag = true;
                    $_SESSION['nameCategory'] = $nameCategory;
                }
            }

            // $msg = $_SESSION['nameCategory'][0];
            // echo <<< EOT
            // <!DOCTYPE html>
            // <html>
            // <body>
            //     <script>
            //     alert("$msg");
            //     window.location.replace("index.php");
            //     </script>
            // </body>
            // </html>
            // EOT;
            // exit();
        }
        else if($dis == 'medium'){//中
            $nameCategory = array();
            $stmt = $conn -> prepare("select 店名, 餐點類型
                                        from shop
                                        where ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) between :close and :far");
            $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'close' => $close, 'far' => $far));
            
            if($stmt -> rowCount() != 0){
                while($row = $stmt -> fetch()){
                    $nameCategory[$row['店名']] = $row['餐點類型'];
                }
                if($flag){
                    $_SESSION['nameCategory'] = array_intersect_key($_SESSION['nameCategory'], $nameCategory);
                }
                else{
                    $flag = true;
                    $_SESSION['nameCategory'] = $nameCategory;
                }
            }
        }
        else{//遠
            $nameCategory = array();
            $stmt = $conn -> prepare("select 店名, 餐點類型
                                        from shop
                                        where ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) > :far");
            $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'far' => $far));
            
            if($stmt -> rowCount() != 0){
                while($row = $stmt -> fetch()){
                    $nameCategory[$row['店名']] = $row['餐點類型'];
                }
                if($flag){
                    $_SESSION['nameCategory'] = array_intersect_key($_SESSION['nameCategory'], $nameCategory);
                }
                else{
                    $flag = true;
                    $_SESSION['nameCategory'] = $nameCategory;
                }
            }
        }
    }
    if(!empty($_POST['category'])){
        $_SESSION['flag'] = 'category';
        $nameCategory = array();
        $stmt = $conn -> prepare("select 店名, 餐點類型 from shop where upper(餐點類型) like upper(:category)");
        $stmt -> execute(array('category' => '%'.$_POST['category'].'%'));


        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                $nameCategory[$row['店名']] = $row['餐點類型'];
            }
            if($flag){
                $_SESSION['nameCategory'] = array_intersect($_SESSION['nameCategory'], $nameCategory);
            }
            else{
                $flag = true;
                $_SESSION['nameCategory'] = $nameCategory;
            }
        }
    }
    if(!empty($_POST['lowPrice']) || !empty($_POST['highPrice'])){
        $_SESSION['flag'] = 'price';
        $nameCategory = array();
        $low = 0;
        $high = 9E18;

        if(!empty($_POST['lowPrice'])){
            $low = $_POST['lowPrice'];
        }
        if(!empty($_POST['highPrice'])){
            $high = $_POST['highPrice'];
        }
        echo $msg = $low." ".$high;
        $stmt = $conn -> prepare("select 店名, 餐點類型
                                            from shop
                                            where 店名 in (select 店名
                                                                from food
                                                                where price between :low and :high)");
        $stmt -> execute(array('low' => $low, 'high' => $high));

        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                $nameCategory[$row['店名']] = $row['餐點類型'];
            }
            if($flag){
                $_SESSION['nameCategory'] = array_intersect_key($_SESSION['nameCategory'], $nameCategory);
            }
            else{
                $flag = true;
                $_SESSION['nameCategory'] = $nameCategory;
            }
        }
        // echo $stmt -> rowCount();

        // echo <<< EOT
        //     <!DOCTYPE html>
        //     <html>
        //     <body>
        //         <script>
        //         alert("$msg");
        //         window.location.replace("index.php");
        //         </script>
        //     </body>
        //     </html>
        // EOT;
        // exit();
    }
    if(!empty($_POST['typeMeal'])){
        $_SESSION['flag'] = 'meal';
        $nameCategory = array();
        $stmt = $conn -> prepare("select 店名, 餐點類型
                                    from shop
                                    where 店名 in (select 店名
                                                    from food
                                                    where upper(foodname) like upper(:meal))");
        $stmt -> execute(array('meal' => '%'.$_POST['typeMeal'].'%'));

        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                $nameCategory[$row['店名']] = $row['餐點類型'];
            }
            if($flag){
                $_SESSION['nameCategory'] = array_intersect_key($_SESSION['nameCategory'], $nameCategory);
            }
            else{
                $flag = true;
                $_SESSION['nameCategory'] = $nameCategory;
            }
        }
    }
    if(empty($_POST['shopName']) && ($_POST['sel1'] == '---') && empty($_POST['lowPrice']) && empty($_POST['highPrice']) && empty($_POST['typeMeal']) && empty($_POST['category'])){
        $_SESSION['flag'] = 'empty';
        //throw new Exception('Please input at least one requirement !!');
        $nameCategory = array();
        $stmt = $conn -> query("select 店名, 餐點類型 from shop");
        //$stmt -> execute(array('account' => $_SESSION['Account']));
        
        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                //echo $row['店名']." ".$row['餐點類型'];
                $nameCategory[$row['店名']] = $row['餐點類型'];
            }
            $flag = true;
            $_SESSION['nameCategory'] = $nameCategory;
        }
        // else{
        //     $_SESSION['flag'] = 'fakempty';
        // }
        // //echo $_SESSION['nameCategory'];

        // foreach($_SESSION['nameCategory'] as $val){
        //     echo $val;
        // }
        // echo "<br>";
        // echo $flag;
        // //echo $_SESSION['nameCategory'];
    }   
    header('location: nav.php');
    exit();
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


$conn = null;



?>