<?php
    include "../../database.php";
    
    if(!isset($_GET["id"]) or !isset($_SESSION["user"])){
        header("location: index.php");
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

    function getCartFromUser($conn,$userID){

        $duplicate = $conn->prepare("SELECT * FROM carts WHERE user_id=:user_id");
        $duplicate->bindParam("user_id",$userID);
        $duplicate->execute();
        $duplicate = $duplicate->fetch();

        if(!empty($duplicate)){
            return $duplicate;
        }

        return false;
    }

    function createCart($conn,$userID){

        $createCart = $conn->prepare("INSERT INTO carts(user_id,is_deliver) VALUES (:user_id,false)");
        $createCart->bindParam("user_id",$userID);
        $createCart->execute();
    }

    function setLiterCartProduct($conn,$cartID,$productID,$liter){

        $updateProductCart = $conn->prepare("UPDATE carts_products SET liter=:liter WHERE product_id=:product_id AND cart_id=:cart_id");
        $updateProductCart->bindParam("liter",$liter);
        $updateProductCart->bindParam("product_id",$productID);
        $updateProductCart->bindParam("cart_id",$cartID);
        $updateProductCart->execute();
    }

    function createCartProduct($conn,$cartID,$productID,$liter){

        $createProductCart = $conn->prepare("INSERT INTO carts_products(cart_id,product_id,liter) VALUES
        (:cart_id,:product_id,:liter)");
            
        $createProductCart->bindParam("cart_id",$cartID);
        $createProductCart->bindParam("product_id",$productID);
        $createProductCart->bindParam("liter",$liter);
        $createProductCart->execute();
    }

    if(isset($_POST["liter"])){
        
        $userID = $_SESSION["user"]["id"];
        $productID = $_GET["id"];
        $liter = $_POST["liter"];

        $cart = getCartFromUser($conn,$userID);
        $cartID = $cart["id"];

        if($cart == false){
    
            createCart($conn,$userID);
            $cart = getCartFromUser($conn,$userID);
        }
        
        if(findDuplicateProductCart($conn,$productID) == false){
            createCartProduct($conn,$cartID,$productID,$liter);
        }
        else{
            setLiterCartProduct($conn,$cartID,$productID,$liter);
        }

        header("location: index.php");
    }

    $id = $_GET["id"];
    $product = $conn->prepare("SELECT * FROM products WHERE id= :id");
    $product->bindParam("id",$id);
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