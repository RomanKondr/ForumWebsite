<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve user ID from the session
  $userID = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

  
  if (!$userID) {
      // Redirect to the login page
      header("Location: log-in.php");
      exit;
  }

  
  $questionTitle = $_POST['Title'];
  $questionDescription = $_POST['Description'];
  $selectedModuleName = $_POST['modules'];

    try {
        $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the module ID based on the selected module name
        $stmt = $conn->prepare("SELECT id FROM Modules WHERE name = :moduleName");
        $stmt->bindParam(':moduleName', $selectedModuleName);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $moduleID = $row['id'];

        
        $targetDirectory = "Pictures/"; 
        $targetFile = $targetDirectory . basename($_FILES["photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["photo"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        // Insert question into posts table with the file path
        $sql = "INSERT INTO posts (User_ID, Title, Description, Module_ID, Photo) VALUES (:userID, :title, :description, :moduleID, :photo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT); 
        $stmt->bindParam(':title', $questionTitle);
        $stmt->bindParam(':description', $questionDescription);
        $stmt->bindParam(':moduleID', $moduleID);
        $stmt->bindParam(':photo', $targetFile);

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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ask question</title>
  <link rel="stylesheet" href="css/submit_form.css">
  <link rel="stylesheet" href="css/home_page.css">
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
      </ul>
      <div class="footer-info">
          <p>Contact us: studentinteraction@gre.ac.uk</p>
      </div>
  </nav>



<section id="question-form">
  <div class="container">
    <h2>Ask a Question</h2>

    
    <form id="questionForm" action="submit_form.php" enctype="multipart/form-data" method="post">


      
      <div class="form-group">
        <label for="questionTitle">Question Title:</label>
        <input class="form-control" id="questionTitle" name="Title" placeholder="Enter question title" required="" type="text"/>
      </div>

      
      <div class="form-group">
        <label for="questionDescription">Question Description:</label>
        <textarea class="form-control" id="questionDescription" name="Description" placeholder="Enter question description" required=""></textarea>
      </div>
      
      <input type="hidden" id="moduleID" name="moduleID" value="" />

      
      <div class="form-group">
      <input type="file" id="upload" name="photo" hidden/>
      <label for="upload" class="file-label">Add photo</label>
      <span id="file-chosen">No file chosen</span>
      </div>

      <?php

      try {
      $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk; dbname=mdb_rk7545s; charset=utf8mb4', 'rk7545s', 'rk7545s');
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      
      $sql = "SELECT name FROM Modules";
      $stmt = $conn->query($sql);

      
      if ($stmt->rowCount() > 0) {
        echo '<div class="form-group">
                <label for="modules">Choose Module</label>
                <select name="modules" id="modules">';

        
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $moduleName = $row['name'];
            echo '<option value="' . $moduleName . '">' . $moduleName . '</option>';
      }

      echo '</select>
            </div>';
      } else {
      echo "No modules found.";
      }
      } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
      }

      
      $conn = null;
      ?>

      

      <div>
      
      <button class="btn btn-primary" type="submit" onclick="setModuleID()">Submit Question</button>

      
      <button class="btn btn-secondary" type="button" onclick="clearForm()">Cancel</button>
      </div>


      
      
    </form>
  </div>
</section>

<script>
    const actualBtn = document.getElementById('upload');

    const fileChosen = document.getElementById('file-chosen');

    actualBtn.addEventListener('change', function(){
    fileChosen.textContent = this.files[0].name
})
</script>

<script>
  function setModuleID() {
    var moduleSelect = document.getElementById("modules");
    var selectedModuleID = moduleSelect.options[moduleSelect.selectedIndex].value;
    document.getElementById("moduleID").value = selectedModuleID;
  }


  function clearForm() {
    document.getElementById("questionForm").reset();
  }
</script>

</body>

</html>