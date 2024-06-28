<?php
// Start or resume the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .order-success-page {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .order-success-page h2 {
            color: #28a745;
            margin-bottom: 20px;
        }

        .order-success-page p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .order-success-page .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .order-success-page .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            .order-success-page {
                padding: 20px;
            }

            .order-success-page p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="order-success-page">
        <h2>Order Placed Successfully!</h2>
        <p>Your order has been placed successfully. You will receive a text confirmation shortly.</p>
        <a href="index.php" class="btn">Go to Homepage</a>
    </div>
</body>
</html>
