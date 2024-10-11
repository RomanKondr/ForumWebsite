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

    // Retrieve form data
    $questionID = $_POST['question_id'];
    $questionTitle = $_POST['Title'];
    $questionDescription = $_POST['Description'];

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update question in the posts table
        $sql = "UPDATE posts SET Title = :title, Description = :description WHERE id = :questionID AND User_ID = :userID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $questionTitle);
        $stmt->bindParam(':description', $questionDescription);
        $stmt->bindParam(':questionID', $questionID, PDO::PARAM_INT);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to homepage.php after successful form submission
        header("Location: homepage.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();

        // Additional error information
        echo "<br>Error Code: " . $stmt->errorCode();
        echo "<br>Error Info: ";
        print_r($stmt->errorInfo());
    }

    $conn = null;
} else {
    
    header("Location: homepage.php");
    exit();
}
?>