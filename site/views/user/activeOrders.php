<?php 
    require "../../database.php";
    require "../../permissions.php";

    userPermission();

    function getOrders($conn,$userID){

        $orders = $conn->prepare("SELECT * FROM orders WHERE user_id=:user_id");
        $orders->bindParam("user_id",$userID);
        $orders->execute();
        $orders = $orders->fetchAll();

        return $orders;
    }

    $userID = $_SESSION["user"]["id"];

    $orders = getOrders($conn,$userID);
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
                        <?php echo $order["name"];?>
                    </td>
                    <td>
                        <a href="orderDetails.php?id=<?php echo $order["id"]?>">Bekijk</a>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>