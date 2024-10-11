<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Admin</title>
  <link rel="stylesheet" href="css/home_page.css">
  <link rel="stylesheet" href="css/contact_admin.css">
  <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
  
</head>

<body>

<div class = "logo"> <a> <img src="Pictures/logo.jpg" width="160" height="118.5"></a>
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

<form id="contactAdminForm" method="post" action="send_email.php">
    
    <label for="contactName">Your Full Name:</label>
    <input type="text" id="contactName" name="contactName" required>

    <label for="contactEmail">Your Email:</label>
    <input type="email" id="contactEmail" name="contactEmail" required>

    <label for="contactMessage">Your Message:</label>
    <textarea id="contactMessage" name="contactMessage" rows="4" required></textarea>

    <button type="submit">Send Message</button>
</form>



</body>

</html>