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
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity']; // Capture the quantity input

    // Check if the product already exists in the cart
    $cart_number = mysqli_query($conn, "SELECT *     FROM cart WHERE name = '$product_name' AND user_id = '$user_id'") or die ('query failed');
    if (mysqli_num_rows($cart_number) > 0){
        $message[] = 'Product already exists in the cart';
    } else {
        // Insert the product into the cart
        $insert_cart = mysqli_query($conn, "INSERT INTO cart (user_id, pid, name, price, quantity, image) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die ('query failed: '.mysqli_error($conn));

        if ($insert_cart) {
            // Update the inventory quantity
            $update_inventory = mysqli_query($conn, "UPDATE inventory SET quantity = quantity - $product_quantity WHERE id = '$product_id'") or die ('query failed: '.mysqli_error($conn));

            if ($update_inventory) {
                $message[] = 'Product successfully added to cart';
            } else {
                // If updating inventory fails, remove the product from the cart
                mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id' AND pid = '$product_id'") or die ('query failed: '.mysqli_error($conn));
                $message[] = 'Failed to update inventory. Product not added to cart.';
            }
        } else {
            $message[] = 'Failed to add product to cart';
        }
    }
}
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
    <?php include 'header.php'; ?>
    <div class="shop">
        <h1 class="title">Shop's Flowers</h1>
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
        <div class="box-container">
            <?php 
                $select_inventory = mysqli_query($conn, "SELECT * FROM inventory") or die ('query failed');
                if (mysqli_num_rows($select_inventory) > 0){
                    while($fetch_inventory = mysqli_fetch_assoc($select_inventory)){
            ?>
            <form action="" method="post" class="box">
                <img src="image/<?php echo $fetch_inventory['image']; ?>" alt="Product Image">
                <div class="price">₱<?php echo $fetch_inventory['price']; ?>/-</div>
                <div class="name"><?php echo $fetch_inventory['name']; ?></div>
                <input type="hidden" name="product_id" value="<?php echo $fetch_inventory['id'] ?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_inventory['name'] ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_inventory['price'] ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_inventory['image'] ?>">
                <input type="number" name="product_quantity" value="1" min="1" class="quantity">
                <div class="icon">
                    <a href="view_page.php?pid=<?php echo $fetch_inventory['id']; ?>" class="bi bi-eye-fill"></a>
                    <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                </div>
                <button type="submit" name="add_to_cart" class="cart-button">Cart</button>
            </form>
            <?php 
                    }
                } else {
                    echo '<p class="empty">No products added yet!</p>';
                }
            ?>
        </div>
    </div>
    
    <script type="text/javascript" src="script.js"></script>
</body>
</html>

                