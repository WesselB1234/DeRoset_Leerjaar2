<?php
    include "../../database.php";
    
    if(!isset($_GET["product_id"]) or !isset($_SESSION["user"])){
        header("location: ../login.php");
    }   

    function findDuplicateProductCart($conn,$productID){

        $duplicate = $conn->prepare("SELECT * FROM carts_products WHERE product_id=:product_id");
        $duplicate->bindParam("product_id",$productID);
        $duplicate->execute();
        $duplicate = $duplicate->fetch();

        if(!empty($duplicate)){
            return true;
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

    if(isset($_POST["liter"])){
        
        $userID = $_SESSION["user"]["id"];
        $productID = $_GET["id"];
        $liter = $_POST["liter"];
        
        if(findDuplicateProductCart($conn,$productID) == false){
            createCartProduct($conn,$userID,$productID,$liter);
        }
        else{
            setLiterCartProduct($conn,$userID,$productID,$liter);
        }

        header("location: index.php");
    }

    $productID = $_GET["product_id"];
    $product = $conn->prepare("SELECT * FROM products WHERE id= :product_id");
    $product->bindParam("product_id",$productID);
    $product->execute();
    $product = $product->fetch();
    
    if(empty($product)){
        header("location: ../error.php");
    }
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
    <h1><?php echo $product["name"]?></h1>
    <br>
    <label for="">Prijs per liter</label>
    <?php echo $product["price_liter"];?>
    <br>
    info bla bla bla

    <br>
    <br>
    <form action="product.php?id=<?php echo $product["id"];?>" method="POST">
        <input type="number" min=0 step=".01" name="liter" placeholder="1.34">
        <input type="submit">
    </form>
</body>
</html>