<?php 
    $servername = "mariadb";
    $username = "user";
    $password = "password";
    $database = "deroset";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }   catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    session_start();
?>