<a href="login.php">Over ons</a>
<a href="order/index.php">Bestellen</a>
<a href="blog.php">Blog</a>
<a href="contact.php">Contact</a>
<?php if(!isset($_SESSION["user"])){?>
    <a href="register.php">Registreer</a>
    <a href="login.php">Login</a>
<?php }
else {?>
    <a href="login.php?logout=true">Logout</a>
<?php }?>

<?php if(isset($_SESSION["user"]) && $_SESSION["user"]["role"] == "Staff"){?>
    <a href="admin/index.php">Personeel</a>
<?php }?>