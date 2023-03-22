<?php
    function adminPermission(){
        
        if(!isset($_SESSION["user"]) or $_SESSION["user"]["role"] != "staff"){
            header('location: ../index.php');
        }
    }
    
    function userPermission(){
        
        if(!isset($_SESSION["user"])){
            header("location ../index.php");
        }
    }
?>