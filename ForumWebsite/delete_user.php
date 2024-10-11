<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $conn->beginTransaction();

        
        $stmtAnswers = $conn->prepare("DELETE FROM answers WHERE user_id = :id");
        $stmtAnswers->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtAnswers->execute();

        
        $stmtPosts = $conn->prepare("DELETE FROM posts WHERE User_ID = :id");
        $stmtPosts->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtPosts->execute();

        
        $stmtUser = $conn->prepare("DELETE FROM Users WHERE id = :id");
        $stmtUser->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtUser->execute();

        
        $conn->commit();

        
        header('Location: list_of_users.php');
        exit();
    } catch (PDOException $e) {
        
        $conn->rollBack();
        echo "Error deleting user: " . $e->getMessage();
    }
} else {
    
    header('Location: list_of_users.php');
    exit();
}
?>
