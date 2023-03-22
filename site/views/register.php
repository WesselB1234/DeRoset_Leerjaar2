<?php 
    require "../database.php";

    function isDuplicate($email,$conn){
        
        $duplicate = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $duplicate->bindParam('email',$email);
        $duplicate->execute();
        $duplicate = $duplicate->fetch();

        if(!empty($duplicate)){
            return true;
        }

        return false;
    }

    function createUser($conn,$username,$email,$role,$password){

        $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

        $create = $conn->prepare("INSERT INTO users(email,username,role,password) VALUES(:email,:username,:role,:password)");
        $create->bindParam("email",$email);
        $create->bindParam("username",$username);
        $create->bindParam("role",$role);
        $create->bindParam("password",$hashedPassword);
        $create->execute();
    }

    if(isset($_POST["password"])){
        
        $username = $_POST["username"];
        $email = $_POST["email"]; 
        $role = "user";
        $password = $_POST["password"]; 

        if(isDuplicate($email,$conn) == false){
            //filter!!!!!!!!!!!!!
            createUser($conn,$username,$email,$role,$password);
        }
        else{
            echo "email already exists";
        }
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
    register

    <form action="register.php" method="POST">
        <input type="username" name="username" required placeholder="Username">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" id="password" name="password" required placeholder="Password" onchange="validatePassword()">
        <input type="password" id="confirm_password" required placeholder="Repeat password" onchange="validatePassword()">
        <input type="submit">
    </form>

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