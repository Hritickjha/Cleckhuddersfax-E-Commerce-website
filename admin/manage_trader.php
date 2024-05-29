<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Trader</title>
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
                                <th>Trader Name</th>
                                <th>Email</th>
                                <th>Phone number</th>
                                <th>City</th>
                                <th>Position</th>
                                <th>Business Desc</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('inc/connection.php');
                            $query = "SELECT * FROM trader";
                            $data = oci_parse($conn, $query);
                            oci_execute($data);

                            $row_count = 0; // Initialize a row count variable

                            while ($result = oci_fetch_assoc($data)) {
                                echo '
                                    <tr>
                                    <td>' . htmlspecialchars($result['FULL_NAME']) . '</td>
                                    <td>' . htmlspecialchars($result['EMAIL']) . '</td>
                                    <td>' . htmlspecialchars($result['PHONE_NUM']) . '</td>
                                    <td>' . htmlspecialchars($result['CITY']) . '</td>
                                    <td>' . htmlspecialchars($result['POSITION']) . '</td>
                                    <td>' . htmlspecialchars($result['BUSINESS_DESP']) . '</td>
                                    <td>
                                    <a href="view_product.php?id=' . htmlspecialchars($result['TRADER_ID']) . '">
                                        <button type="button" class="btn btn-info shadow-none btn-sm me-2">
                                            <i class="bi bi-eye-fill"></i> View
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-success shadow-none btn-sm">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </button>
                                    <button type="button" class="btn btn-primary shadow-none btn-sm ms-2">
                                        <i class="bi bi-bookmark-check-fill"></i> Active
                                    </button>
                                    </td>
                                    </tr>
                                ';
                                $row_count++; // Increment row count for each row fetched
                            }

                            if ($row_count === 0) {
                                echo "<tr><td colspan='7'>No data found</td></tr>"; // Display "No data found" in a single row if no rows fetched
                            }
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
