<?php  
    session_start();
    require_once('db.php');

    $account = $_SESSION['Account'];
    $shop = $_SESSION['shop_name'];
    $total = $_SESSION['total'];
    $fee= $_SESSION['delivery'];

    $order_food = $_SESSION['order_food'];
    $order_food_number = $_SESSION['order_food_number'];
    $order_food_price = $_SESSION['order_food_price'];
    $food_ori_num = $_SESSION['food_ori_num'];


    $food_array = array();
    $food_num_array = array();
    $food_price_array = array();
    $food_ori_num_array = array();


    $food_array = explode(" ",$order_food);
    $food_num_array = explode(" ",$order_food_number);
    $food_price_array = explode(" ",$order_food_price);
    $food_ori_num_array = explode(" ",$food_ori_num);

    $except = "";
    $arrLength = count($food_array);  //食物數量

   

    try{
        //餘額查詢
        $walletsql = "SELECT balance FROM user WHERE account = '$account'   ; ";    
        $result2 = mysqli_query($link,$walletsql);
        $row2 = mysqli_fetch_assoc($result2);
        $wallet = $row2['balance'];
        if($total > $wallet)
        {       
            
            $except .= "##餘額不足##" ;
            // throw new Exception($say);
        }

        for($i=0 ; $i<$arrLength-1 ; $i++)  //找尋商家是否有相對食物
        {
            $checksql = "SELECT foodname FROM food WHERE foodname = '$food_array[$i]'   ; "; 
            $result3 = mysqli_query($link,$checksql);
            // $row3 = mysqli_fetch_assoc($result3);
            if(mysqli_num_rows($result3)==0)
            {
                $except .= "##此餐點不存在##";
            }
        }

        $check = 0;
        $exceptfood = "";
        for($i=0 ; $i<$arrLength-1 ; $i++)          //店家更改數量
        {
            $checkfood = "SELECT quantity FROM food WHERE foodname = '$food_array[$i]'   ;";
            $result4 = mysqli_query($link,$checkfood);
            $row4 = mysqli_fetch_assoc($result4);
            if($row4['quantity'] < $food_num_array[$i] && $food_ori_num_array[$i]!=$row4['quantity'] )
            {   
                $check = 1;
                $except .= $food_array[$i] ;
                $except .= ",";
            }
        }
        if($check == 1)
        {
            $except .= "數量經商店修改以低於購買數量，請再次輸入##";
        }
        

        for($i=0 ; $i<$arrLength-1 ; $i++)          //店家更改價錢
        {
            $checkprice = "SELECT price FROM food WHERE foodname = '$food_array[$i]'   ;";
            $result5 = mysqli_query($link,$checkprice);
            $row5 = mysqli_fetch_assoc($result5);
            if($row5['price'] != $food_price_array[$i])
            {
                $except .= ("##店家在您訂購期間內更改商品價格，請再次下單，謝謝##");
            }
        }
       




        if($_SESSION['food_exceed'] != "")  //確認是否有符合數量
        {
            $except .= "##";
            $except .= $_SESSION['food_exceed'] ;
            $except .= "數量已超過店家數量，請再次輸入##";
        }

        if($except != "")  //確認是否訂購失敗
        {
            throw new Exception($except);
        }
        else
        {
        $sql = "SELECT account FROM shop WHERE 店名 = '$shop'";  
        $result = mysqli_query($link,$sql);
        $row = mysqli_fetch_assoc($result);
        $shopuser = $row['account'] ;
        $status = 0;
        
        $sql = "INSERT INTO  foodorder (account,`sta`,shopname,totalprice,delivery) VALUE ('$account','$status ', '$shop' , $total,$fee ) ";    
        $result = mysqli_query($link,$sql);
        //買家
        $sql = "INSERT INTO  transaction (account,`action`,`trader`, tradershop , totalprice) VALUE ('$account', 'Payment' , '$shopuser', '$shop' , -$total ) ";    
        $result = mysqli_query($link,$sql);
        //賣家
        $positivetotal = '+'.$total;
        $sql = "INSERT INTO  `transaction` (account,`accountshop`,`action`,`trader`, `totalprice`) VALUE ('$shopuser','$shop', 'Receive', '$account' , '$positivetotal' ) ";    
        $result = mysqli_query($link,$sql);


        //計算目前OID
        $sql = "SELECT AUTO_INCREMENT
        FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'hw2'
        AND   TABLE_NAME   = 'foodorder';";
        $result1 = mysqli_query($link,$sql);
        $row = mysqli_fetch_assoc($result1);
        $current_oid = $row['AUTO_INCREMENT']-1;
        // echo $row['AUTO_INCREMENT']; // Print a single column data
        // echo $order_food[0];
        for($i=0 ; $i<$arrLength-1 ; $i++)
        {
            $copysql = "SELECT price,`Img`,`ImgType` FROM food WHERE foodname = '$food_array[$i]'   ; "; 
            $result4 = mysqli_query($link,$copysql);
            $row = mysqli_fetch_assoc($result4);
            $food_price = $row['price'];
            $Img = $row['Img'];
            $ImgType = $row['ImgType'];
            $sql = "INSERT INTO  detail (OID,shopname,foodname,quantity, price , Img , ImgType , sta) VALUE ($current_oid,'$shop', '$food_array[$i]' , $food_num_array[$i] , $food_price  , '$Img' , '$ImgType' , '0') ";  
            mysqli_query($link,$sql);
        }

        //商家賺錢
        $shopearn = "UPDATE user
        INNER JOIN
            shop ON user.account = shop.account 
        SET 
            balance = balance + $total
        WHERE
            shop.店名 = '$shop';";
        mysqli_query($link,$shopearn);

        //買家扣錢
        $accountpay = "UPDATE user
        SET 
            balance = balance - $total
        WHERE
            user.account = '$account';";
        mysqli_query($link,$accountpay);
        
        //商家扣數量


        for($i=0 ; $i<$arrLength-1 ; $i++)
        {
            $minusquantity = "UPDATE food
            SET 
                quantity = quantity - $food_num_array[$i]
            WHERE
                food.foodname = '$food_array[$i]';";  
            mysqli_query($link,$minusquantity);
        }


        throw new Exception("訂購成功"  );
        unset($_SESSION['order_food']); 
        unset($_SESSION['order_food_number']);  
        unset($_SESSION['total']);
        }
    }
    catch(Exception $e){
        $msg=$e->getMessage();

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