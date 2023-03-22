<?php

    require "../database.php";

    function getUser($email,$conn){

        $user = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $user->bindParam("email",$email);
        $user->execute();

        return $user->fetch();
    }

    if(isset($_POST["email"])){

        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = getUser($email,$conn);

        if((!empty($user) && password_verify($password,$user["password"]))){

            $_SESSION["user"] = $user;

            header("location: user/index.php");
        }
        else{
            echo "login failed";
        }
    }

    if(isset($_GET["logout"]) && isset($_SESSION["user"])){
        unset($_SESSION["user"]);
    }
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
    login
    
    <form action="login.php" method="POST">
        <input type="email" name="email" required>
        <input type="password" id="password" name="password">
        <input type="submit">
    </form>
</body>
</html>