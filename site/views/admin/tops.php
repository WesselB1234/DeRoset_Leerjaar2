<?php 
    require "../../database.php";
    require "../../permissions.php";

    adminPermission("../login.php");
    
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

    function getProducts($conn){

        $products = $conn->prepare("SELECT * FROM products");
        $products->execute();
        $products = $products->fetchAll();

        return $products;
    }

    function createWeeklyTop($conn,$productID){

        $create = $conn->prepare("INSERT INTO tops_products(product_id) 
        VALUES(:product_id)");
        
        $create->bindParam("product_id",$productID);
        $create->execute();
    }

    function createDailyTop($conn,$productID){

        $present = getDailyTop($conn);
        
        if(!empty($present)){
            deleteTop($conn,$present["top_id"]);
        }

        $create = $conn->prepare("INSERT INTO tops_products(product_id,is_daily_top)
        VALUES (:product_id,1)");

        $create->bindParam("product_id",$productID);
        $create->execute();
    }

    function deleteTop($conn,$topID){

        $delete = $conn->prepare("DELETE FROM tops_products WHERE id=:id");
        $delete->bindParam("id",$topID);
        $delete->execute();
    }

    if(isset($_POST["daily_id"])){
        
        $productID = $_POST["daily_id"];
        createDailyTop($conn,$productID);
    }

    if(isset($_POST["weekly_id"])){

        $productID =  $_POST["weekly_id"];
        createWeeklyTop($conn,$productID);
    }

    if(isset($_GET["delete"])){

        $productID = $_GET["delete"];
        deleteTop($conn,$productID);
    }

    $products = getProducts($conn);
    $daily = getDailyTop($conn);
    $weekly = getWeeklyTops($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    Daglijks

    <form action="tops.php" method="POST">
        <select name="daily_id" method="POST">
            <?php foreach($products as $product) {?>
                <option value="<?php echo $product["id"];?>"><?php echo $product["name"];?></option>
            <?php }?>
        </select>
        <input type="submit">
    </form>

    <table>
        <thead>
            <th>
                Naam
            </th>
            <th>
                Prijs per liter
            </th>
            <th>
                Merk
            </th>
        </thead>
        <tbody>
            <tr>
                <?php if(!empty($daily)){?>
                    <td>
                        <?php echo $daily["product_name"];?>
                    </td>
                    <td>
                        € <?php echo $daily["price_liter"];?>
                    </td>
                    <td>
                        <?php echo $daily["brand_name"];?>
                    </td>
                    <td>
                        <a href="tops.php?delete=<?php echo $daily["top_id"]?>">Verwijder</a>
                    </td>
                <?php } 
                else{?>
                    <td>
                        none
                    </td>
                    <td>
                        none
                    </td>
                    <td>
                        none
                    </td>
                <?php }?>
            </tr>
        </tbody>
    </table>

     Weeklijk

    <form action="tops.php" method="POST">
        <select name="weekly_id">
            <?php foreach($products as $product) {?>
                <option value="<?php echo $product["id"];?>"><?php echo $product["name"];?></option>
            <?php }?>
        </select>
        <input type="submit">
    </form>
    
    <table>
        <thead>
            <th>
                Naam
            </th>
            <th>
                Prijs per liter
            </th>
            <th>
                Merk
            </th>
        </thead>
        <tbody>
            <?php foreach($weekly as $weekTop){?>
            <tr>
                <td>
                    <?php echo $weekTop["product_name"];?>
                </td>
                <td>
                    <?php echo $weekTop["price_liter"];?>
                </td>
                <td>
                    <?php echo $weekTop["brand_name"];?>
                </td>
                <td>
                    <a href="tops.php?delete=<?php echo $weekTop["top_id"]?>">Verwijder</a>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>