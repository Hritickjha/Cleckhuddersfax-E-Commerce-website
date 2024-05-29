<?php
require('inc/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewId = $_POST['id'];
    $currentStatus = $_POST['status'];

    // Toggle the status based on the current status
    $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';

    // Update the status for the given review ID
    $query = "UPDATE userreview SET STATUS = :new_status WHERE REVIEW_ID = :review_id";
    $stmt = oci_parse($conn, $query);

    oci_bind_by_name($stmt, ':new_status', $newStatus);
    oci_bind_by_name($stmt, ':review_id', $reviewId);

    if (oci_execute($stmt)) {
        echo "Status updated to " . htmlentities($newStatus);
    } else {
        $e = oci_error($stmt);
        echo "Error updating status: " . htmlentities($e['message']);
    }

    // Free the statement handle and close connection
    oci_free_statement($stmt);
    oci_close($conn);
}
?>
