<?php 
session_start();
require('inc/connection.php');

// Fetch data from Oracle Apex table `customer_query` and order by CURRENT_TIME in descending order
$query = "SELECT ID, NAME, EMAIL, SUBJECT, MESSAGE, CURRENT_TIME FROM customer_query ORDER BY CURRENT_TIME DESC";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Query</title>
    <?php include('inc/link.php'); ?>
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
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Start date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = oci_fetch_assoc($stmt)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['NAME']); ?></td>
                                <td><?php echo htmlspecialchars($row['EMAIL']); ?></td>
                                <td><?php echo htmlspecialchars($row['SUBJECT']); ?></td>
                                <td class=" text-wrap"><?php echo htmlspecialchars($row['MESSAGE']); ?></td>
                                <td class=" text-wrap"><?php echo htmlspecialchars($row['CURRENT_TIME']); ?></td>
                                <td>
                                    <a href="delete_query.php?id=<?php echo $row['ID']; ?>" onclick="return confirm('Are you sure you want to delete this entry?');">
                                        <button type="button" class="btn btn-danger shadow-none btn-sm">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-success shadow-none btn-sm">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </button>
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
