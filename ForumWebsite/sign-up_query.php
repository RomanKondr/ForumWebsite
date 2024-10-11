<?php


function signUpUser($name, $surname, $email, $password, $confirm_password) {
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }
  
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  
    $host = 'host name';
    $db_name = 'db_name';
    $user = 'use_name';
    $password_db = 'db_password';  
  
      try {
          $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $user, $password_db);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
          
          $checkUserQuery = $conn->prepare("SELECT id FROM Users WHERE email = :email");
          $checkUserQuery->bindParam(':email', $email);
          $checkUserQuery->execute();
  
          if ($checkUserQuery->rowCount() > 0) {
              
              echo "
                <script>alert('User with such email already exists')</script>
                ";
          } else {
              // User does not exist, insert into the database
              $insertUserQuery = $conn->prepare("INSERT INTO Users (name, surname, email, PASSWORD) VALUES (:name, :surname, :email, :password)");
              $insertUserQuery->bindParam(':name', $name);
              $insertUserQuery->bindParam(':surname', $surname);
              $insertUserQuery->bindParam(':email', $email);
              $insertUserQuery->bindParam(':password', $password);
  
              if ($insertUserQuery->execute()) {
                  // Registration successful, redirect or perform other actions
  
                  // Now, let's redirect to the page where you want to go after successful signup
                  header("Location: log-in.php");
                  exit();
              } else {
                  // Registration failed, handle accordingly (e.g., show an error message)
                  echo "Registration failed!";
              }
          }
      } catch (PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
      } finally {
          
          $conn = null;
      }
}
?>