<?php 
    require "../../database.php";
    require "../../permissions.php";

    userPermission("../login.php"); 

    function getUser($conn,$userID){

        $user = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $user->bindParam("id",$userID);
        $user->execute();
        $user = $user->fetch();

        return $user;
    }

    $userID = $_SESSION["user"]["id"];
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
    <h2><?php echo $user["username"];?></h2>
    <a href="orders/index.php">Bekijk bestellingen</a>
    <a href="settings.php">Gebruikers instellingen</a>
</body>
</html>