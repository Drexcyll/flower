<?php 
    include 'connection.php';
    session_start();

    $user_id = $_SESSION['admin_id'];
    if (!isset($user_id)){
        header('location:login.php');
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
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@1.10.2/font/bootstrap.icons.css">
    <title>Admin</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <section class="dashboard">
        <h1 class="title">Dashboard</h1>
        <div class="box-container">
            <div class="box">
                <?php
                    $total_pendings = 0;
                    $select_pendings = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status = 'pending'") or die('Query failed: ' . mysqli_error($conn));
                    while ($fetch_pendings = mysqli_fetch_assoc($select_pendings)){
                        $total_pendings += $fetch_pendings['total_price'];
                    }
                ?>
                <h3>₱ <?php echo $total_pendings; ?></h3>
                <p>Total Pendings</p>
            </div>
            <div class="box">
                <?php
                    $total_completed = 0;
                    $select_completed = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status = 'completed'") or die('Query failed: ' . mysqli_error($conn));
                    while ($fetch_completed = mysqli_fetch_assoc($select_completed)){
                        $total_completed += $fetch_completed['total_price'];
                    }
                ?>
                <h3>₱ <?php echo $total_completed; ?></h3>
                <p>Total Completed</p>
            </div>
            <div class="box">
                <?php
                    $select_orders = mysqli_query($conn, "SELECT * FROM orders") or die('Query failed: ' . mysqli_error($conn));
                    $num_of_orders = mysqli_num_rows($select_orders);                  
                ?>
                <h3><?php echo $num_of_orders; ?></h3>
                <p>Orders Placed</p>
            </div>
            <div class="box">
                <?php
                    $select_inventory = mysqli_query($conn, "SELECT * FROM inventory") or die('Query failed: ' . mysqli_error($conn));
                    $num_of_inventory = mysqli_num_rows($select_inventory);                  
                ?>
                <h3><?php echo $num_of_inventory; ?></h3>
                <p>Products Added</p>
            </div>
            <div class="box">
                <?php
                    $select_users = mysqli_query($conn, "SELECT * FROM users WHERE user_type = 'user'") or die('Query failed: ' . mysqli_error($conn));
                    $num_of_users = mysqli_num_rows($select_users);                  
                ?>
                <h3><?php echo $num_of_users; ?></h3>
                <p>Registered Users</p>
            </div>
            <div class="box">
                <?php
                    $select_admins = mysqli_query($conn, "SELECT * FROM users WHERE user_type = 'admin'") or die('Query failed: ' . mysqli_error($conn));
                    $num_of_admins = mysqli_num_rows($select_admins);                  
                ?>
                <h3><?php echo $num_of_admins; ?></h3>
                <p>Total Admins</p>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
