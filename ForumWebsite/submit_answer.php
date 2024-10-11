<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_SESSION['user'])) {
        header('Location: log-in.php');
        exit();
    }

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the form data
        $userId = $_SESSION['user']['id'];
        $questionId = isset($_POST['question_id']) ? $_POST['question_id'] : 0;
        $moduleId = isset($_POST['module_id']) ? $_POST['module_id'] : 0;
        $answer = isset($_POST['answer']) ? $_POST['answer'] : '';

        // Query to get the module_id associated with the post_id
        $moduleQuery = $conn->prepare("SELECT Module_ID FROM posts WHERE id = :question_id");
        $moduleQuery->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $moduleQuery->execute();

        // Check if the post_id is valid
        if ($moduleQuery->rowCount() === 0) {
            
            echo "Debug Module ID: 0"; 
            echo "Invalid post_id. Please choose a valid question.";
            exit();
        }

        // Fetch the module_id
        $moduleId = $moduleQuery->fetch(PDO::FETCH_ASSOC)['Module_ID'];

        // Check if the provided module_id exists in the Modules table
        $moduleCheck = $conn->prepare("SELECT id FROM Modules WHERE id = :module_id");
        $moduleCheck->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        $moduleCheck->execute();

        if ($moduleCheck->rowCount() === 0) {
            // Handle the case where the module_id is invalid
            echo "Debug Module ID: " . $moduleId; // Add this line for debugging
            echo "Invalid module_id. Please choose a valid module.";
            exit();
        }

        // Insert the answer into the answers table
        $sql = "INSERT INTO answers (answer, user_id, module_id, post_id) VALUES (:answer, :user_id, :module_id, :post_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $questionId, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to homepage.php after submitting the answer
        header('Location: homepage.php');
        exit();
    } catch (PDOException $e) {
        
        echo "Error: " . $e->getMessage();
    } finally {
        
        $conn = null;
    }
} else {
    // If not a POST request, redirect to the homepage
    header('Location: homepage.php');
    exit();
}
?>