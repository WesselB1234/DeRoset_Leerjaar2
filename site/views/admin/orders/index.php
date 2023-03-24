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
        </thead>
        <tbody>
            <?php foreach($orders as $order){?>
                <tr>
                    <td>
                        <?php echo $order["id"];?>
                    </td>
                    <td>
                        <?php echo $order["name"];?>
                    </td>
                    <td>
                        <a href="../users/details.php?user_id=<?php echo $order["user_id"];?>"><?php echo $order["username"];?></a>
                    </td>
                    <td>
                        <a href="details.php?id=<?php echo $order["order_id"]?>">Bekijk</a>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>