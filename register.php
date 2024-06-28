<?php 
    include 'connection.php';

    if (isset($_POST['submit-btn'])){
        $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $name = mysqli_real_escape_string($conn, $filter_name);

        $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $email = mysqli_real_escape_string($conn, $filter_email);

        $filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $password = mysqli_real_escape_string($conn, $filter_password);

        $filter_cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_STRING);
        $cpassword = mysqli_real_escape_string($conn, $filter_cpassword);

        // Corrected SQL query syntax
        $select_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die ('query failed');

        if (mysqli_num_rows($select_user) > 0){
            $message[] = 'user already exists';
        } else {
            if ($password != $cpassword){
                $message[] = 'passwords do not match';
            } else {
                // Corrected SQL query syntax
                mysqli_query($conn, "INSERT INTO users(name, email, password) VALUES ('$name', '$email', '$password')") or die ('query failed');
                $message[] = 'registered successfully';
                header('location:login.php');
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>User Registration Page</title>
</head>
<body>
    <section class="form-container">
        <?php 
            if (isset($message)){
                foreach ($message as $msg){
                    echo '
                    <div class="message">
                        <span>'.$msg.'</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>';  
                }
            }
        ?>
        <form action="" method="post">
            <h3>Register Now</h3>
            <input type="text" name="name" placeholder="Enter your name" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="password" name="cpassword" placeholder="Re-enter your password" required>
            <input type="submit" name="submit-btn" class="btn" value="Register Now">
            <p>Already have an account? <a href="login.php">Login Now!</a></p>
        </form>
    </section>
</body>
</html>
