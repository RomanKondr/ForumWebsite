<?php
session_start();

try {
    $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

try {
    $stmt = $conn->query("SELECT * FROM Modules");
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching modules: " . $e->getMessage();
}

$addModuleButton = '';
if (isset($_SESSION['user'])) {
    $addModuleButton = '<a href="add_module.php" class="add-btn"><i class="fas fa-plus"></i> Add Module</a>';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List of Modules</title>
    <link rel="stylesheet" href="css/home_page.css">
    <link rel="stylesheet" href="css/list_of_modules.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
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

    <div class="list_of_modules_header">
        <p><strong>List of Modules:</strong></p>
    </div>

    <!-- Display list of modules -->
    <div class="module-list">
        <?php foreach ($modules as $module): ?>
            <div class="module-item" id="module-<?php echo $module['id']; ?>">
                <img src="Pictures/module_icon.jpg" alt="Module Icon" class="module-icon">
                <div class="module-details">
                    <p>Name: <?php echo htmlspecialchars($module['name']); ?></p>

                    <?php
                    // Display the "Edit" and "Delete" buttons only if a user is logged in
                    if (isset($_SESSION['user'])) {
                        echo '<a href="edit_module.php?id=' . $module['id'] . '" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>';
                        echo '<button class="delete-btn" onclick="confirmDelete(' . $module['id'] . ')"><i class="fas fa-trash"></i> Delete</button>';
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="add-module-button">
        <?php echo $addModuleButton; ?>
    </div>

    <script>
        function confirmDelete(moduleId) {
            var confirmDelete = confirm("Are you sure you want to delete this module?");
            if (confirmDelete) {
                // Redirect to the PHP script for deleting
                window.location.href = 'delete_module.php?id=' + moduleId;
            }
        }
    </script>

</body>

</html>

