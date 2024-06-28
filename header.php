<?php
// Include the file where $conn is defined
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: index.php');
    exit();
}

// Assign user ID from session variable
$user_id = $_SESSION['user_id'];

// Fetch user information from the database
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
if (!$user_query) {
    // Handle the query error
    die('Query failed: ' . mysqli_error($conn));
}

// Fetch user data
$user_data = mysqli_fetch_assoc($user_query);

// Close the query
mysqli_free_result($user_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Header</title>
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="admin.php" class="logo">Grace's Flower Shop</a>
            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
            </nav>
            <div class="icons">
                <i class="bi bi-list" id="menu-btn"></i>
                <?php 
                    // Fetch cart data
                    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
                    if (!$cart_query) {
                        // Handle the query error
                        die('Query failed: ' . mysqli_error($conn));
                    }

                    // Get the number of items in the cart
                    $cart_num_rows = mysqli_num_rows($cart_query);

                    // Close the query
                    mysqli_free_result($cart_query);
                ?>
                <a href="view_page.php"><i class="bi bi-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
                <i class="bi bi-person" id="user-btn"></i>
                <div class="user-box">
                <p>username: <span><?php echo $_SESSION['user_name'];?></span></p>
                <p>email: <span><?php echo $_SESSION['user_email'];?></span></p>
                <form method="post" class="logout">
                <button name="logout" class="logout-btn">LOG OUT</button>
                </form>
            </div>


        </div>
    </header>
</body>
</html>
