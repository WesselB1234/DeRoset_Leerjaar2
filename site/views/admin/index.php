<?php

    require '../../database.php';
    require '../../permissions.php';

    adminPermission();
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
    admin dashboard

    <a href="orders/index.php">View orders</a>
    <a href="products/index.php">View products</a>
    <a href="tops.php">View tops</a>
    <a href="users/index.php">View Users</a>
</body>
</html>