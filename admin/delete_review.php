<?php
session_start();
require('inc/connection.php');

if (isset($_GET['id'])) {
    $review_id = $_GET['id'];
    
    // Delete the review with the specified ID
    $query = "DELETE FROM userreview WHERE REVIEW_ID = :id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':id', $review_id);
    
    if (oci_execute($stmt)) {
        // Deletion successful, redirect back to the review page
        header("Location: product_review.php");
        exit;
    } else {
        // Deletion failed, show an error message
        echo "Error deleting review.";
    }

    // Free the statement handle and close connection
    oci_free_statement($stmt);
    oci_close($conn);
} else {
    // No ID specified, redirect back to the review page
    header("Location: product_review.php");
    exit;
}
?>
