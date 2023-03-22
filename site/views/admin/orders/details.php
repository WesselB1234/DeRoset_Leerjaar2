<?php
    require "../../../database.php";

    if(!isset($_GET["id"]) or !isset($_SESSION["user"])){
        header("location: index.php");
    }   

    $orderID = $_GET["id"];

    $order = $conn->prepare("SELECT * FROM orders WHERE id=:order_id");
    $order->bindParam("order_id",$orderID);
    $order->execute();
    $order = $order->fetch();

    $orderProducts = $conn->prepare("SELECT * FROM orders_products 
    JOIN products ON products.id = orders_products.product_id
    WHERE order_id = :order_id");

    $orderProducts->bindParam("order_id",$orderID);
    $orderProducts->execute();
    $orderProducts = $orderProducts->fetchAll();
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
</body>
</html>