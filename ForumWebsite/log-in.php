<?php
session_start();
require_once('sign-up_query.php');
require_once('log-in_query.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup'])) {
        // Get user input
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        signUpUser($name, $surname, $email, $password, $confirm_password);
    } elseif (isset($_POST['login'])) {
        // Get user input for login
        $login_email = $_POST['email'];
        $login_password = $_POST['password'];

        // Call the login function from log-in_query.php
        loginUser($login_email, $login_password);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log-in form</title>
    <link rel="stylesheet" href="css/log-in.css">
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
</head>



<body>
<div class="container">
  <div class="forms-container">
    <div class="form-control signup-form">
      <form action=" " method="post">
      <h2>Sign up</h2>
      <input type="text" name="name" placeholder="Name" required />
      <input type="text" name="surname" placeholder="Surname" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="password" name="confirm_password" placeholder="Confirm password" required />
      <button type="submit" name="signup">Sign up</button>
      </form>
      
    </div>

    <div class="form-control signin-form">
      <form action=" " method="post">
        <h2>Sign in</h2>
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login" >Sign in</button>
      </form>
    </div>
  </div>
  <div class="intros-container">
    <div class="intro-control signin-intro">
      <div class="intro-control__inner">
        <h2>Welcome back!</h2>
        <p>
          Welcome back! We are so happy to have you here. It's great to see you again. We hope you had a safe and enjoyable time away.
        </p>
        <button id="signup-btn">No account yet? Sign up.</button>
      </div>
    </div>
    <div class="intro-control signup-intro">
      <div class="intro-control__inner">
        <h2>Come join us!</h2>
        <p>
          We are so excited to have you here.If you haven't already, create an account to get access to exclusive offers, rewards, and discounts.
        </p>
        <button id="signin-btn">Already have an account? Sign in.</button>
      </div>
    </div>
  </div>
</div>
</body>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const signupBtn = document.getElementById("signup-btn");
        const signinBtn = document.getElementById("signin-btn");
        const mainContainer = document.querySelector(".container");

        // Check for the query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const action = urlParams.get('action');

        // Toggle the class based on the query parameter
        if (action === 'signup') {
            mainContainer.classList.add("change"); // Use add instead of toggle
        }

        signupBtn.addEventListener("click", () => {
            mainContainer.classList.add("change"); // Always add the class when the button is clicked
        });

        signinBtn.addEventListener("click", () => {
            mainContainer.classList.remove("change"); // Use remove instead of toggle
        });
    });
</script>

</html>