<?php
session_start();
include('inc/connection.php');

if (!isset($_SESSION['trader_id'])) {
    die("Trader ID not set in session. Please log in.");
}

if (!isset($_GET['id'])) {
    die("Product ID is required to edit the product.");
}

$product_id = $_GET['id'];
$trader_id = $_SESSION['trader_id'];

// Fetch product details from the database
$query = "SELECT PRODUCT_NAME, PRODUCT_DESC, PRICE, IMAGE, STOCK_AVAILABLE, MIN_ORDER, MAX_ORDER, ALLERGY_INFO 
          FROM products 
          WHERE ADD_PRODUCT_ID = :product_id AND TRADER_ID = :trader_id";
$stmt = oci_parse($conn, $query);
oci_bind_by_name($stmt, ':product_id', $product_id);
oci_bind_by_name($stmt, ':trader_id', $trader_id);
oci_execute($stmt);

$product = oci_fetch_assoc($stmt);

if (!$product) {
    die("Product not found or you do not have permission to edit this product.");
}

// Handle form submission for updating the product
if (isset($_POST['update'])) {
    $product_name = $_POST['product_name'];
    $product_desc = $_POST['product_desc'];
    $price = $_POST['price'];
    $avalable = $_POST['avalable'];
    $min_order = $_POST['min_order'];
    $max_order = $_POST['max_order'];
    $allergy = $_POST['allergy'];
    $image = $product['IMAGE']; // Keep the current image if a new one is not uploaded

    // File upload handling (if a new image is uploaded)
    if (!empty($_FILES["image"]["name"])) {
        $targetDirectory = "../images/";
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        $fileSizeKB = $_FILES["image"]["size"] / 1024; // Size in KB
        if ($fileSizeKB < 20) {
            echo "Sorry, your file is too small. Minimum size allowed is 20KB.";
            $uploadOk = 0;
        } elseif ($fileSizeKB > 3072) { // 3MB
            echo "Sorry, your file is too large. Maximum size allowed is 3MB.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $image = $_FILES["image"]["name"]; // Use the new uploaded image file name
                echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update product details in the database
    $query = "UPDATE products 
              SET PRODUCT_NAME = :product_name, PRODUCT_DESC = :product_desc, PRICE = :price, IMAGE = :image, 
                  STOCK_AVAILABLE = :avalable, MIN_ORDER = :min_order, MAX_ORDER = :max_order, ALLERGY_INFO = :allergy 
              WHERE ADD_PRODUCT_ID = :product_id AND TRADER_ID = :trader_id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':product_name', $product_name);
    oci_bind_by_name($stmt, ':product_desc', $product_desc);
    oci_bind_by_name($stmt, ':price', $price);
    oci_bind_by_name($stmt, ':image', $image);
    oci_bind_by_name($stmt, ':avalable', $avalable);
    oci_bind_by_name($stmt, ':min_order', $min_order);
    oci_bind_by_name($stmt, ':max_order', $max_order);
    oci_bind_by_name($stmt, ':allergy', $allergy);
    oci_bind_by_name($stmt, ':product_id', $product_id);
    oci_bind_by_name($stmt, ':trader_id', $trader_id);

    $result = oci_execute($stmt);

    if ($result) {
        echo '<script>alert("Product updated successfully.");</script>';
        echo '<script>window.location.href = "add_product.php";</script>';
    } else {
        $error = oci_error($stmt);
        echo "Error updating product: " . $error['message'];
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <?php include('inc/link.php'); ?>
</head>
<body>
    <div class="main-wrapper">
        <div class="header-container fixed-top">
            <?php include('inc/header.php'); ?>
        </div>

        <?php include('inc/sidebar.php'); ?>
        
        <div class="content-wrapper">
            <div class="container">
                <h2>Edit Product</h2>
                <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control shadow-none" id="product_name" name="product_name" value="<?php echo $product['PRODUCT_NAME']; ?>" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="product_desc" class="form-label">Product Description</label>
                            <input type="text" class="form-control shadow-none" id="product_desc" name="product_desc" value="<?php echo $product['PRODUCT_DESC']; ?>" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control shadow-none" id="price" name="price" value="<?php echo $product['PRICE']; ?>" required>
                        </div>
                        <!-- <div class="col-lg-12 mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control shadow-none" id="image" name="image">
                            <small>Current Image: <?php echo $product['IMAGE']; ?></small>
                        </div> -->
                        <div class="col-lg-12 mb-3">
                            <label for="avalable" class="form-label">Available</label>
                            <input type="number" class="form-control shadow-none" id="avalable" name="avalable" value="<?php echo $product['STOCK_AVAILABLE']; ?>" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="min_order" class="form-label">Min Order</label>
                            <input type="number" class="form-control shadow-none" id="min_order" name="min_order" value="<?php echo $product['MIN_ORDER']; ?>" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="max_order" class="form-label">Max Order</label>
                            <input type="number" class="form-control shadow-none" id="max_order" name="max_order" value="<?php echo $product['MAX_ORDER']; ?>" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="allergy" class="form-label">Allergy Information</label>
                            <input type="text" class="form-control shadow-none" id="allergy" name="allergy" value="<?php echo $product['ALLERGY_INFO']; ?>">
                        </div>
                        <div class="modal-footer mx-3 mb-4">
                            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary mx-4 me-4" name="update">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
