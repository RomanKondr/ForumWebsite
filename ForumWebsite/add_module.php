<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $moduleName = $_POST["module_name"]; 

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert the new module into the Modules table
        $stmt = $conn->prepare("INSERT INTO Modules (name) VALUES (:name)");
        $stmt->bindParam(':name', $moduleName, PDO::PARAM_STR);
        $stmt->execute();

        // Redirect to the list_of_modules.php page after adding the module
        header("Location: list_of_modules.php");
        exit();
    } catch (PDOException $e) {
        echo "Error adding module: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Module</title>
    <link rel="stylesheet" href="css/home_page.css">
    <link rel="stylesheet" href="css/add_module.css"> 
</head>

<body>

    <div class="logo">
        <a><img src="Pictures/logo.jpg" width="162"></a>
    </div>
    <nav>
        <ul>
            <li><a href="homepage.php"> HOME </a></li>
            <li><a href="list_of_users.php"> USERS </a></li>
            <li><a href="list_of_modules.php"> MODULES </a></li>
        </ul>
        <div class="footer-info">
            <p>Contact us: studentinteraction@gre.ac.uk</p>
        </div>
    </nav>

    <h2>Add Module</h2>
    <form action="add_module.php" method="post">
        <label for="module_name">Module Name:</label>
        <input type="text" id="module_name" name="module_name" required>

        <button type="submit">Add Module</button>
    </form>

</body>

</html>