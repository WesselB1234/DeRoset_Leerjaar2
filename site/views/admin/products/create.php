<?php 
    require "../../../database.php";
    require "../../../permissions.php";

    adminPermission();

    function createProduct($conn,$name,$brandID,$priceLiter,$description,$imageFile){
    
        $create = $conn->prepare("INSERT INTO products(name,price_liter,description,brand_id) VALUES
        (:name,:price_liter,:description,:brand_id)");

        $create->bindParam("name",$name);
        $create->bindParam("brand_id",$brandID);
        $create->bindParam("price_liter",$priceLiter);
        $create->bindParam("description",$description);
        $create->execute();

        // set image

        $productID = $conn->lastInsertId();

        $fileTemp = $imageFile['tmp_name'];
        $fileName = $imageFile["name"];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $toFileName = "product".strval($productID).".". $ext;

        move_uploaded_file($fileTemp, "../../../images/products/".$toFileName);

        $update = $conn->prepare("UPDATE products SET image =:image WHERE id = :product_id");
        $update->bindParam("image",$toFileName);
        $update->bindParam("product_id",$productID);
        $update->execute();
    }

    function getBrands($conn){

        $brands = $conn->prepare("SELECT * FROM brands");
        $brands->execute();
        $brands = $brands->fetchAll();

        return $brands;
    }

    if(isset($_POST["name"])){
        
        $name = $_POST["name"];
        $brandID = $_POST["brand_id"];
        $priceLiter = $_POST["price_liter"];
        $description = $_POST["description"];
        $imageFile = $_FILES["image_file"];

        createProduct($conn,$name,$brandID,$priceLiter,$description,$imageFile);        

        header('location: index.php');
    }

    $brands = getBrands($conn);
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
    <form action="create.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Naam">

        <select name="brand_id">
            <?php foreach($brands as $brand){?>
                <option value="<?php echo $brand["id"]?>"><?php echo $brand["name"];?></option>
            <?php }?>
        </select>
        
        <input type="number" min=0 step=".01" name="price_liter" placeholder="1.34">
        <input type="file" name="image_file">

        <textarea name="description" id="" cols="30" rows="10" placeholder="">
            
        </textarea>

        <input type="submit" name="" id="">
    </form>
</body>
</html>