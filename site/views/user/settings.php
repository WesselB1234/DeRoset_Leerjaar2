<?php 
    require "../../database.php";
    require "../../permissions.php";

    userPermission();

    function getUserByMail($conn,$email){
        
        $user = $conn->prepare("SELECT * FROM users WHERE email=:email");
        $user->bindParam("email",$email);
        $user->execute();
        $user = $user->fetch();

        return $user;
    }

    function getUserByID($conn,$userID){
        
        $user = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $user->bindParam("id",$userID);
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

    function changeUserPassword($conn,$userID,$oldPassword,$newPassword){

        $user = getUserByID($conn,$userID);

        if(password_verify($oldPassword,$user["password"])){

            $hashedPassword = password_hash($newPassword,PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE users SET password=:password WHERE id=:id");
            $update->bindParam("password",$hashedPassword);
            $update->bindParam("id",$userID);
            $update->execute();

            notify("Wachtwoord veranderd");
        }
        else{
            notify("Oude wachtwoord niet gelijk");
        }
    }

    function changeUserEmail($conn,$userID,$email){

        if(validateEmail($email)){

            $duplicateUser = getUserByMail($conn,$email);

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
        else{
            notify("Not a valid email");
        }
    }

    function validateEmail($email){
        return filter_var($email,FILTER_VALIDATE_EMAIL);
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

    if(isset($_POST["old_password"])){
        
        $oldPassword = $_POST["old_password"];
        $newPassword = $_POST["new_password"];

        changeUserPassword($conn,$userID,$oldPassword,$newPassword);
    }
    
    if(isset($_GET["delete"])){
        deleteUser($conn,$userID);
    }
    
    $user = getUserByID($conn,$userID);
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
        <input type="text" name="username" required placeholder="Gebruikersnaam" value="<?php echo $user["username"];?>">
        <input type="submit">
    </form>

    <br>
    verander email
    <form action="settings.php" method="POST">
        <input type="email" name="email" required placeholder="Email" value="<?php echo $user["email"];?>">
        <input type="submit">
    </form>

    <br>
    verander wachtwoord
    <form action="settings.php" method="POST">
        <input type="password" name="old_password" required placeholder="Oude wachtwoord">
        <input type="password" id="password" name="new_password" required placeholder="Nieuwe wachtwoord" onchange="validatePassword()">
        <input type="password" id="confirm_password" required placeholder="Herhaal nieuwe wachtwoord" onchange="validatePassword()">
        <input type="submit">
    </form>

    <a href="../login.php?logout=true">Log uit</a>
    <br>
    <br>
    <a href="settings.php?delete=true">Verwijder account</a>

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