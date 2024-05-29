
<?php session_start(); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin dashboard</title>
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

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure trader_id is set in the session
if (!isset($_SESSION['trader_id'])) {
    die('Trader ID not set in session.');
}

$trader_id = $_SESSION['trader_id'];

// Modify query to include trader_id condition
$query = "SELECT PRODUCT_NAME, PRODUCT_DESC, PRICE, IMAGE, STOCK_AVAILABLE
          FROM products 
          WHERE TRADER_ID = :trader_id";
$stmt = oci_parse($conn, $query);

// Bind the trader_id parameter to the query
oci_bind_by_name($stmt, ':trader_id', $trader_id);

oci_execute($stmt);

// Display data using a while loop
while ($row = oci_fetch_assoc($stmt)) {
    $image_name = $row['IMAGE'];
    $image_path = "../images/" . $image_name; // Assuming images are stored in 'images' folder

    echo '    
        
        <div class="col-lg-4 col-md-6">
<div class="card" style="width: 18rem;">
  <img src="' . $image_path . '" class="card-img-top w-100" alt="...">
  <div class="card-body">
    <h5 class="card-title">' . $row['PRODUCT_NAME'] . '</h5>
    <p class="card-text">' . $row['PRODUCT_DESC'] . '</p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">Price:-  $'.$row['PRICE'].'</li>
    <li class="list-group-item">Stock Available:-  '.$row['STOCK_AVAILABLE'].'</li>
  </ul>
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