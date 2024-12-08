<?php 
    $dsn = "mysql:host=127.0.0.1;dbname=studentdb";
    $dbusername = "root";
    $dbpassword = "";

    try {
        // Create a PDO connection
        $pdo = new PDO($dsn, $dbusername, $dbpassword, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "OK";
    } catch (PDOException $e) {
        // Display database connection error
        echo "Connection failed: " . $e->getMessage();
    }
?>