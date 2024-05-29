<?php 
session_start();
require('inc/connection.php');

// Fetch data from Oracle Apex table `userreview` and `products` and order by REVIEW_DATE in descending order
$query = "SELECT ur.REVIEW_ID, ur.CUSTOMER_ID, ur.ADD_PRODUCT_ID, ur.NAME, ur.EMAIL, ur.REVIEW, ur.RATING, ur.REVIEW_DATE, ur.STATUS, p.PRODUCT_NAME 
          FROM userreview ur
          JOIN products p ON ur.ADD_PRODUCT_ID = p.ADD_PRODUCT_ID
          ORDER BY ur.REVIEW_DATE DESC";
$stmt = oci_parse($conn, $query);

// Check if the statement parsed successfully
if (!$stmt) {
    $e = oci_error($conn);
    echo "Error parsing the statement: " . htmlentities($e['message']);
    exit;
}

// Execute the statement
$exec = oci_execute($stmt);

// Check if the statement executed successfully
if (!$exec) {
    $e = oci_error($stmt);
    echo "Error executing the statement: " . htmlentities($e['message']);
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Review</title>
    <?php include('inc/link.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-status').click(function() {
                var reviewId = $(this).data('id');
                var currentStatus = $(this).data('status');
                var button = $(this);
                $.ajax({
                    url: 'update_status.php',
                    type: 'POST',
                    data: { id: reviewId, status: currentStatus },
                    success: function(response) {
                        alert(response);
                        if (currentStatus === 'active') {
                            button.removeClass('btn-primary').addClass('btn-secondary').text('Inactive').data('status', 'inactive');
                        } else {
                            button.removeClass('btn-secondary').addClass('btn-primary').text('Active').data('status', 'active');
                        }
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
                                <th>Review ID</th>
                                <th>Name</th>
                                <th>Product Name</th>
                                <th>Review</th>
                                <th>Rating</th>
                                <th>Review Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = oci_fetch_assoc($stmt)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['REVIEW_ID']); ?></td>
                                <td><?php echo htmlspecialchars($row['NAME']); ?></td>
                                <td><?php echo htmlspecialchars($row['PRODUCT_NAME']); ?></td>
                                <td class="text-wrap"><?php echo htmlspecialchars($row['REVIEW']); ?></td>
                                <td><?php echo htmlspecialchars($row['RATING']); ?></td>
                                <td><?php echo htmlspecialchars($row['REVIEW_DATE']); ?></td>
                                <td><?php echo htmlspecialchars($row['STATUS']); ?></td>
                                <td>
                                    <?php if ($row['STATUS'] == 'active') : ?>
                                        <button type="button" class="btn btn-primary shadow-none btn-sm btn-status" data-id="<?php echo $row['REVIEW_ID']; ?>" data-status="active">
                                            <i class="bi bi-check-circle"></i> Active
                                        </button>
                                    <?php else : ?>
                                        <button type="button" class="btn btn-secondary shadow-none btn-sm btn-status" data-id="<?php echo $row['REVIEW_ID']; ?>" data-status="inactive">
                                            <i class="bi bi-x-circle"></i> Inactive
                                        </button>
                                    <?php endif; ?>
                                    <a href="delete_review.php?id=<?php echo $row['REVIEW_ID']; ?>" onclick="return confirm('Are you sure you want to delete this entry?');">
                                        <button type="button" class="btn btn-danger shadow-none btn-sm">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>
                                    </a>
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
