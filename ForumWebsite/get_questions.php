<?php
session_start();

try {
    $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['moduleId'])) {
        $moduleId = $_GET['moduleId'];

        // Fetch questions for the specified module with user details
        $sql = "SELECT posts.*, Modules.name AS ModuleName, Users.name AS UserName, Users.surname AS UserSurname
                FROM posts
                INNER JOIN Modules ON posts.Module_ID = Modules.id
                INNER JOIN Users ON posts.User_ID = Users.id
                WHERE Modules.id = :moduleId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
    } else {
        // Fetch all questions with user details
        $sql = "SELECT posts.*, Modules.name AS ModuleName, Users.name AS UserName, Users.surname AS UserSurname
                FROM posts
                INNER JOIN Modules ON posts.Module_ID = Modules.id
                INNER JOIN Users ON posts.User_ID = Users.id";
        $stmt = $conn->query($sql);
    }

    
    $stmt->execute();

    
    if ($stmt->rowCount() > 0) {
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $questionTitle = $row['Title'];
            $questionDescription = $row['Description'];
            $photo = $row['Photo'];
            $moduleName = $row['ModuleName'];
            $userName = $row['UserName'];
            $userSurname = $row['UserSurname'];
            $questionId = $row['id'];

            $userID = $row['User_ID'];

            echo '<div class="question">
                    <div class="question-user">
                        <h2>' . $userName . ' ' . $userSurname . '</h2>
                    </div>
                    <div class="question-info">
                        <p><strong>Question Title:</strong> ' . $questionTitle . '</p>
                        <p><strong>Question Description:</strong> ' . $questionDescription . '</p>';

            // Display module name
            echo '<p class="module-name">Module: ' . $moduleName . '</p>';

            // Display photo if available and file exists
            if (!empty($photo) && file_exists($photo)) {
                echo '<img src="' . $photo . '" onerror="this.style.display=\'none\'" alt="Question Photo" class="question-photo">';
            }

            echo '<div class="question-buttons">';
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $userID) {
                echo '<button class="answer-button" onclick="redirectToAnswerForm(' . $questionId .  ')">Answer this question</button>';
            }

            if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $userID) {
                echo '<button class="edit-button" onclick="redirectToEditForm(' . $questionId . ')">Edit</button>';
                echo '<button class="delete-button" onclick="deleteQuestion(' . $_SESSION['user']['id'] . ',' . $questionId . ')">Delete</button>';
            }

            echo '</div>
                </div>';

            // Fetch and display answers for the current question
            $answersSql = "SELECT answers.*, Users.name AS AnswerUserName, Users.surname AS AnswerUserSurname
                           FROM answers
                           INNER JOIN Users ON answers.user_id = Users.id
                           WHERE answers.post_id = :post_id";
            $answersStmt = $conn->prepare($answersSql);
            $answersStmt->bindParam(':post_id', $questionId, PDO::PARAM_INT);
            $answersStmt->execute();

            if ($answersStmt->rowCount() > 0) {
                echo '<div class="answers-container">
                        <h3>Answers:</h3>
                        <ul class="answers-list">';

                    while ($answerRow = $answersStmt->fetch(PDO::FETCH_ASSOC)) {
                        $answerUserName = $answerRow['AnswerUserName'];
                        $answerUserSurname = $answerRow['AnswerUserSurname'];
                        $answerText = $answerRow['answer'];

                        echo 
                             '<span class="answer-user">'. $answerUserName . ' ' . $answerUserSurname . ':</span> ' . $answerText . ' <br>';
            }   
                echo '</ul></div>';
            }

            echo '</div>'; 
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


$conn = null;
?>