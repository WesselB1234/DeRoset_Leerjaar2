<?php
    include "../database.php";

    if(!isset($_SESSION["user"])){
        header("location: index.php");
    } 

    $user_id = $_SESSION["user"]["id"];

    $cartOrders = $conn->prepare("SELECT *,products.name as product_name FROM carts_products
    JOIN products ON products.id = carts_products.product_id
    WHERE user_id=:user_id");
    
    $cartOrders->bindParam("user_id",$user_id);
    $cartOrders->execute();
    $cartOrders = $cartOrders->fetchAll();
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
</body>
</html>