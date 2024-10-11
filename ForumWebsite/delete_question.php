<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userId = $_POST['userId'];
    $questionId = $_POST['questionId'];

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $sql = "DELETE FROM posts WHERE id = :questionId AND User_ID = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $response = ['success' => true];
    } catch (PDOException $e) {
        $response = ['success' => false, 'error' => $e->getMessage()];
    }

    
    header('Content-Type: application/json');
    echo json_encode($response);

    
    $conn = null;
    exit();
}
?>