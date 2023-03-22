<?php 
    require "../../database.php";
    
    if(!isset($_SESSION["user"])){
        header("location ../index.php");
    }

    $products = $conn->prepare("SELECT * FROM products");
    $products->execute();
    $products = $products->fetchAll();
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
    <?php foreach($products as $product) {?>
        <a href="product.php?id=<?php echo $product["id"]?>"><?php echo $product["name"]?></a>
        <br>
    <?php }?>
</body>
</html>