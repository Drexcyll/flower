<?php
// Start or resume the session
session_start();

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

// Fetch cart items
$cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
$totalQuantity = 0;
$totalPrice = 0;
while ($cart_item = mysqli_fetch_assoc($cart_query)) {
    $totalQuantity += intval($cart_item['quantity']);
    $totalPrice += floatval($cart_item['price']) * intval($cart_item['quantity']);
}

// Now include header.php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Checkout</title>
</head>
<body>
    <div class="container">
        <div class="checkout-page">
            <h2>Checkout</h2>
            <form action="submit_order.php" method="post">
                <div class="form-group">
                    <label for="username">Full Name</label>
                    <input type="text" id="username" name="username" value="<?php echo $_SESSION['user_name']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div class="form-group">
                    <label for="pickup_datetime">Pickup Date and Time</label>
                    <input type="datetime-local" id="pickup_datetime" name="pickup_datetime" required>
                </div>
                <div class="form-group">
                    <label for="totalQuantity">Total Quantity</label>
                    <input type="text" id="totalQuantity" name="totalQuantity" value="<?php echo $totalQuantity; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="totalPrice">Total Price</label>
                    <input type="text" id="totalPrice" name="totalPrice" value="<?php echo number_format($totalPrice, 2); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" id="status" name="status" value="Pending" readonly>
                </div>
                <button type="submit" class="checkout-button">Place Order</button>
            </form>
        </div>
    </div>
</body>
</html>
