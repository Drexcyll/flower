<?php 
    include 'connection.php';
    session_start();

    $user_id = $_SESSION['user_id'];
    if (!isset($user_id)){
        header('location:index.php');
    }
    if (isset($_POST['logout'])){
        session_destroy();
        header('location:login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@1.10.2/font/bootstrap.icons.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Flower Shop</title>
</head>
<body>
    <?php include 'header.php' ?>
    
    <?php include 'footer.php' ?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>