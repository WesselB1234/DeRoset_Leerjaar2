<?php 
    require "../../../database.php";
    require "../../../permissions.php";

    adminPermission();

    $orders = $conn->prepare("SELECT * FROM orders");
    $orders->execute();
    $orders = $orders->fetchAll();
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
                User
            </th>
        </thead>
        <tbody>
            <?php foreach($orders as $order){?>
                <tr>
                    <td>
                        <?php echo $order["id"];?>
                    </td>
                    <td>
                        <?php echo $order["user_id"];?>
                    </td>
                    <td>
                        <a href="details.php?id=<?php echo $order["id"]?>">Bekijk</a>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>