<?php
session_start();

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Function to get the user's name
function getUserName() {
    return isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';
}

function getUserSurname() {
    return isset($_SESSION['user']['surname']) ? $_SESSION['user']['surname'] : '';
}

// Function to display login and signup buttons
function displayLoginSignupButtons() {
    echo '
        <button class="login-btn" onclick="redirectToLogin()">Login</button>
        <button class="signup-btn" onclick="redirectToSignup()">Sign Up</button>
    ';
}

// Function to display the user's name and sign-out button
function displayUserDetails() {
    $userName = getUserName();
    $userSurname = getUserSurname();
    echo "
    <div style='margin-right: 10px;'>
        <div class='welcome-container' style='display: flex; align-items: center;'>
            <p style='font-size: 18px; margin: 0; color: #fff;'> $userName $userSurname</p>
            <form action='log-out.php' method='post'>
                <button type='submit' name='logout' class='logout-btn'>Sign Out</button>
            </form>
        </div>
    </div>
    ";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Page</title>
    <link rel="stylesheet" href="css/home_page.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&disp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }

    </style>
</head>

<body>

<div class="logo">
    <a><img src="Pictures/logo.jpg" width="162"></a>
</div>
<nav>
    <ul>
        <li><a href="homepage.php"> HOME </a></li>
        <li><a href="list_of_users.php"> USERS </a></li>
        <li><a href="list_of_modules.php"> MODULES </a></li>



   
        <li class="nav-buttons">
            <?php
            if (isLoggedIn()) {
                displayUserDetails();
            } else {
                displayLoginSignupButtons();
            }
            ?>
        </li>

        <li class="search-container">
           <div class="search"> </div>
           <input  type="text" id="searchInput" onkeyup="search_question()" placeholder="Search..." oninput="searchQuestions()"> 
        </li>
        
    </ul>
</nav>

    <div class="top-home">
        <div class="question-container">
            <h1>All questions</h1>
            <a href="<?php echo isLoggedIn() ? 'submit_form.php' : 'log-in.php'; ?>"><button class="button">Ask a question</button></a>
        </div>

        <?php
    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $sql = "SELECT id, name FROM Modules";
        $stmt = $conn->query($sql);

        
        if ($stmt->rowCount() > 0) {
            echo '<div class="module_listing_home">
                    <div class="btn-group">';

            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $moduleId = $row['id'];
                $moduleName = $row['name'];
                echo '<button class="buttons module-button" onclick="loadQuestions(' . $moduleId . ')">' . $moduleName . '</button>';
            }

            
            echo '<button class="buttons" onclick="loadAllQuestions()">All questions</button>';

            echo '</div>
                </div>';
        } else {
            echo "No modules found.";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    
    $conn = null;
    ?>

    </div>

    
    <ul class="questions-list" id="questionsListContainer"></ul>
    

    

    <footer>
    <div class="footer-container" >
        
        <p class="footer-info">Contact us: studentinteraction@gre.ac.uk</p>
        
        <button class="contact-admin-button" onclick="contactAdmin()">Contact Admin</button>
        <div class="footer-social-icons">
            <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
        </div>
    </div>
    </footer>
    


    <script>
        function loadQuestions(moduleId) {
            
            document.getElementById('questionsListContainer').innerHTML = '';

            
            fetch('get_questions.php?moduleId=' + moduleId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('questionsListContainer').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }

        
        function loadAllQuestions() {
           
            document.getElementById('questionsListContainer').innerHTML = '';

            
            fetch('get_questions.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('questionsListContainer').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
 
        

        
        window.onload = function () {
            loadAllQuestions();
        };


        function search_question() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let questions = document.querySelectorAll('.question');

        questions.forEach(question => {
        let title = question.querySelector('.question-info > p:nth-child(1)').textContent.toLowerCase();
        let description = question.querySelector('.question-info > p:nth-child(2)').textContent.toLowerCase();

        if (!(title.includes(input) || description.includes(input))) {
            question.style.display = "none";
        } else {
            question.style.display = "block";
        }
        });
        }


        function redirectToLogin() {
            window.location.href = 'log-in.php';
        }

        
        function redirectToSignup() {
            window.location.href = 'log-in.php?action=signup';
        }

        function redirectToEditForm(questionId) {
        window.location.href = 'edit_form.php?question_id=' + questionId;
        }

        function contactAdmin(){
            window.location.href = 'contact_admin.php';
        }
        
        function redirectToAnswerForm(questionId, moduleId) {
          if (typeof moduleId !== 'undefined') {
             window.location.href = "answer_form.php?questionId=" + questionId + "&moduleId=" + moduleId;
          } else {
            window.location.href = "answer_form.php?questionId=" + questionId;
          }
       }

        function redirectToAnswerForm(questionId, moduleId) {
           window.location.href = "answer_form.php?questionId=" + questionId + "&moduleId=" + moduleId;
        }

       function deleteQuestion(userId, questionId) {
        if (confirm("Are you sure you want to delete this question?")) {
        fetch('delete_question.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'userId=' + userId + '&questionId=' + questionId,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                alert('Question deleted successfully!');
                location.reload(); 
            } else {
                alert('Error deleting question. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    }

    </script>

</body>

</html>