<?php
session_start();
include('inc/connection.php');

// Ensure the trader ID is set in the session
if (!isset($_SESSION['trader_id'])) {
    die("Trader ID not set in session. Please log in.");
}

// Fetch the trader ID from the session
$trader_id = $_SESSION['trader_id'];

// Check if the product ID is provided
if (!isset($_GET['id'])) {
    die("Product ID not provided.");
}

$product_id = $_GET['id'];

// Prepare the SQL query to delete the product
$query = "DELETE FROM products WHERE ADD_PRODUCT_ID = :product_id AND TRADER_ID = :trader_id";
$stmt = oci_parse($conn, $query);
oci_bind_by_name($stmt, ':product_id', $product_id);
oci_bind_by_name($stmt, ':trader_id', $trader_id);

// Execute the statement
$result = oci_execute($stmt);

if ($result) {
    // Deletion successful
    echo '<script>alert("Product deleted successfully.");</script>';
    echo '<script>window.location.href = "add_product.php";</script>';
} else {
    // Deletion failed
    $error = oci_error($stmt);
    echo "Error deleting product: " . $error['message'];
}

// Clean up statement
oci_free_statement($stmt);

// Close database connection
oci_close($conn);
?>
