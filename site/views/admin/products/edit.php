<?php 
    require "../../../database.php";

    if(!isset($_SESSION["user"]) or $_SESSION["user"]["role"] != "staff"){
        header('location: ../index.php');
    }

    $product = null;

    if(isset($_POST["name"]) && isset($_GET["id"])){
        
        $id = $_GET["id"];
        $name = $_POST["name"];
        $brand = $_POST["brand"];
        $priceLiter = $_POST["price_liter"];
        $description = $_POST["description"];

        $edit = $conn->prepare("UPDATE products SET name=:name,brand=:brand,price_liter=:price_liter,description=:description 
        WHERE id=:id");

        $edit->bindParam("id",$id);
        $edit->bindParam("name",$name);
        $edit->bindParam("brand",$brand);
        $edit->bindParam("price_liter",$priceLiter);
        $edit->bindParam("description",$description);
        $edit->execute();

        header('location: index.php');
    }

    if(isset($_GET["id"])){

        $id = $_GET["id"];

        $product = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $product->bindParam("id",$id);
        $product->execute();
        $product = $product->fetch();
    }
    else{
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
    <form action="edit.php?id=<?php echo $product["id"];?>" method="POST">
        <input type="text" name="name" placeholder="Naam" value="<?php echo $product["name"];?>">
        <input type="text" name="brand" placeholder="Merk" value="<?php echo $product["brand"];?>">
        <input type="number" min=0 step=".01" name="price_liter" placeholder="1.34" value="<?php echo $product["price_liter"];?>">
        <input type="file">

        <textarea name="description" id="" cols="30" rows="10" placeholder="">
            <?php echo $product["description"];?>
        </textarea>

        <input type="submit" name="" id="">
    </form>
</body>
</html>