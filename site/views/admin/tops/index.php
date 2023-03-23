<?php 
    require "../../../database.php";
    require "../../../permissions.php";

    adminPermission();

    function getDailyTop($conn){
        
        $daily = $conn->prepare("SELECT * FROM tops_products 
        JOIN products ON products.id = tops_products.product_id
        WHERE is_daily_top = 1");

        $daily->execute();
        $daily = $daily->fetch();

        return $daily;
    }
    
    function getWeeklyTop($conn){

        $weekly = $conn->prepare("SELECT * FROM tops_products 
        JOIN products ON products.id = tops_products.product_id
        WHERE is_daily_top = 0");

        $weekly->execute();
        $weekly = $weekly->fetchAll();

        return $weekly;
    }

    $daily = getDailyTop($conn);
    $weekly = getWeeklyTop($conn);
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
    <a href="<?php  ?>">Maak weeklijke top aan</a>

    <table>
        <thead>
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
            <tr>
                <?php if(!empty($daily)){?>
                    <td>
                        <?php echo $daily["name"];?>
                    </td>
                    <td>
                        <?php echo $daily["price_liter"];?>
                    </td>
                    <td>
                        <?php echo $daily["brand_name"];?>
                    </td>
                <?php } 
                else{?>
                    <td>
                        none
                    </td>
                    <td>
                        none
                    </td>
                    <td>
                        none
                    </td>
                <?php }?>
                <td>
                    <a href="editDailyTop.php?product_id">Verander</a>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
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
            <?php foreach($weekly as $product){?>
            <tr>
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
                    <a href="editDailyTop.php?product_id">Verander</a>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</body>
</html>