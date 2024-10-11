<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['question_id'])) {
    $questionId = $_GET['question_id'];

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $sql = "SELECT * FROM posts WHERE id = :questionId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
        $stmt->execute();

        
        if ($stmt->rowCount() > 0) {
            $questionDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $questionDetails['User_ID']) {
               
                $questionTitle = $questionDetails['Title'];
                $questionDescription = $questionDetails['Description'];
                

            } else {
                
                header("Location: homepage.php?error=not_owner");
                exit();
            }
        } else {
            
            header("Location: homepage.php?error=no_results");
            exit();
        }

    } catch (PDOException $e) {
        
        echo "Error: " . $e->getMessage();
        
    }

    
    $conn = null;
} else {
    
    header("Location: homepage.php?error=invalid_request");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Question</title>
    <link rel="stylesheet" href="css/edit_form.css">
</head>

<body>

    <div class="logo">
        <a><img src="Pictures/logo.jpg" width="160" height="118.5"></a>
    </div>

    <nav>
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li> <a href = "list_of_users.php"> Users </a></li>
            <li> <a href = "list_of_modules.php"> Modules </a></li>
        </ul>
        <div class="footer-info">
            <p>Contact us: studentinteraction@gre.ac.uk</p>
        </div>
    </nav>

    
    <section id="question-edit-form">
        <div class="container">
            <h2>Edit Question</h2>

            
            <form id="editQuestionForm" action="process_edit_question.php" method="post" enctype="multipart/form-data">
                
                <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">

                
                <div class="form-group">
                    <label for="questionTitle">Question Title:</label>
                    <input class="form-control" id="questionTitle" name="Title" placeholder="Enter question title" required="" type="text" value="<?php echo isset($questionTitle) ? htmlspecialchars($questionTitle) : ''; ?>" />
                </div>

                
                <div class="form-group">
                    <label for="questionDescription">Question Description:</label>
                    <textarea class="form-control" id="questionDescription" name="Description" placeholder="Enter question description" required=""><?php echo isset($questionDescription) ? htmlspecialchars($questionDescription) : ''; ?></textarea>
                </div>

                
                <button class="btn btn-primary" type="submit">Save Changes</button>

                
                <a class="btn btn-secondary" href="homepage.php">Cancel</a>
            </form>
        </div>
    </section>
</body>
</html>