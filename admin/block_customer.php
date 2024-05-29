<?php
require('inc/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Update the status to 'inactive' for the given email
    $query = "UPDATE customer SET STATUS = 'inactive' WHERE EMAIL = :email";
    $stmt = oci_parse($conn, $query);

    oci_bind_by_name($stmt, ':email', $email);

    if (oci_execute($stmt)) {
        echo "Status updated to inactive";
    } else {
        $e = oci_error($stmt);
        echo "Error updating status: " . htmlentities($e['message']);
    }

    // Free the statement handle and close connection
    oci_free_statement($stmt);
    oci_close($conn);
}
?>
