<?php 
    require "../../../database.php";
    require "../../../permissions.php";
    
    $userID = null;
    $user = null;

    if(isset($_GET["id"])){

        $userID = $_GET["id"];
        $user = getUser($conn,$userID);
        
        if(empty($user)){
            header("location: index.php");
        }
    }
    else{
        adminPermission();
    }

    function deleteUser($conn,$userID){
        
        $delete = $conn->prepare("DELETE FROM users WHERE id=:id");
        $delete->bindParam("id",$userID);
        $delete->execute();
    }    

    function getUser($conn,$userID){
        
        $user = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $user->bindParam("id",$userID);
        $user->execute();
        $user = $user->fetch();

        return $user;
    }   

    if(isset($_GET["id"])){

        $userID = $_GET["id"];
        $user = getUser($conn,$userID);
        
        if(empty($user)){
            header("location: index.php");
        }
    }

    if(isset($_GET["delete"])){

        $userID = $_GET["delete"];
        
        deleteUser($conn,$userID);

        header("location: index.php");
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
    <h1><?php echo $user["username"];?></h1>
    <br>
    <?php echo $user["email"];?>
    <br>
    <?php echo $user["role"];?>
    <br>
    <a href="details.php?delete=<?php echo $user["id"]?>">Verwijder account</a>
</body>
</html>