<?php
session_start();
require('inc/connection.php');

// Check if the ID is set in the URL parameter
if(isset($_GET['id'])) {
    $customer_id = $_GET['id'];
    
    // Update the status of the customer to "Inactive"
    $query = "UPDATE customer SET STATUS = 'Inactive' WHERE ID = :id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':id', $customer_id);
    oci_execute($stmt);
    
    // Free the statement handle
    oci_free_statement($stmt);
    oci_close($conn);
    
    // Redirect back to the customer page
    header('Location: customer.php');
    exit;
} else {
    // If ID is not set, redirect back to the customer page
    header('Location: customer.php');
    exit;
}
?>
