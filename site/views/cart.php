<?php
    include "../database.php";
    include "../permissions.php";

    userPermission("login.php"); 

    function emptyCart($conn,$userID){
        
        $deleteCart = $conn->prepare("DELETE FROM carts_products WHERE user_id=:user_id");
        $deleteCart->bindParam("user_id",$userID);
        $deleteCart->execute();
    }
    
    function getCartProducts($conn,$userID){
        
        $cartOrders = $conn->prepare("SELECT *,products.name as product_name,products.price_liter * carts_products.liter as 'total_cost' FROM carts_products
        JOIN products ON products.id = carts_products.product_id
        WHERE user_id=:user_id");
        
        $cartOrders->bindParam("user_id",$userID);
        $cartOrders->execute();
        $cartOrders = $cartOrders->fetchAll();

        return $cartOrders;
    }

    function getLocations($conn){
        
        $locations = $conn->prepare("SELECT * FROM locations");
        $locations->execute();
        $locations = $locations->fetchAll();

        return $locations;
    }

    function createOrder($conn,$userID,$isDeliver,$name,$address,$postalcode,$locationID,$telephoneNumber,$orderDate){
        
        $cartOrders = getCartProducts($conn,$userID); 

        $createOrder = $conn->prepare("INSERT INTO orders(user_id,is_deliver,name,address,postalcode,location_id,telephone_number,order_date) 
        VALUES (:user_id,:is_deliver,:name,:address,:postalcode,:location_id,:telephone_number,:order_date)");

        $createOrder->bindParam("user_id",$userID);
        $createOrder->bindParam("is_deliver",$isDeliver, $conn::PARAM_BOOL);
        $createOrder->bindParam("name",$name);
        $createOrder->bindParam("address",$address);
        $createOrder->bindParam("postalcode",$postalcode);
        $createOrder->bindParam("location_id",$locationID);
        $createOrder->bindParam("telephone_number",$telephoneNumber);
        $createOrder->bindParam("order_date",$orderDate);
        $createOrder->execute();

        $orderID = $conn->lastInsertId();

        foreach($cartOrders as $productOrder){
            
            $createProductOrder = $conn->prepare("INSERT INTO orders_products(order_id,product_id,liter) VALUES
            (:order_id,:product_id,:liter)");
            
            $createProductOrder->bindParam("order_id",$orderID);
            $createProductOrder->bindParam("product_id",$productOrder["id"]);
            $createProductOrder->bindParam("liter",$productOrder["liter"]);
            $createProductOrder->execute();
        }

        emptyCart($conn,$userID);
    }

    function calculateTotalCostsCart($conn,$userID){

        $totalCost = $conn->prepare("SELECT sum(products.price_liter * carts_products.liter) as 'total_cost' FROM carts_products
        JOIN products ON products.id = carts_products.product_id
        WHERE user_id=:user_id");
        
        $totalCost->bindParam("user_id",$userID);
        $totalCost->execute();
        $totalCost = $totalCost->fetch();

        return $totalCost["total_cost"];
    }

    function setLiterCartProduct($conn,$userID,$productID,$liter){

        $updateProductCart = $conn->prepare("UPDATE carts_products SET liter=:liter WHERE product_id=:product_id AND user_id=:user_id");
        $updateProductCart->bindParam("liter",$liter);
        $updateProductCart->bindParam("product_id",$productID);
        $updateProductCart->bindParam("user_id",$userID);
        $updateProductCart->execute();
    }

    function deleteCartProduct($conn,$userID,$productID){

        $delete = $conn->prepare("DELETE FROM carts_products WHERE user_id=:user_id AND product_id=:product_id");
        $delete->bindParam("product_id",$productID);
        $delete->bindParam("user_id",$userID);
        $delete->execute();
    }

    $userID = $_SESSION["user"]["id"];
    
    if(isset($_POST["is_deliver"])){
        
        if(count(getCartProducts($conn,$userID)) > 0){

            $isDeliver = $_POST["is_deliver"];
            $name = $_POST["name"];
            $address = $_POST["address"];
            $postalcode = $_POST["postalcode"];
            $locationID = $_POST["location_id"];
            $telephoneNumber = $_POST["telephone_number"];
            $orderDate = $_POST["order_date"];

            createOrder($conn,$userID,$isDeliver,$name,$address,$postalcode,$locationID,$telephoneNumber,$orderDate);
            emptyCart($conn,$userID);
        }
        else{
            echo "cart empty mate";
        }
    }

    if(isset($_POST["change_amount"])){

        $productID = $_GET["product_id"];
        $newAmount = $_POST["change_amount"];

        if($newAmount > 0){
            setLiterCartProduct($conn,$userID,$productID,$newAmount);
        }
        else{
            deleteCartProduct($conn,$userID,$productID);
        }
    }

    $cartOrders = getCartProducts($conn,$userID);
    $locations = getLocations($conn);
    $totalCost = calculateTotalCostsCart($conn,$userID);
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
    <table>
        <thead>
            <th>
                Naam
            </th>
            <th>
                Liter  
            </th>
            <th>
                Koste
            </th>
        </thead>
        <tbody>
            <?php foreach($cartOrders as $order){?>
                <tr>
                    <td>
                        <?php echo $order["product_name"];?>
                    </td>
                    <td>    
                        <?php echo $order["liter"];?>
                    </td>
                    <td>
                        € <?php echo $order["total_cost"];?>
                    </td>
                    <td>
                        <button id="button<?php echo $order["id"]?>" onclick="insertValueChangeForm('<?php echo $order['id']?>',<?php echo $order['product_id']?>)">Verander</button>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>

    <br>
    <form action="cart.php" method="POST">
        <input type="radio" name="is_deliver" onchange="changeCostText(10.00)" required> Bezorgen
        <br>
        <input type="radio" name="is_deliver" onchange="changeCostText(0.00)" required> Afhalen
        <br>
        <input type="text" placeholder="Naam" name="name" required>
        <br>
        <input type="text" placeholder="Adres" name="address" required>
        <br>
        <input type="text" placeholder="Postcode" name="postalcode" required>
        <br>
        <select name="location_id">
            <?php foreach($locations as $location){?>
                <option value="<?php echo $location["id"];?>"><?php echo $location["name"];?></option>
            <?php }?>
        </select>
        <br>
        <input type="text" placeholder="Telefoonnummer" name="telephone_number" required>
        <br>
        <input type="date" name="order_date">
        <input type="submit">
    </form>

    <br>
    <br>
    <span id="total_cost">Totale kosten: € <?php echo $totalCost;?></span>

    <script>     
        let totalCost = <?php echo $totalCost;?>;
        let costSpan = document.getElementById("total_cost");

        function valueChangeForm(productID,JS_ID){
           
           return `<td>
                <form id="valueChangerForm` + JS_ID.toString() + `" action="cart.php?product_id=` + productID.toString() + `" method="POST">
                    <input type="number" name="change_amount" min=0 step=".01" placeholder="1.34">
                    <input type="submit">
                </form>
            </td>`;
        }
        
        function changeCostText(offset){
            costSpan.innerHTML =  'Totale kosten: ' + (totalCost + offset).toString();  
        }

        function insertValueChangeForm(JS_ID,productID){

            let button = document.getElementById("button" + JS_ID.toString())
            let duplicate = document.getElementById("valueChangerForm" + JS_ID.toString())
            let trTable = button.parentElement.parentElement;
        
            if(duplicate == null){
                trTable.innerHTML += valueChangeForm(productID,JS_ID);
            }
            else{
                duplicate.parentElement.remove();
            }
        }
    </script>
</body>
</html>