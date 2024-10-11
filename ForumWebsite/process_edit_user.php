<?php
session_start();

try {
    $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];

    // Fetch the user data from the database based on the user ID
    $stmt = $conn->prepare("SELECT * FROM Users WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update user information with the new values from the form
        $newName = $_POST['new_name'];
        $newSurname = $_POST['new_surname'];
        $newEmail = $_POST['new_email'];

        $updateStmt = $conn->prepare("UPDATE Users SET name = :name, surname = :surname, email = :email WHERE id = :id");
        $updateStmt->bindParam(':name', $newName);
        $updateStmt->bindParam(':surname', $newSurname);
        $updateStmt->bindParam(':email', $newEmail);
        $updateStmt->bindParam(':id', $userId);
        $updateStmt->execute();

        // Redirect to the list_of_users.php page after updating
        header('Location: list_of_users.php');
        exit();
    } else {
        echo "User not found";
    }
} else {
    echo "Invalid request";
}
?>