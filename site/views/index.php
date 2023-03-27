<?php
    require "../database.php";

    function getDailyTop($conn){
        
        $daily = $conn->prepare("SELECT *,products.name as 'product_name',tops_products.id as 'top_id',brands.name as 'brand_name' FROM tops_products 
        JOIN products ON products.id = tops_products.product_id
        JOIN brands ON brands.id = products.brand_id
        WHERE is_daily_top = 1");

        $daily->execute();
        $daily = $daily->fetch();

        return $daily;
    }
    
    function getWeeklyTops($conn){

        $weekly = $conn->prepare("SELECT *,products.name as 'product_name',tops_products.id as 'top_id',brands.name as 'brand_name' FROM tops_products 
        JOIN products ON products.id = tops_products.product_id
        JOIN brands ON brands.id = products.brand_id
        WHERE is_daily_top = 0");

        $weekly->execute();
        $weekly = $weekly->fetchAll();

        return $weekly;
    }

    $daily = getDailyTop($conn);
    $weekly = getWeeklyTops($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- <?php include 'layout/navbar.php';?>

    <br>
    dagelijks
    <br>
    <h3><?php echo $daily["product_name"];?></h3>
    <br>
    <br>
    weeklijks
    <?php foreach($weekly as $top){?>
        <div>
            <h4><?php echo $top["product_name"];?></h4>
        </div>
    <?php }?> -->
    
    <?php include 'layout/setupKit.php';?>
        
        <div class="content">

        </div>
    </div>
</body>
</html>