<?php
    require "../../../database.php";

    if(!isset($_GET["id"]) or !isset($_SESSION["user"])){
        header("location: index.php");
    }   

    function getUser($conn,$userID){

        $user = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $user->bindParam("id",$userID);
        $user->execute();
        $user = $user->fetch();

        return $user;
    }

    function getOrder($conn,$orderID){
        
        $order = $conn->prepare("SELECT * FROM orders WHERE id=:order_id");
        $order->bindParam("order_id",$orderID);
        $order->execute();
        $order = $order->fetch();

        return $order;
    }

    function getOrderProducts($conn,$orderID){
        
        $orderProducts = $conn->prepare("SELECT * FROM orders_products 
        JOIN products ON products.id = orders_products.product_id
        WHERE order_id = :order_id");

        $orderProducts->bindParam("order_id",$orderID);
        $orderProducts->execute();
        $orderProducts = $orderProducts->fetchAll();

        return $orderProducts;
    }

    function collectOrder($conn,$orderID){

    }

    function cancelOrder($conn,$orderID){

    }
    
    $orderID = $_GET["id"];

    if(isset($_GET["collected"])){
        collectOrder($conn,$orderID);
    }
     
    if(isset($_GET["cancel"])){
        cancelOrder($conn,$orderID);
    }

    $order = getOrder($conn,$orderID);
    $orderProducts = getOrderProducts($conn,$orderID);

    $userID = $order["user_id"];
    $user = getUser($conn,$userID);
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
        <br>
        <h2> <a href="../users/details.php?user_id=<?php echo $user["id"];?>"><?php echo $user["username"];?></a></h2>
        <thead>
            <th>Name</th>
            <th>Liter</th>
        </thead>
        <tbody>
            <?php foreach($orderProducts as $orderProduct){?>
                <tr>
                    <td>
                        <?php echo $orderProduct["name"];?>
                    </td>
                    <td>
                        <?php echo $orderProduct["liter"];?>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>

    <a href="details.php?collected=true">Compleet</a>
    <br>
    <a href="details.php?cancel=true">Annuleer</a>
</body>
</html>