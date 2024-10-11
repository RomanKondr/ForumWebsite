<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: log-in.php');
    exit();
}

// Get the post ID from the query parameters
$questionId = isset($_GET['questionId']) ? $_GET['questionId'] : 0;
$moduleId = isset($_GET['moduleId']) ? $_GET['moduleId'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Answer form</title>
  <link rel="stylesheet" href="css/home_page.css">
  <link rel="stylesheet" href="css/answer_form.css">
  
</head>

<body>

<div class = "logo"> <a> <img src="Pictures/logo.jpg" width="160" height="118.5"></a>
  </div>

<nav>
     <ul>
       <li> <a href = "homepage.php"> Home </a></li>
       <li> <a href = "list_of_users.php"> Users </a></li>
       <li> <a href = "list_of_modules.php"> Modules </a></li>
       
     </ul>

     <div class="footer-info">
            <p>Contact us: studentinteraction@gre.ac.uk</p>
     </div>
</nav>

<div class="answer-container">
    <h1>Answer the Question</h1>
    <form action="submit_answer.php" method="post">
    <textarea id="answer" name="answer" rows="4" required></textarea>
    <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">
    <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
    <button type="submit" class="button">Submit Answer</button>
    </form>
</div>
</body>

</html>