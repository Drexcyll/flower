<?php 
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)){
    header('location:index.php');
    exit();
}

if (isset($_POST['logout'])){
    session_destroy();
    header('location:login.php');
    exit();
}

if (isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = floatval(str_replace(',', '', $_POST['product_price'])); // Ensure price is treated as float and remove commas
    $product_image = $_POST['product_image'];
    $product_quantity = intval($_POST['product_quantity']); // Capture the quantity input as integer

    $cart_number = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$product_name' AND user_id = '$user_id'") or die ('query failed');
    if (mysqli_num_rows($cart_number) > 0){
        $message[] = 'Product already exists in the cart';
    } else {
        $insert_query = "INSERT INTO cart (user_id, pid, name, price, quantity, image) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')";
        if (mysqli_query($conn, $insert_query)) {
            $message[] = 'Product successfully added to cart';
        } else {
            $message[] = 'Failed to add product to cart: ' . mysqli_error($conn);
        }
    }
}

if (isset($_POST['delete_from_cart'])){
    $delete_id = $_POST['cart_id'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$delete_id' AND user_id = '$user_id'") or die('query failed');
    $message[] = 'Product removed from cart';
}

// Fetch the cart products
$cart_products = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die ('query failed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Document</title>
</head>
<body>
    <?php include 'header.php' ?>

    <?php 
    if (isset($message)){
        foreach ($message as $msg){
            echo '
                <div class="message">
                    <span>'.$msg. '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div>
            ';
        }
    }
    ?>
    <div class="view_page">
        <?php 
        if (isset($_GET['pid'])){
            $pid = $_GET['pid'];
            $select_inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE id = '$pid'") or die ('query failed');
            if (mysqli_num_rows($select_inventory) > 0){
                while($fetch_inventory = mysqli_fetch_assoc($select_inventory)){
                    $price = floatval(str_replace(',', '', $fetch_inventory['price']));
        ?>
        <form action="" method="post" class="box">
            <img src="image/<?php echo $fetch_inventory['image']; ?>" alt="Product Image">
            <div class="detail">
                <div class="price">₱<?php echo number_format($price, 2); ?>/-</div>
                <div class="name"><?php echo $fetch_inventory['name']; ?></div>
                <div class="product-detail"><?php echo $fetch_inventory['product_detail']; ?></div>
                <input type="hidden" name="product_id" value="<?php echo $fetch_inventory['id'] ?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_inventory['name'] ?>">
                <input type="hidden" name="product_price" value="<?php echo $price ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_inventory['image'] ?>">
                <input type="number" name="product_quantity" value="1" min="1" class="quantity">
                <button type="submit" name="add_to_cart" class="cart-button">Add to Cart</button>
            </div>
        </form>
        <?php 
                }
            } else {
                echo '<p class="empty">Product not found!</p>';
            }
        }  
        ?>
    </div>

    <!-- Display the cart products -->
    <div class="cart">
        <h2>Your Cart</h2>
        <div class="cart-items">
            <?php 
            $total = 0;
            if (mysqli_num_rows($cart_products) > 0){
                while($cart_item = mysqli_fetch_assoc($cart_products)){
                    $price = floatval(str_replace(',', '', $cart_item['price']));
                    $quantity = intval($cart_item['quantity']);
                    $total_price = $price * $quantity; // Ensure the price is multiplied by the quantity as a float
                    $total += $total_price;
            ?>
            <div class="cart-item">
                <img src="image/<?php echo $cart_item['image']; ?>" alt="Product Image">
                <div class="details">
                    <div class="name"><?php echo $cart_item['name']; ?></div>
                    <div class="price">₱<?php echo number_format($price, 2); ?>/-</div>
                    <div class="quantity">Quantity: <?php echo $quantity; ?></div>
                </div>
                <form action="" method="post" class="delete-form">
                    <input type="hidden" name="cart_id" value="<?php echo $cart_item['id']; ?>">
                    <button type="submit" name="delete_from_cart" class="delete-button">Delete</button>
                </form>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">Your cart is empty!</p>';
            }
            ?>  
        </div>
        <!-- Display the total price -->
        <?php 
        if (mysqli_num_rows($cart_products) > 0) { 
        ?>
        <div class="total">
            <h3>Total: <span class="total-price">₱<?php echo number_format($total, 2); ?></span></h3>
        </div>
        <form action="checkout.php" method="post">
            <button type="submit" class="checkout-button">Proceed to Checkout</button>
        </form>
        <?php } ?>
    </div>

    <script type="text/javascript" src="script.js"></script>
</body>
</html>
