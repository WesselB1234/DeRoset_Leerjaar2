<?php 
    require "../../../database.php";
    require "../../../permissions.php";

    adminPermission();

    if(isset($_GET["delete"])){

        $id = $_GET["delete"];

        $delete = $conn->prepare("DELETE FROM products WHERE id = :id");
        $delete->bindParam("id",$id);
        $delete->execute();
    }

    $products = $conn->prepare("SELECT *,brands.name as 'brand_name' FROM products JOIN brands on brands.id = products.brand_id");
    $products->execute();
    $products = $products->fetchAll();
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
    <a href="create.php">Maak aan</a>

    <table>
        <thead>
            <th>
                Id
            </th>
            <th>
                Naam
            </th>
            <th>
                Prijs per liter
            </th>
            <th>
                Merk
            </th>
        </thead>
        <tbody>
            <?php foreach($products as $product){?>
            <tr>
                <td>
                    <?php echo $product["id"];?>
                </td>
                <td>
                    <?php echo $product["name"];?>
                </td>
                <td>
                    <?php echo $product["price_liter"];?>
                </td>
                <td>
                    <?php echo $product["brand_name"];?>
                </td>
                <td>
                    <a href="edit.php?id=<?php echo $product["id"];?>">Verander</a>
                </td>
                <td>
                    <a href="index.php?delete=<?php echo$product["id"];?>">Verwijder</a>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>