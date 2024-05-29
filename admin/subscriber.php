<?php session_start(); ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Subscriber</title>
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
                                <th>Id</th>
                                <th>Email address</th>
                                <th>Date and Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('inc/connection.php');
                            $query = "SELECT * FROM newsletter";
                            $data = oci_parse($conn, $query);
                            oci_execute($data);

                            $row_count = 0; // Initialize a row count variable

                            while ($result = oci_fetch_assoc($data)) {
                                echo "<tr>";
                                echo "<td>" . $result['NEWSLETTER_ID'] . "</td>";
                                echo "<td>" . $result['EMAIL'] . "</td>";
                                echo "<td>" . $result['NEWSLETTER_DATE'] . "</td>";
                                echo "<td><button type='button' class='btn btn-info btn-sm'><i class='bi bi-reply'></i> Reply</button></td>";
                                echo "</tr>";
                                $row_count++; // Increment row count for each row fetched
                            }

                            if ($row_count === 0) {
                                echo "<tr><td colspan='4'>No data found</td></tr>"; // Display "No data found" in a single row if no rows fetched
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
