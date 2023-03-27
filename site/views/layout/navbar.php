<nav>
    <a href="index.php">Over ons</a>
    <a href="order.php">Bestellen</a>
    <a href="blog.php">Blog</a>
    <a href="contact.php">Contact</a>

    <?php if(!isset($_SESSION["user"])){?>
        <a href="register.php">Registreer</a>
        <a href="login.php">Login</a>
    <?php }
    else {?>
        <a href="user/index.php">Mijn account</a>
    <?php }?>

    <?php if(isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "staff"){?>
        <a href="admin/index.php">Personeel</a>
    <?php }?>
</nav>

<?php if(isset($_SESSION["user"])){?>
    <a href="cart.php">
        <img class="shoppingCard"src="../../images/layout/shopping_cart-removebg-preview.png" alt="">
    </a>
<?php }?>
