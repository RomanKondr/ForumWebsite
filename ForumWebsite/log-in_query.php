<?php

function loginUser($email, $password)
{
    $host = 'mysql.cms.gre.ac.uk';
    $db_name = 'mdb_rk7545s';
    $user = 'rk7545s';
    $password_db = 'rk7545s';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $user, $password_db);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!empty($email) && !empty($password)) {
            $sql = "SELECT * FROM `Users` WHERE `email`=? AND `PASSWORD`=? ";
            $query = $conn->prepare($sql);
            $query->execute(array($email, $password));
            $row = $query->rowCount();
            $fetch = $query->fetch();
            if ($row > 0) {
                // Store user details in the session
                $_SESSION['user'] = array(
                    'id' => $fetch['id'],
                    'name' => $fetch['name'], // Adjust the column names accordingly
                    'surname' => $fetch['surname']
                );
                header("location: homepage.php");
                exit;
            } else {
                echo "
                <script>alert('Invalid email or password')</script>
                ";
            }
        } else {
            echo "
            <script>alert('Please complete the required fields!')</script>
            ";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>