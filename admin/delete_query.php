<?php
session_start();
require('inc/connection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the query to delete the entry with the given ID
    $query = "DELETE FROM customer_query WHERE ID = :id";
    $stmt = oci_parse($conn, $query);

    // Bind the ID parameter
    oci_bind_by_name($stmt, ':id', $id);

    // Execute the query
    if (oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)) {
        echo "Success";
    } else {
        echo "Error";
    }

    // Free the statement handle and close the connection
    oci_free_statement($stmt);
    oci_close($conn);

    // Redirect back to the main page
    header('Location: customer_query.php');
    exit;
}
?>
