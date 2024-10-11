<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['module_id'])) {
    $moduleId = $_POST['module_id'];

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       
        $newName = $_POST['new_name'];
        $stmt = $conn->prepare("UPDATE Modules SET name = :newName WHERE id = :id");
        $stmt->bindParam(':id', $moduleId, PDO::PARAM_INT);
        $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: list_of_modules.php');
        exit();
    } catch (PDOException $e) {
        echo "Error updating module: " . $e->getMessage();
    } finally {
        
        $conn = null;
    }
} else {
    // Redirect to the list_of_modules.php page if accessed without a POST request or module ID
    header('Location: list_of_modules.php');
    exit();
}
?>