<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $moduleId = $_GET['id'];

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $conn->beginTransaction();

        
        $stmtAnswers = $conn->prepare("DELETE FROM answers WHERE module_id = :id");
        $stmtAnswers->bindParam(':id', $moduleId, PDO::PARAM_INT);
        $stmtAnswers->execute();

        
        $stmtPosts = $conn->prepare("DELETE FROM posts WHERE Module_ID = :id");
        $stmtPosts->bindParam(':id', $moduleId, PDO::PARAM_INT);
        $stmtPosts->execute();

        
        $stmtModule = $conn->prepare("DELETE FROM Modules WHERE id = :id");
        $stmtModule->bindParam(':id', $moduleId, PDO::PARAM_INT);
        $stmtModule->execute();

        
        $conn->commit();

        
        header('Location: list_of_modules.php');
        exit();
    } catch (PDOException $e) {
        
        $conn->rollBack();
        echo "Error deleting module: " . $e->getMessage();
    }
} else {
    
    header('Location: list_of_modules.php');
    exit();
}
?>
