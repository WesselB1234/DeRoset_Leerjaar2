<?php
    function adminPermission($directory){
        
        if(!isset($_SESSION["user"]) or $_SESSION["user"]["role"] != "staff"){

            header('location: '.$directory);
        }
    }
    
    function userPermission($directory){
        
        if(!isset($_SESSION["user"])){
            header('location: '.$directory);
        }
    }
?>