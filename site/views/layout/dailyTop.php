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
    <div class="title">
        Smaak van de dag
    </div>
    <div class="productCard">
        <img class ="productImage" src="../images/products/<?php echo $daily["image"];?>">
        <div class="costIndicator">€ <?php echo $daily["price_liter"];?></div>
        <div><?php echo $daily["product_name"]?></div>
    </div>
    <a href="">Bestel</a>
</div>