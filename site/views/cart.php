<?php
    include "../database.php";
    include "../permissions.php";

    userPermission();

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

    function emptyCart($conn,$cartID,$userID){
        
        $deleteCart = $conn->prepare("DELETE FROM carts WHERE id=:cart_id");
        $deleteCart->bindParam("cart_id",$cartID);
        $deleteCart->execute();

        createCart($conn,$userID);
    }
    
    function getCartOrders($conn,$cartID){
        
        $cartOrders = $conn->prepare("SELECT *,products.name as product_name FROM carts_products
        JOIN products ON products.id = carts_products.product_id
        WHERE cart_id=:cart_id");
        
        $cartOrders->bindParam("cart_id",$cartID);
        $cartOrders->execute();
        $cartOrders = $cartOrders->fetchAll();

        return $cartOrders;
    }

    function createOrder($conn,$userID,$cartID,$isDeliver){
        
        $cartOrders = getCartOrders($conn,$cartID); 

        $createOrder = $conn->prepare("INSERT INTO orders(user_id,is_deliver) VALUES (:user_id,:is_deliver)");
        $createOrder->bindParam("user_id",$userID);
        $createOrder->bindParam("is_deliver",$isDeliver, $conn::PARAM_BOOL);
        $createOrder->execute();

        $orderID = $conn->lastInsertId();

        foreach($cartOrders as $productOrder){
            
            $createProductOrder = $conn->prepare("INSERT INTO orders_products(order_id,product_id,liter) VALUES
            (:order_id,:product_id,:liter)");
            
            $createProductOrder->bindParam("order_id",$orderID);
            $createProductOrder->bindParam("product_id",$productOrder["id"]);
            $createProductOrder->bindParam("liter",$productOrder["liter"]);
            $createProductOrder->execute();
        }
    }

    $userID = $_SESSION["user"]["id"];
    $cart = getCartFromUser($conn,$userID);

    if($cart == false){

        createCart($conn,$userID);
        $cart = getCartFromUser($userID,$conn);
    }
    
    $cartID = $cart["id"];
    
    if(isset($_POST["is_deliver"])){
        
        $isDeliver = $_POST["is_deliver"];

        createOrder($conn,$userID,$cartID,$isDeliver);
        emptyCart($conn,$cartID,$userID);
    }

    $cartOrders = getCartOrders($conn,$cartID);
    
    // INCOMPLETE
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
    <table>
        <thead>
            <th>
                Naam
            </th>
            <th>
                Liter  
            </th>
        </thead>
        <tbody>
            <?php foreach($cartOrders as $order){?>
                <tr>
                    <td>
                        <?php echo $order["product_name"];?>
                    </td>
                    <td>    
                        <?php echo $order["liter"];?>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>

    <br>
    <form action="cart.php" method="POST">
        <input type="radio" name="is_deliver" <?php if($cart["is_deliver"] == 1){?> checked <?php }?> required> Bezorgen
        <br>
        <input type="radio" name="is_deliver" <?php if($cart["is_deliver"] == 0){?> checked <?php }?> required> Afhalen
        <br>
        <input type="text" placeholder="Naam" required>
        <br>
        <input type="text" placeholder="Adres" required>
        <br>
        <input type="text" placeholder="Postcode" required>
        <br>
        <select name="" id="">
            <option value="bruh">bruh</option>
        </select>
        <br>
        <input type="text" placeholder="Adres" required>
        <br>
        <input type="radio"> Aflever adres is hetzelfde als factuuradres
        <br>
        <input type="date">
        <input type="submit">
    </form>
</body>
</html>