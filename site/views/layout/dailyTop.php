<?php
    function getDailyTop($conn){
        
        $daily = $conn->prepare("SELECT *,products.name as 'product_name',tops_products.id as 'top_id',brands.name as 'brand_name' FROM tops_products 
        JOIN products ON products.id = tops_products.product_id
        JOIN brands ON brands.id = products.brand_id
        WHERE is_daily_top = 1");

        $daily->execute();
        $daily = $daily->fetch();

        return $daily;
    }

    $daily = getDailyTop($conn);
?>

<div class="dailyTopContainer">
    <br>
    dagelijks
    <br>
    <h3><?php echo $daily["product_name"];?></h3>

    <a href="">Bestel</a>
</div>