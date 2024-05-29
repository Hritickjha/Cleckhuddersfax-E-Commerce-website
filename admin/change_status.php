<?php
session_start();
require('inc/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if customerId and newStatus are set
    if (isset($_POST['customerId']) && isset($_POST['newStatus'])) {
        $customerId = $_POST['customerId'];
        $newStatus = $_POST['newStatus'];

        // Update status in the database
        $updateQuery = "UPDATE customer SET STATUS = :newStatus WHERE CUSTOMER_ID = :customerId";
        $stmt = oci_parse($conn, $updateQuery);
        oci_bind_by_name($stmt, ':newStatus', $newStatus);
        oci_bind_by_name($stmt, ':customerId', $customerId);
        
        if (oci_execute($stmt)) {
            echo "Status updated successfully";
        } else {
            echo "Error updating status";
        }
        
        oci_free_statement($stmt);
        oci_close($conn);
    } else {
        echo "Invalid request";
    }
} else {
    echo "Method not allowed";
}
?>
