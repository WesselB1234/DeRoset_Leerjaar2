<?php  
    function getWeeklyTops($conn){

        $weekly = $conn->prepare("SELECT *,products.name as 'product_name',tops_products.id as 'top_id',brands.name as 'brand_name' FROM tops_products 
        JOIN products ON products.id = tops_products.product_id
        JOIN brands ON brands.id = products.brand_id
        WHERE is_daily_top = 0");

        $weekly->execute();
        $weekly = $weekly->fetchAll();

        return $weekly;
    }

    $weekly = getWeeklyTops($conn);
?>

<div class="popularTopContainer">
    weeklijks
    <?php foreach($weekly as $top){?>
        <div>
            <h4><?php echo $top["product_name"];?></h4>
        </div>
    <?php }?>
</div>