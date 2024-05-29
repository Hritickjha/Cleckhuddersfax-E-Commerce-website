<?php
session_start();
include('inc/connection.php');

$errors = [];

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Retrieve form data
    $product_name = $_POST['product_name'];
    $product_desc = $_POST['product_desc'];
    $price = $_POST['price'];
    $avalable = $_POST['avalable'];
    $min_order = $_POST['min_order'];
    $max_order = $_POST['max_order'];
    $allergy = $_POST['allergy'];

    // Validate form data
    if (empty($product_name)) $errors[] = "Product name is required.";
    if (empty($product_desc)) $errors[] = "Product description is required.";
    if (empty($price)) $errors[] = "Price is required.";
    if (empty($avalable)) $errors[] = "Available stock is required.";
    if (empty($min_order)) $errors[] = "Minimum order is required.";
    if (empty($max_order)) $errors[] = "Maximum order is required.";
    if (empty($allergy)) $errors[] = "Allergy information is required.";

    // File upload handling
    if ($_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
        $errors[] = "Image file is required.";
    } else {
        $targetDirectory = "../images/";
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $errors[] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            $errors[] = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        $fileSizeKB = $_FILES["image"]["size"] / 1024; // Size in KB
        if ($fileSizeKB < 20) {
            $errors[] = "Sorry, your file is too small. Minimum size allowed is 20KB.";
            $uploadOk = 0;
        } elseif ($fileSizeKB > 3072) { // 3MB
            $errors[] = "Sorry, your file is too large. Maximum size allowed is 3MB.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $errors[] = "Sorry, your file was not uploaded.";
        } else {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Insert data into the products table if no errors
    if (empty($errors)) {
        // Get trader ID from session
        $trader_id = $_SESSION['trader_id'];

        $query = "INSERT INTO products (PRODUCT_NAME, PRODUCT_DESC, PRICE, IMAGE, STOCK_AVAILABLE, MIN_ORDER, MAX_ORDER, ALLERGY_INFO, TRADER_ID)
                  VALUES (:product_name, :product_desc, :price, :image, :avalable, :min_order, :max_order, :allergy, :trader_id)";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':product_name', $product_name);
        oci_bind_by_name($stmt, ':product_desc', $product_desc);
        oci_bind_by_name($stmt, ':price', $price);
        oci_bind_by_name($stmt, ':image', $_FILES["image"]["name"]); // Use the uploaded image file name
        oci_bind_by_name($stmt, ':avalable', $avalable);
        oci_bind_by_name($stmt, ':min_order', $min_order);
        oci_bind_by_name($stmt, ':max_order', $max_order);
        oci_bind_by_name($stmt, ':allergy', $allergy);
        oci_bind_by_name($stmt, ':trader_id', $trader_id);

        // Execute the statement
        $result = oci_execute($stmt);

        if($result) {
            // Insertion successful
            echo '<script>alert("Data uploaded.");</script>';
            echo '<script>window.location.href = "add_product.php";</script>';
        } else {
            // Insertion failed
            $error = oci_error($stmt);
            $errors[] = "Error inserting data: " . $error['message'];
        }

        // Clean up statement
        oci_free_statement($stmt);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
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
                <div class="data-table-section table-responsive">
                    <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal"
                            data-bs-target="#team-s">
                        <i class="bi bi-plus-square"></i> Add
                    </button>
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Available</th>
                                <th>Min Order</th>
                                <th>Max Order</th>
                                <th>Allergy Desc</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="w-100">
                            <?php
                            include('inc/connection.php');
                            if (!isset($_SESSION['trader_id'])) {
                                die("Trader ID not set in session. Please log in.");
                            }

                            $trader_id = $_SESSION['trader_id'];

                            $query = "SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRODUCT_DESC, PRICE, IMAGE, STOCK_AVAILABLE, MIN_ORDER, MAX_ORDER, ALLERGY_INFO 
                                      FROM products 
                                      WHERE TRADER_ID = :trader_id";
                            $stmt = oci_parse($conn, $query);
                            oci_bind_by_name($stmt, ':trader_id', $trader_id);
                            oci_execute($stmt);

                            while ($row = oci_fetch_assoc($stmt)) {
                                $image_name = $row['IMAGE'];
                                $image_path = "../images/" . $image_name;

                                echo '
                                <tr>
                                    <td class="text-wrap">' . $row['PRODUCT_NAME'] . '</td>
                                    <td class="text-wrap">' . $row['PRODUCT_DESC'] . '</td>
                                    <td class="text-wrap">$' . $row['PRICE'] . '</td>
                                    <td><img src="' . $image_path . '" class="img-fluid w-100" alt=""></td>
                                    <td class="text-wrap">' . $row['STOCK_AVAILABLE'] . '</td>
                                    <td class="text-wrap">' . $row['MIN_ORDER'] . '</td>
                                    <td class="text-wrap">' . $row['MAX_ORDER'] . '</td>
                                    <td class="text-wrap">' . $row['ALLERGY_INFO'] . '</td>
                                    <td>
                                        <a href="edit_product.php?id=' . $row['ADD_PRODUCT_ID'] . '">
                                            <button type="button" class="btn btn-dark shadow-none btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                        </a>
                                        <a href="delete_product.php?id=' . $row['ADD_PRODUCT_ID'] . '" class="btn btn-danger btn-sm shadow-none" onclick="return confirm(\'Are you sure you want to delete this product?\');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>';
                            }

                            oci_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal add product -->
    <div class="modal fade" id="team-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add your Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form method="post" id="form-data" autocomplete="off" enctype="multipart/form-data">
                        <div class="">
                            <div class="">
                                <label for="">Product Name</label>
                                <input name="product_name" type="text" class="form-control shadow-none text-center" placeholder="Product Name" value="<?php echo isset($product_name) ? $product_name : ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label for="">Product Description</label>
                                <input name="product_desc" type="text" class="form-control shadow-none text-center" placeholder="product description" value="<?php echo isset($product_desc) ? $product_desc : ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label for="">Price</label>
                                <input name="price" type="number" class="form-control shadow-none text-center" placeholder="price" value="<?php echo isset($price) ? $price : ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label for="">Image</label>
                                <input accept="image/png, image/jpeg" name="image" type="file" class="form-control shadow-none text-center" required>
                            </div>
                            <div class="mb-2">
                                <label for="">Available</label>
                                <input name="avalable" type="number" class="form-control shadow-none text-center" placeholder="available" value="<?php echo isset($avalable) ? $avalable : ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label for="">Min Order</label>
                                <input name="min_order" type="number" class="form-control shadow-none text-center" placeholder="min order" value="<?php echo isset($min_order) ? $min_order : ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label for="">Max Order</label>
                                <input name="max_order" type="number" class="form-control shadow-none text-center" placeholder="max order" value="<?php echo isset($max_order) ? $max_order : ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label for="">Allergy Description</label>
                                <input name="allergy" type="text" class="form-control shadow-none text-center" placeholder="allergy description" value="<?php echo isset($allergy) ? $allergy : ''; ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- add product modal end -->

    <?php include('inc/footer.php'); ?>
</body>
</html>
