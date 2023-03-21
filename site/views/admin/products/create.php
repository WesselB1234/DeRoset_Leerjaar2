<?php 
    require "../../../database.php";

    if(!isset($_SESSION["user"]) or $_SESSION["user"]["role"] != "staff"){
        header('location: ../index.php');
    }

    if(isset($_POST["name"])){
        
        $name = $_POST["name"];
        $brand = $_POST["brand"];
        $priceLiter = $_POST["price_liter"];
        $description = $_POST["description"];

        $create = $conn->prepare("INSERT INTO products(name,price_liter,description,brand,image) VALUES
        (:name,:price_liter,:description,:brand,null)");

        $create->bindParam("name",$name);
        $create->bindParam("brand",$brand);
        $create->bindParam("price_liter",$priceLiter);
        $create->bindParam("description",$description);
        $create->execute();

        header('location: index.php');
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
    <form action="create.php" method="POST">
        <input type="text" name="name" placeholder="Naam">
        <input type="text" name="brand" placeholder="Merk">
        <input type="number" min=0 step=".01" name="price_liter" placeholder="1.34">
        <input type="file">

        <textarea name="description" id="" cols="30" rows="10" placeholder="">
            
        </textarea>

        <input type="submit" name="" id="">
    </form>
</body>
</html>