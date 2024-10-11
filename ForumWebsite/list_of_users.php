<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from the session
    $userID = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
    
  
    // Check if the user is not logged in
    if (!$userID) {
        // Redirect to the login page
        header("Location: log-in.php");
        exit;
    }
    
} else {

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    // Fetch users from the database
    try {
        $stmt = $conn->query("SELECT * FROM Users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching users: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List of Users</title>
    <link rel="stylesheet" href="css/home_page.css">
    <link rel="stylesheet" href="css/list_of_users.css">
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

    <div class="list_of_users_header">
        <p> <strong>Student Interaction Platform Users:</strong></p>
        <div>
            <!-- Display list of users -->
            <div class="user-list">
                <?php foreach ($users as $user): ?>
                    <div class="user-item" id="user-<?php echo $user['id']; ?>">
                        <i class="fas fa-user"></i>
                        <div class="user-details">
                            <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
                            <p>Surname: <?php echo htmlspecialchars($user['surname']); ?></p>
                            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        
                        <?php
                        $loggedInUserID = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;
                        $displayedUserID = isset($user['id']) ? intval($user['id']) : 0;
                        
                        if ($loggedInUserID > 0 && $loggedInUserID === $displayedUserID) {
                            echo '<a href="edit_user.php?id=' . $user['id'] . '" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>';
                        }
                        
                        // Display the "Delete" button only if the logged-in user does not match the user being displayed
                        if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $user['id']) {
                            echo '<button class="delete-btn" onclick="confirmDelete(' . $user['id'] . ')"><i class="fas fa-trash"></i> Delete</button>';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <script>
                function confirmDelete(userId) {
                    var confirmDelete = confirm("Are you sure you want to delete this user?");
                    if (confirmDelete) {
                        // Redirect to the PHP script for deleting
                        window.location.href = 'delete_user.php?id=' + userId;
                    }
                }
            </script>
        </div>
    </div>
</body>
</html>