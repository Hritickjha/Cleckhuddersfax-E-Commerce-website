<?php
session_start();
include('inc/connection.php');
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare the delete query
    $delete_query = "DELETE FROM slider WHERE ID = :id";
    $delete_stmt = oci_parse($conn, $delete_query);
    oci_bind_by_name($delete_stmt, ':id', $product_id);

    // Execute the delete query
    if (oci_execute($delete_stmt)) {
        // Redirect back to the page displaying products
        echo '<script>alert("Data success fully delete.");</script>';
        echo '<script>window.location.href = "page_adjustment.php";</script>';
        exit;
    } else {
        echo "Failed to delete product.";
    }
} else {
    echo "Invalid request.";
}

// Close database connection
oci_close($conn);
?>
