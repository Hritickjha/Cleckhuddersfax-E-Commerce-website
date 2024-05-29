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
<?php 
session_start();
require('inc/connection.php');

// Fetch data from customer table                        
$query = "SELECT FIRSTNAME, EMAIL, PHONE_NUMBER, ADDRESS, STATUS, REGISTER_DATE FROM customer";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer</title>
    <?php include('inc/link.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-block').click(function() {
                var email = $(this).data('email');
                var button = $(this);
                $.ajax({
                    url: 'block_customer.php',
                    type: 'POST',
                    data: { email: email },
                    success: function(response) {
                        alert(response);
                        button.closest('tr').find('.status-column').text('inactive');
                    },
                    error: function() {
                        alert('Error updating status');
                    }
                });
            });
        });
    </script>
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
                <div class="data-table-section table-responsive">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone number</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Register Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = oci_fetch_assoc($stmt)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['FIRSTNAME']); ?></td>
                                <td><?php echo htmlspecialchars($row['EMAIL']); ?></td>
                                <td><?php echo htmlspecialchars($row['PHONE_NUMBER']); ?></td>
                                <td><?php echo htmlspecialchars($row['ADDRESS']); ?></td>
                                <td class="status-column"><?php echo htmlspecialchars($row['STATUS']); ?></td>
                                <td><?php echo htmlspecialchars($row['REGISTER_DATE']); ?></td>
                                <td>
                                    <?php 
                                    if($row['STATUS'] == 'active'){
                                        echo ' 
                                        <button type="button" class="btn btn-success shadow-none btn-sm btn-status">
                                        <i class="bi bi-bookmark-check-fill"></i> Active
                                        </button>
                                        <button type="button" class="btn btn-dark shadow-none btn-sm btn-block" data-email="'.$row['EMAIL'].'">
                                        <i class="bi bi-bookmark-check-fill"></i> Block
                                    </button>
                                        ';
                                    } else {
                                        echo '
                                        <button type="button" class="btn btn-danger shadow-none btn-sm btn-status">
                                        <i class="bi bi-bookmark-check-fill"></i> Inactive
                                        </button>
                                        ';
                                    }
                                    ?>
                                    
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php 
                            // Free the statement handle and close connection after the loop
                            oci_free_statement($stmt);
                            oci_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include('inc/footer.php'); ?>
</body>

</html>
