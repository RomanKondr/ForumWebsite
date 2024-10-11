<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST["contactName"];
    $email = $_POST["contactEmail"];
    $message = $_POST["contactMessage"];

    ini_set("SMTP", "smtp.gre.ac.uk"); 

    $to = "email..."; 
    $subject = "Ask admin for help";
    $headers = "From: $email";

    $emailMessage = "Name: $name\n";
    $emailMessage .= "Email: $email\n\n";
    $emailMessage .= "Message:\n$message";

    $success = mail($to, $subject, $emailMessage, $headers);

    if ($success) {
        echo "<p class='highlight'>Your message has been sent successfully. Thank you!</p>";
    } else {
        echo "<p class='highlight'>Oops! Something went wrong. Please try again later.</p>";
    }
}
?>
