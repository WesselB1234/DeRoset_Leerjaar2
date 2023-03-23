<?php 
    require "../../../database.php";
    require "../../../permissions.php";

    adminPermission();

    function getProduct($conn,$productID){

        $select = $conn->prepare("SELECT * FROM products WHERE id=:product_id");
        $select->bindParam("product_id",$productID);
        $select->execute();
        $select = $select->fetch();

        return $select;
    }

    function updateProduct($conn,$productID,$name,$brandID,$priceLiter,$description,$imageFile){

        $edit = $conn->prepare("UPDATE products SET name=:name,brand_id=:brand_id,price_liter=:price_liter,description=:description,image=:image 
        WHERE id=:id");

        $edit->bindParam("id",$productID);
        $edit->bindParam("name",$name);
        $edit->bindParam("brand_id",$brandID);
        $edit->bindParam("price_liter",$priceLiter);
        $edit->bindParam("description",$description);

        if($imageFile == null){

            $product = getProduct($conn,$productID);

            $edit->bindParam("image",$product["image"]);
        }
        else{
            $fileTemp = $imageFile['tmp_name'];
            $fileName = $imageFile["name"];
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $toFileName = "product".strval($productID).".". $ext;

            $edit->bindParam("image",$toFileName);

            move_uploaded_file($fileTemp, "../../../images/products/".$toFileName);
        }

        $edit->execute();
    }

    function getBrands($conn){

        $brands = $conn->prepare("SELECT * FROM brands");
        $brands->execute();
        $brands = $brands->fetchAll();

        return $brands;
    }

    $product = null;
    $brands = getBrands($conn);

    if(isset($_POST["name"]) && isset($_GET["id"])){
        
        $productID = $_GET["id"];
        $name = $_POST["name"];
        $brandID = $_POST["brand_id"];
        $priceLiter = $_POST["price_liter"];
        $description = $_POST["description"];
        $imageFile = null;  

        if(strlen($_FILES["image"]["tmp_name"]) > 0){
            $imageFile = $_FILES["image"];
        }
    
        updateProduct($conn,$productID,$name,$brandID,$priceLiter,$description,$imageFile);

        header('location: index.php');
    }

    if(isset($_GET["id"])){

        $id = $_GET["id"];

        $product = $conn->prepare("SELECT products.id,products.name,products.price_liter,products.description,products.brand_id,brands.name as 'brand_name'
        FROM products JOIN brands ON brands.id = products.brand_id WHERE products.id = :id");

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
    <form action="edit.php?id=<?php echo $product["id"];?>" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Naam" value="<?php echo $product["name"];?>">
        
        <select name="brand_id">
            <option value="<?php echo $product["brand_id"]?>" selected><?php echo $product["brand_name"]?></option>
            <?php foreach($brands as $brand){?>
                <option value="<?php echo $brand["id"]?>"><?php echo $brand["name"];?></option>
            <?php }?>
        </select>

        <input type="number" min=0 step=".01" name="price_liter" placeholder="1.34" value="<?php echo $product["price_liter"];?>">
        <input type="file" name="image">

        <textarea name="description" id="" cols="30" rows="10" placeholder="">
            <?php echo $product["description"];?>
        </textarea>

        <input type="submit" name="" id="">
    </form>
</body>
</html>