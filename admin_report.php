<?php 
include 'connection.php';
session_start();

$user_id = $_SESSION['admin_id'];
if (!isset($user_id)){
    header('location:login.php');
    exit();
}

if (isset($_POST['logout'])){
    session_destroy();
    header('location:login.php');
    exit();
}

// Function to insert report record
function insertReport($conn, $admin_id, $file_path) {
    $generated_at = date('Y-m-d H:i:s');
    $query = "INSERT INTO reports (admin_id, generated_at, file_path) VALUES ('$admin_id', '$generated_at', '$file_path')";
    mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));
}

// Update order status
if (isset($_POST['update_order'])){
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];

    mysqli_query($conn, "UPDATE orders SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('Query failed');
    insertReport($conn, $user_id, 'Updated order status for order ID: ' . $order_id);
    header('location:admin_order.php');
    exit();
}

// Delete order
if (isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM orders WHERE id = '$delete_id'") or die('Query failed');
    insertReport($conn, $user_id, 'Deleted order ID: ' . $delete_id);
    header('location:admin_order.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <title>Admin Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .order-container {
            padding: 20px;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .box {
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            width: calc(33% - 40px);
            box-sizing: border-box;
            position: relative;
        }
        .box p {
            margin: 10px 0;
        }
        .box span {
            font-weight: bold;
        }
        .btn, .print-btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .delete {
            background-color: #dc3545;
        }
        .print-btn {
            background-color: #28a745;
            margin: 20px 0;
            text-align: center;
        }
        @media (max-width: 768px) {
            .box {
                width: calc(50% - 40px);
            }
        }
        @media (max-width: 480px) {
            .box {
                width: calc(100% - 40px);
            }
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }
            .box-container {
                display: block;
            }
            .box {
                width: 100%;
                page-break-after: always;
            }
            .print-btn, .delete, select, .btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <section class="order-container">
        <h1 class="title">Total Placed Orders</h1>
        <div class="box-container">
            <?php 
                $select_orders = mysqli_query($conn, "SELECT * FROM orders") or die('Query failed');
                if (mysqli_num_rows($select_orders) > 0){
                    while($fetch_orders = mysqli_fetch_assoc($select_orders)){
            ?>
            <div class="box">
                <p>User Name: <span><?php echo $fetch_orders['name']; ?></span></p>
                <p>User ID: <span><?php echo $fetch_orders['user_id']; ?></span></p>
                <p>Contact Number: <span><?php echo $fetch_orders['number']; ?></span></p>
                <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
                <p>Total Price: <span><?php echo $fetch_orders['total_price']; ?></span></p>
                <p>Pickup Datetime: <span><?php echo $fetch_orders['datetime_to_pickup']; ?></span></p>
                <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
                <p>Total Products: <span><?php echo $fetch_orders['total_products']; ?></span></p>
                <form method="post">
                    <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                    <select name="update_payment">
                        <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                    <input type="submit" name="update_order" value="Update Order" class="btn">
                    <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" class="btn delete" onclick="return confirm('Delete this order?')">Delete</a>
                </form>
            </div>
            <?php 
                    }
                } else {
                    echo '<p class="empty">No orders found!</p>';
                }
            ?>
        </div>
        <button class="print-btn" onclick="window.print()">Print Report</button>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
