<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <?php include('inc/link.php') ?>
    <style>
        
    </style>
</head>
<body>
    <?php include('inc/header.php') ?>
    
    <?php
    include('inc/connection.php');

    // Check if the user_id session is set
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $query = "
            SELECT o.ORDER_ID, o.PRODUCT_NAME, o.QUANTITY_ORDERED, o.PRICE, o.STATUS, o.ORDER_DATE
            FROM orderdetail o
            WHERE o.CUSTOMER_ID = :user_id
            ORDER BY o.ORDER_DATE DESC
        ";

        $data = oci_parse($conn, $query);
        oci_bind_by_name($data, ':user_id', $user_id);
        oci_execute($data);

        // Initialize total sale variable
        $total_sale = 0;

        echo '<section class="h-100 h-custom" style="background-color: #eee;">
                <div class="container py-5 h-100" id="content">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-lg-8 col-xl-6">
                            <div class="card border-top border-bottom border-3" style="border-color: #f37a27 !important;">
                                <div class="card-body p-5">
                                    <p class="lead fw-bold mb-5" style="color: #f37a27;">Purchase Receipt</p>';

        while ($result = oci_fetch_assoc($data)) {
            // Determine button text based on STATUS value
            $button_text = ($result['STATUS'] == 1) ? 'Pending' : 'Delivered';

            echo '<div class="row">
                    <div class="col-12 mb-2 text-end mt-3"><button type="button" class="btn btn-sm text-warning text-bold">'.$button_text.'</button></div><br>
                    <div class="col mb-3">
                        <p class="small text-muted mb-1">Date</p>
                        <p>' . $result['ORDER_DATE'] . '</p>
                    </div>
                    <div class="col mb-3">
                        <p class="small text-muted mb-1">Order No.</p>
                        <p>' . $result['ORDER_ID'] . '</p>
                    </div>
                  </div>
                  <div class="mx-n5 px-5 py-4" style="background-color: #f2f2f2;">
                    <div class="row">
                        <div class="col-md-8 col-lg-9">
                            <h5>Product Name</h5><p>' . $result['PRODUCT_NAME'] . '</p>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <h6>Price</h6><p>$' . number_format($result['PRICE'], 2) . '</p>
                        </div>
                    </div>
                  </div>';

            // Increment total sale by the price of the product
            $total_sale += $result['PRICE'];
        }

        echo '<div class="row my-4">
                <div class="col-md-4 offset-md-8 col-lg-3 offset-lg-9">
                    <p class="lead fw-bold mb-0" style="color: #f37a27;">Total: $' . number_format($total_sale, 2) . '</p>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                    <div class="horizontal-timeline">
                        <button class="btn btn-lg btn-success" id="cmd">Download your receipt PDF</button>
                    </div>
                </div>
              </div>
              <p class="mt-4 pt-2 mb-0">Want any help? <a href="tel:+977-9819931015" class="Blondie" style="color: #f37a27;">Please contact us</a></p>
              </div>
              </div>
              </div>
              </div>
              </div>
              </section>';

        // Store total sale value in a session variable
        $_SESSION['total_sale'] = $total_sale;
    } else {
        echo "Your product is not ordered!";
    }

    oci_close($conn);
    ?>

    <div id="editor"></div>

    <?php include('inc/footer.php') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
    <script>
        var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function (element, renderer) {
                return true;
            }
        };

        document.getElementById('cmd').addEventListener('click', function () {
            doc.fromHTML(document.getElementById('content').innerHTML, 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });
            doc.save('receipt.pdf');
        });
    </script>
</body>
</html>
