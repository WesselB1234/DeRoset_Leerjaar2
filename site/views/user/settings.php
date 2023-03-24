<?php 
    require "../../database.php";
    require "../../permissions.php";

    userPermission();

    function getUser($conn,$email){
        
        $user = $conn->prepare("SELECT * FROM users WHERE email=:email");
        $user->bindParam("email",$email);
        $user->execute();
        $user = $user->fetch();

        return $user;
    }

    function deleteUser($conn,$userID){
        
        $delete = $conn->prepare("DELETE FROM users WHERE id=:id");
        $delete->bindParam("id",$userID);
        $delete->execute();

        header("location: ../login.php?logout=true");
    }

    function setUserName($conn,$userID,$name){

        $update = $conn->prepare("UPDATE users SET username=:username WHERE id=:id");
        $update->bindParam("username",$name);
        $update->bindParam("id",$userID);
        $update->execute();

        notify("Gebruikersnaam veranderd");
    }

    function changeUserPassword($conn,$userID,$password){

        $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password=:password WHERE id=:id");
        $update->bindParam("password",$hashedPassword);
        $update->bindParam("id",$userID);
        $update->execute();

        notify("Wachtwoord veranderd");
    }

    function changeUserEmail($conn,$userID,$email){

        $duplicateUser = getUser($conn,$email);

        if(empty($duplicateUser)){

            $update = $conn->prepare("UPDATE users SET email=:email WHERE id=:id");
            $update->bindParam("id",$userID);
            $update->bindParam("email",$email);
            $update->execute();

            notify("Email successfully updated");
        }
        else{
            notify("Email already exists");
        }
    }

    function notify($message){
        echo $message;
    }   

    $userID = $_SESSION["user"]["id"];

    if(isset($_POST["username"])){

        $name = $_POST["username"];
        setUserName($conn,$userID,$name);
    }

    if(isset($_POST["email"])){

        $email = $_POST["email"];
        changeUserEmail($conn,$userID,$email);
    }

    if(isset($_POST["password"])){
        
        $password = $_POST["password"];
        changeUserPassword($conn,$userID,$password);
    }
    
    if(isset($_GET["delete"])){
        deleteUser($conn,$userID);
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

    verander gebruikersnaam
    <form action="settings.php" method="POST">
        <input type="text" name="username" required placeholder="Gebruikersnaam">
        <input type="submit">
    </form>

    <br>
    verander email
    <form action="settings.php" method="POST">
        <input type="text" name="email" required placeholder="Email">
        <input type="submit">
    </form>

    <br>
    verander wachtwoord
    <form action="settings.php" method="POST">
        <input type="password" id="password" name="password" required placeholder="Password" onchange="validatePassword()">
        <input type="password" id="confirm_password" required placeholder="Repeat password" onchange="validatePassword()">
        <input type="submit">
    </form>

    <a href="settings.php?delete=true">Verwijder account</a>
    <br>
    <a href="../login.php?logout=true">Log uit</a>

    <script>
        var password = document.getElementById("password")
        , confirm_password = document.getElementById("confirm_password");

        function validatePassword(){
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
</body>
</html>