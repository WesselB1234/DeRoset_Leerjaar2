<?php 
    require "../../../database.php";
    require "../../../permissions.php";

    adminPermission();

    function getOrders($conn){
        
        $orders = $conn->prepare("SELECT *,orders.id as 'order_id',users.username FROM orders JOIN users on users.id = orders.user_id");
        $orders->execute();
        $orders = $orders->fetchAll();

        return $orders;
    }
    
    function getOrder($conn,$orderID){

        $order = $conn->prepare("SELECT * FROM orders WHERE id=:id");
        $order->bindParam("id",$orderID);
        $order->execute();
        $order = $order->fetch();

        return $order;
    }

    function deleteOrder($conn,$orderID){

        $delete = $conn->prepare("DELETE FROM orders WHERE id=:id");
        $delete->bindParam("id",$orderID);
        $delete->execute();
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

    if(isset($_GET["delete"])){

        $orderID = $_GET["delete"];
        $order = getOrder($conn,$orderID);

        if(!empty($order)){
            if($order["is_collected"] == 1 or $order["is_canceled"] == 1){
                deleteOrder($conn,$orderID);
            }  
        }   
    }

    $orders = getOrders($conn);
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
                Id
            </th>
            <th>
                Bestelling naam
            </th>
            <th>
                Gebruiker
            </th>
            <th>
                Totale kost
            </th>
        </thead>
        <tbody>
            <?php foreach($orders as $order){?>
                <tr>
                    <td>
                        <?php echo $order["order_id"];?>
                    </td>
                    <td>
                        <?php echo $order["name"];?>
                    </td>
                    <td>
                        <a href="../users/details.php?user_id=<?php echo $order["user_id"];?>"><?php echo $order["username"];?></a>
                    </td>
                    <td>
                        €
                        <?php 
                            $totalCost = CalculateTotalCostsOrder($conn,$order["is_deliver"],$order["order_id"]);     
                            echo $totalCost;
                        ?>   
                    </td>
                    <td>
                        <a href="details.php?id=<?php echo $order["order_id"]?>">Bekijk</a>
                    </td>

                    <?php if($order["is_collected"] == 1){?>

                        <td>
                            Completed
                        </td>

                    <?php } else if($order["is_canceled"] == 1){?>
                        
                        <td>
                            Geannuleerd
                        </td>

                    <?php }?>

                    <?php if($order["is_collected"] == 1 or $order["is_canceled"] == 1){?>

                        <td>
                             <a href="index.php?delete=<?php echo $order["order_id"];?>">Verwijder</a>
                        </td>

                    <?php }?>
                </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>