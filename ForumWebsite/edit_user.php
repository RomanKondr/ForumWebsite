<?php
session_start();

if (!isset($_SESSION["user"]["id"])) {
    // Redirect to the login page if not logged in
    header("Location: log-in.php");
    exit();
}

try {
    $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if the user ID is set
if (isset($_GET['id'])) {
    $userIdFromSession = $_SESSION["user"]["id"];
    $userIdFromURL = $_GET['id'];

    // Check if the user is trying to edit their own details
    if ($userIdFromSession != $userIdFromURL) {
        echo "You do not have permission to edit this user's details.";
        exit();
    }

    // Fetch user details from the database based on the user ID
    try {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE id = :id");
        $stmt->bindParam(':id', $userIdFromURL, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching user details: " . $e->getMessage();
    }

    // Display the form with user details for editing
    if ($user) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Edit User</title>
            <link rel="stylesheet" href="css/home_page.css">
            <link rel="stylesheet" href="css/edit_user.css">
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

        <h2>Edit User</h2>
        <form action="process_edit_user.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            
            <label for="new_name">New Name:</label>
            <input type="text" id="new_name" name="new_name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="new_surname">New Surname:</label>
            <input type="text" id="new_surname" name="new_surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required>

            <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <button type="submit">Update User</button>
        </form>

        </body>
        </html>
        <?php
    } else {
        echo "User not found.";
    }
} else {
    echo "User ID not specified.";
}
?>