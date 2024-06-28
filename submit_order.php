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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $pickup_datetime = mysqli_real_escape_string($conn, $_POST['pickup_datetime']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Calculate total quantity and total price
    $totalQuantity = 0;
    $totalPrice = 0;
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
    while ($cart_item = mysqli_fetch_assoc($cart_query)) {
        $quantity = intval($cart_item['quantity']);
        $totalQuantity += $quantity;
        $price = floatval($cart_item['price']);
        $totalPrice += $price * $quantity;
    }

    // Insert order details into the orders table
    $order_query = "INSERT INTO orders (user_id, name, number, email, address, datetime_to_pickup, total_products, total_price, payment_status)
                    VALUES ('$user_id', '$username', '$contact', '$email', '$address', '$pickup_datetime', '$totalQuantity', '$totalPrice', '$status')";

    if (mysqli_query($conn, $order_query)) {
        // If the order is inserted successfully, clear the cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

        // Redirect to a confirmation page
        header('Location: order_success.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Redirect to the checkout page if the form is not submitted
    header('Location: checkout.php');
}
?>
