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
if (isset($_POST['update_users'])) {
    $order_id = $_POST['users_id'];
    $update_payment = $_POST['update_payment'];

    $update_query = "UPDATE users SET payment_status = '$update_payment' WHERE id = '$order_id'";
    if (mysqli_query($conn, $update_query)) {
        $message[] = 'Order updated successfully!';
    } else {
        $message[] = 'Failed to update order: ' . mysqli_error($conn);
    }
}

// Function to delete order
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $delete_query = "DELETE FROM users WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_query)) {
        $message[] = 'Order deleted successfully!';
    } else {
        $message[] = 'Failed to delete order: ' . mysqli_error($conn);
    }
    header('location:admin_user.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@1.10.2/font/bootstrap.icons.css">
    <title>Admin</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php   
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
            <div class="message">
                <span>'.$message.'</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>
            ';  
        }
    }
    ?>
    <section class="user-container">
        <h1 class="title">Total Registerd Users</h1>
        <div class="box-container">
        <?php 
                $select_users = mysqli_query($conn, "SELECT * FROM users") or die ('Query failed: ' . mysqli_error($conn));
                if (mysqli_num_rows($select_users) > 0) {
                    while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            ?>
            <div class="box">
                <p>User ID: <span><?php echo $fetch_users['id']; ?></span></p>
                <p>User Name: <span><?php echo $fetch_users['name']; ?></span></p>
                <p>Email: <span><?php echo $fetch_users['email']; ?></span></p>
                <p>User Type: <span style="color:<?php if($fetch_users['user_type']== 'admin'){echo 'orange';}; ?>"><?php echo $fetch_users['user_type']; ?></span></p>
                <a href="admin_user.php?delete=<?php echo $fetch_users['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
            </div>
            <?php 
                }
            }
            ?>
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
