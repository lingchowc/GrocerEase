<?php

    // Start the session at the very top of the script
    session_start();

    // Include Composer's autoloader
    require_once '../vendor/autoload.php';

    use Symfony\Component\Yaml\Yaml;

    // Load YAML configuration
    $config = Yaml::parseFile('../config.yml');

    // Extract database configuration
    $dbConfig = $config['database'];
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}";
    // $dsn = "mysql:host=127.0.0.1;dbname=studentdb";
    // $registry = new CollectorRegistry(new PDO($dsn, $dbusername, $dbpassword, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
    $dbusername = $dbConfig['username'];
    $dbpassword = $dbConfig['password'];

    // Initialize error message
    $error_message = "";

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Create a PDO connection
            $pdo = new PDO($dsn, $dbusername, $dbpassword, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            // Prepare the SQL query to check username and password
            $query = "SELECT * FROM login WHERE username = :username AND password = :password";
            $stmt = $pdo->prepare($query);

            // Bind the parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            // Execute the query
            $stmt->execute();

            // Check if the login is successful
            if ($stmt->rowCount() == 1) {
                // Store user session info
                $_SESSION['username'] = $username;

                // Redirect to the user dashboard
                header("Location: userDashboard.php");
                exit();
            } else {
                // Login failed, set error message
                $error_message = "Login failed. Invalid username or password.";
            }
        } catch (PDOException $e) {
            // Display database connection error
            die("Connection failed: " . $e->getMessage());
        }
    }
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Smart Home Login</title>
    <link rel="stylesheet" href="../css/login.css"> 

</head>
<body>
    <div class="container">
        <h1>Welcome to Student Smart Home</h1>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Log In</button>
        </form>
        <a href="#" class="forgot-password">Forgot Password?</a>
    </div>
</body>
</html>
