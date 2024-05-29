<?php session_start(); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Gallery</title>
  <?php include('inc/link.php'); ?>
</head>

<body>
  <div class="main-wrapper">
    <div class="header-container fixed-top">

      <!-- top header start -->
      <?php include('inc/header.php'); ?>

      <!-- top header end -->

    </div>

    <!-- side bar start -->
    <?php include('inc/sidebar.php'); ?>
    <!-- side bar end -->
    <div class="content-wrapper">

      <div class="container">
        <!-- Gallery -->
        <div class="row">
        <?php
include('inc/connection.php');

// Modify query to include trader_id condition
$query = "SELECT PRODUCT_NAME, PRODUCT_DESC, PRICE, IMAGE, DATE_ADDED 
          FROM products ";
$stmt = oci_parse($conn, $query);

oci_execute($stmt);

// Display data using a while loop
while ($row = oci_fetch_assoc($stmt)) {
    $image_name = $row['IMAGE'];
    $image_path = "../images/" . $image_name; // Assuming images are stored in 'images' folder

    echo '    
        <div class="col-lg-4 col-md-6 text-light mt-3">
            <div class="card text-bg-dark">
                <img src="' . $image_path . '" class="card-img w-100 img-fluid" alt="...">
                <div class="card-img-overlay">
                    <h5 class="card-title text-info fw-bold">' . $row['PRODUCT_NAME'] . '</h5>
                    <p class="card-text text-secondary-emphasis">' . $row['PRODUCT_DESC'] . '</p>
                    <p class="card-text text-danger fs-4 fw-bold"><small>' . $row['DATE_ADDED'] . '</small></p>
                </div>
            </div>
        </div>
    ';
}

// Close database connection
oci_close($conn);
?>

        
</div>
        </div>
        <!-- Gallery -->

      </div>
    </div>
    <?php include('inc/footer.php'); ?>
</body>

</html>