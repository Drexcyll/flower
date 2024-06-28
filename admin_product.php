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

    /*----------adding products to database--------------*/ 
    if (isset($_POST['add_product'])){
        $product_name = mysqli_real_escape_string($conn, $_POST['name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['price']);
        $product_quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'image/'.$image;

        $select_product_name = mysqli_query($conn, "SELECT name FROM inventory WHERE name = '$product_name'") or die ('query failed');
        if (mysqli_num_rows($select_product_name) > 0){
            $message[] = 'product name already exists';
        } else {
            $insert_product = mysqli_query($conn, "INSERT INTO inventory (name, price, quantity, image) VALUES('$product_name', '$product_price', '$product_quantity', '$image')") or die ('query failed');
            if ($insert_product){
                if ($image_size > 2000000) {
                    $message[] = 'product image is too large';
                } else {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'product added successfully!';
                }
            }
        }
    }

    /*----------deleting a product from the database--------------*/ 
    if (isset($_GET['delete'])){
        $delete_id = $_GET['delete'];
        $delete_query = mysqli_query($conn, "DELETE FROM inventory WHERE id = $delete_id") or die('query failed');
        if ($delete_query){
            header('location:admin_product.php');
        }
    }

    /*----------updating a product in the database--------------*/ 
    if (isset($_POST['update_product'])){
        $update_id = $_POST['update_id'];
        $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
        $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
        $update_quantity = mysqli_real_escape_string($conn, $_POST['update_quantity']);
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'image/'.$update_image;

        if(!empty($update_image)){
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
            $update_query = mysqli_query($conn, "UPDATE inventory SET name = '$update_name', price = '$update_price', quantity = '$update_quantity', image = '$update_image' WHERE id = $update_id") or die('query failed');
        } else {
            $update_query = mysqli_query($conn, "UPDATE inventory SET name = '$update_name', price = '$update_price', quantity = '$update_quantity' WHERE id = $update_id") or die('query failed');
        }

        if ($update_query){
            $message[] = 'product updated successfully!';
            header('location:admin_product.php');
        }
    }

    // Fetching all products from the inventory
    $select_all_products = mysqli_query($conn, "SELECT * FROM inventory") or die ('query failed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <title>Admin Products</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php 
        if(isset($message)){
            foreach ($message as $message){
                echo '
                <div class="message">
                    <span>'.$message.'</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div>
                ';  
            }
        }
    ?>
    <section class="add-products">
        <form method="post" action="" enctype="multipart/form-data">
            <h1 class="title">Add New Product</h1>
            <div class="input-field">
                <label>Product Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-field">
                <label>Product Price</label>
                <input type="text" name="price" required>
            </div>
            <div class="input-field">
                <label>Product Quantity</label>
                <input type="number" name="quantity" required>
            </div>
            <div class="input-field">
                <label>Product Image</label>
                <input type="file" name="image" accept="image/jpg, image/png, image/jpeg, image/webp" required>
            </div>
            <input type="submit" name="add_product" value="Add Product" class="btn">
        </form>
    </section>
    <section class="product-list">
        <h1 class="title">Product List</h1>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Product Quantity</th>
                    <th>Product Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($select_all_products)): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="50"></td>
                        <td>
                            <a href="admin_product.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');" class="btn-delete">Delete</a>
                            <button class="btn-edit" onclick="editProduct(<?php echo $row['id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['price']; ?>', '<?php echo $row['quantity']; ?>', '<?php echo $row['image']; ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal">
        <form method="post" action="" enctype="multipart/form-data" class="modal-content">
            <h1 class="title">Edit Product</h1>
            <input type="hidden" name="update_id" id="update_id">
            <div class="input-field">
                <label>Product Name</label>
                <input type="text" name="update_name" id="update_name" required>
            </div>
            <div class="input-field">
                <label>Product Price</label>
                <input type="text" name="update_price" id="update_price" required>
            </div>
            <div class="input-field">
                <label>Product Quantity</label>
                <input type="number" name="update_quantity" id="update_quantity" required>
            </div>
            <div class="input-field">
                <label>Product Image</label>
                <input type="file" name="update_image" id="update_image" accept="image/jpg, image/png, image/jpeg, image/webp">
            </div>
            <input type="submit" name="update_product" value="Update Product" class="btn">
            <button type="button" class="btn-close" onclick="closeModal()">Close</button>
        </form>
    </div>
                    
    <script type="text/javascript" src="script.js"></script>
    <script>
        function editProduct(id, name, price, quantity, image) {
            document.getElementById('update_id').value = id;
            document.getElementById('update_name').value = name;
            document.getElementById('update_price').value = price;
            document.getElementById('update_quantity').value = quantity;
            document.getElementById('update_image').src = image;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>
