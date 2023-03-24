<?php
    require "../../../database.php";
    require "../../../permissions.php";

    $orderID = null;
    $order = null;

    $userID = null;
    $user = null;

    if(!isset($_GET["id"])){
        header("location: index.php");
    }
    else{
        userPermission(); 
        
        $orderID = $_GET["id"];
        $order = getOrder($conn,$orderID);

        $userID = $_SESSION["user"]["id"];
        $user = getUser($conn,$userID);

        if($order["user_id"] != $userID){
            header("location: index.php");
        }
    }

    function getUser($conn,$userID){

        $user = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $user->bindParam("id",$userID);
        $user->execute();
        $user = $user->fetch();

        return $user;
    }

    function getOrder($conn,$orderID){
        
        $order = $conn->prepare("SELECT *,orders.id as 'order_id',locations.name as 'location_name'FROM orders
        JOIN locations on locations.id = orders.location_id 
        WHERE orders.id=:order_id");

        $order->bindParam("order_id",$orderID);
        $order->execute();
        $order = $order->fetch();

        return $order;
    }

    function getOrderProducts($conn,$orderID){
        
        $orderProducts = $conn->prepare("SELECT *,(products.price_liter * orders_products.liter) as 'total_cost' FROM orders_products 
        JOIN products ON products.id = orders_products.product_id
        WHERE order_id = :order_id");

        $orderProducts->bindParam("order_id",$orderID);
        $orderProducts->execute();
        $orderProducts = $orderProducts->fetchAll();

        return $orderProducts;
    }

    function collectOrder($conn,$orderID){
        
        $update = $conn->prepare("UPDATE orders SET is_collected = 1 WHERE id=:order_id");
        $update->bindParam("order_id",$orderID);
        $update->execute();
    }

    function cancelOrder($conn,$orderID){

        $update = $conn->prepare("UPDATE orders SET is_canceled = 1 WHERE id=:order_id");
        $update->bindParam("order_id",$orderID);
        $update->execute();
    }

    function calculateTotalCostsOrder($conn,$is_deliver,$orderID){

        $totalCost = $conn->prepare("SELECT sum(products.price_liter * orders_products.liter) as 'total_cost' FROM orders_products
        JOIN products ON products.id = orders_products.product_id
        WHERE order_id=:order_id");
        
        $totalCost->bindParam("order_id",$orderID);
        $totalCost->execute();
        $totalCost = $totalCost->fetch()["total_cost"];
        
        if($is_deliver == 1){
            $totalCost+=10;
        }

        return $totalCost;
    }
     
    if(isset($_GET["cancel"]) && $order["is_collected"] == 0){

        cancelOrder($conn,$orderID);
        header("location: index.php");
    }

    $order = getOrder($conn,$orderID);
    $orderProducts = getOrderProducts($conn,$orderID);

    $totalCost = calculateTotalCostsOrder($conn,$order["is_deliver"],$orderID);
    $deliverArray = [0 => "Afhalen",1 => "Bezorgen"];
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
        <h2>Bestelling: <?php echo $order["name"];?></h2>
        <h2> <a href="../users/details.php?user_id=<?php echo $user["id"];?>"><?php echo $user["username"];?></a></h2>
        <thead>
            <th>Name</th>
            <th>Liter</th>
            <th>Kost</th>
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
                    <td>
                        € <?php echo $orderProduct["total_cost"];?>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
    
    <ul>
        <li><?php echo $deliverArray[$order["is_deliver"]];?></li>
        <li>Adres: <?php echo $order["address"];?></li>
        <li>Postcode: <?php echo $order["postalcode"];?></li>
        <li>Locatie: <?php echo $order["location_name"];?></li>
        <li>Telefoonnummer: <?php echo $order["telephone_number"];?></li>
        <li>Bestellings datum: <?php echo $order["order_date"];?></li>
    </ul>

    <h4>Totale kost: € <?php echo $totalCost;?></h4>
   
    <?php if($order["is_collected"] == 1){
    ?>
        Completed
    <?php }?>
    
    <br>
    <?php if($order["is_canceled"] == 0 && $order["is_collected"] == 0){?>
        <a href="details.php?id=<?php echo $order["order_id"];?>&cancel=true">Annuleer</a>
    <?php }
    else if($order["is_canceled"] == 1){
    ?>
        geanuleerd
    <?php }?>
</body>
</html>