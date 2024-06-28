<?php 
include 'connection.php';
session_start();

$user_id = $_SESSION['admin_id'];
if (!isset($user_id)) {
    header('location:login.php');
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
}

// Function to update order status
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];

    $update_query = "UPDATE orders SET payment_status = '$update_payment' WHERE id = '$order_id'";
    if (mysqli_query($conn, $update_query)) {
        $message[] = 'Order updated successfully!';
    } else {
        $message[] = 'Failed to update order: ' . mysqli_error($conn);
    }
}

// Function to delete order
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $delete_query = "DELETE FROM orders WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_query)) {
        $message[] = 'Order deleted successfully!';
    } else {
        $message[] = 'Failed to delete order: ' . mysqli_error($conn);
    }
    header('location:admin_order.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <title>Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .message {
            background: #ffdddd;
            color: #d8000c;
            padding: 10px;
            border-left: 6px solid #f44336;
            margin: 10px;
            position: relative;
            border-radius: 5px;
        }
        .message .bi-x-circle {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }
        .order-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .box {
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: calc(33.333% - 40px);
            box-sizing: border-box;
            transition: box-shadow 0.3s ease;
        }
        .box:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .box p {
            margin: 10px 0;
            color: #555;
        }
        .box p span {
            color: #333;
            font-weight: bold;
        }
        .box select,
        .box input[type="submit"],
        .box a {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background: var(--pink);
            cursor: pointer;
        }
        .box select {
            background: #eee;
            color: #333;
        }
        .box a {
            background: #dc3545;
        }
        .box a.delete {
            background: var(--pink);
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php   
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>'.$msg.'</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>
            ';  
        }
    }
    ?>
    <section class="order-container">
        <h1 class="title">Total Placed Orders</h1>
        <div class="box-container">
            <?php 
                $select_orders = mysqli_query($conn, "SELECT * FROM orders") or die ('Query failed: ' . mysqli_error($conn));
                if (mysqli_num_rows($select_orders) > 0) {
                    while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
            ?>
            <div class="box">
                <p>User Name: <span><?php echo $fetch_orders['name']; ?></span></p>
                <p>User ID: <span><?php echo $fetch_orders['user_id']; ?></span></p>
                <p>Contact Number: <span><?php echo $fetch_orders['number']; ?></span></p>
                <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
                <p>Total Price: <span>₱<?php echo number_format($fetch_orders['total_price'], 2); ?></span></p>
                <p>Pickup Datetime: <span><?php echo $fetch_orders['datetime_to_pickup']; ?></span></p>
                <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
                <p>Total Products: <span><?php echo $fetch_orders['total_products']; ?></span></p>
                <form method="post">
                    <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                    <select name="update_payment" required>
                        <option value="" disabled selected><?php echo $fetch_orders['payment_status']?></option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                    <input type="submit" name="update_order" value="Update Order" class="btn">
                    <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                </form>
            </div>
            <?php 
                }
            } else {
                echo '<p class="empty">No orders found!</p>';
            }
            ?>
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
