<?php 
    require "../database.php";

    function findDuplicateProductCart($conn,$productID){

        $duplicate = $conn->prepare("SELECT * FROM carts_products WHERE product_id=:product_id");
        $duplicate->bindParam("product_id",$productID);
        $duplicate->execute();
        $duplicate = $duplicate->fetch();

        if(!empty($duplicate)){
            return $duplicate;
        }

        return false;
    }

    function setLiterCartProduct($conn,$userID,$productID,$liter){

        $updateProductCart = $conn->prepare("UPDATE carts_products SET liter=:liter WHERE product_id=:product_id AND user_id=:user_id");
        $updateProductCart->bindParam("liter",$liter);
        $updateProductCart->bindParam("product_id",$productID);
        $updateProductCart->bindParam("user_id",$userID);
        $updateProductCart->execute();
    }

    function createCartProduct($conn,$userID,$productID,$liter){

        $createProductCart = $conn->prepare("INSERT INTO carts_products(user_id,product_id,liter) VALUES
        (:user_id,:product_id,:liter)");
            
        $createProductCart->bindParam("user_id",$userID);
        $createProductCart->bindParam("product_id",$productID);
        $createProductCart->bindParam("liter",$liter);
        $createProductCart->execute();
    }
    
    if(isset($_GET["product_id"])){
        
        if(!isset($_GET["product_id"]) or !isset($_SESSION["user"])){
            header("location: login.php");
        } 
        else{
            $userID = $_SESSION["user"]["id"];
            $productID = $_GET["product_id"];
            $liter = 1;
            
            $duplicate = findDuplicateProductCart($conn,$productID);

            if($duplicate == false){
                createCartProduct($conn,$userID,$productID,$liter);
            }
            else{
                setLiterCartProduct($conn,$userID,$productID,$duplicate["liter"] + $liter);
            }
        }
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
    <link rel="stylesheet" href="../css/order.css">
    <title>Document</title>
</head>
<body>
    <?php foreach($products as $product) {?>
        <a href="order.php?product_id=<?php echo $product["id"]?>"><?php echo $product["name"]?>
            <img class ="productImage" src="../images/products/<?php echo $product["image"];?>">
        </a>
    <?php }?>
</body>
</html>