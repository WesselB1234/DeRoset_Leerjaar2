<nav>
    <a href="index.php" <?php if($_SERVER['REQUEST_URI'] == "/views/index.php"){?> class="onUrlPage"<?php }?>>Over ons</a>
    <a href="order.php" <?php if($_SERVER['REQUEST_URI'] == "/views/order.php"){?> class="onUrlPage"<?php }?>>Bestellen</a>
    <a href="blog.php" <?php if($_SERVER['REQUEST_URI'] == "/views/blog.php"){?> class="onUrlPage"<?php }?>>Blog</a>
    <a href="contact.php" <?php if($_SERVER['REQUEST_URI'] == "/views/contact.php"){?> class="onUrlPage"<?php }?>>Contact</a>

    <?php if(!isset($_SESSION["user"])){?>
        <a href="register.php" <?php if($_SERVER['REQUEST_URI'] == "/views/register.php"){?> class="onUrlPage"<?php }?>>Registreer</a>
        <a href="login.php" <?php if($_SERVER['REQUEST_URI'] == "/views/login.php"){?> class="onUrlPage"<?php }?>>Login</a>
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
        <div class="containsProducts"></div>
    </a>
<?php }?>
