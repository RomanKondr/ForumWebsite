<?php
session_start();

try {
    $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


if (isset($_GET['id'])) {
    $moduleId = $_GET['id'];

   
    try {
        $stmt = $conn->prepare("SELECT * FROM Modules WHERE id = :id");
        $stmt->bindParam(':id', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        $module = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching module details: " . $e->getMessage();
    }

    
    if ($module) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Edit Module</title>
            <link rel="stylesheet" href="css/home_page.css">
            <link rel="stylesheet" href="css/edit_module.css">
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

        <h2>Edit Module</h2>
        <form action="process_edit_module.php" method="post">
            <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
            
            <label for="new_name">New Name:</label>
            <input type="text" id="new_name" name="new_name" value="<?php echo htmlspecialchars($module['name']); ?>" required>

            
            <button type="submit">Update Module</button>
        </form>

        </body>
        </html>
        <?php
    } else {
        echo "Module not found.";
    }
} else {
    echo "Module ID not specified.";
}
?>