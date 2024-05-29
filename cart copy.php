<?php
session_start();
include('inc/connection.php'); // Include your database connection file
$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypalID = 'sb-s43oyo30555884@business.example.com'; // Business Email

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the cart session array exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('No products in the cart.');window.location.href = 'product-list.php'</script>";
    exit();
}

// Calculate total quantity of items in the cart
$total_quantity = array_sum($_SESSION['cart']);
if ($total_quantity > 20) {
    echo "<script>alert('You can only buy a maximum of 20 products.');window.location.href = 'product-list.php'</script>";
    exit();
}

// Fetch product data from the database for the products in the cart
$product_ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));

$query = "SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRICE, IMAGE FROM products WHERE ADD_PRODUCT_ID IN ($product_ids)";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cart</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <meta content="Bootstrap Ecommerce" name="description">
    <?php include('inc/link.php'); ?>
    <style>
        @media screen and (min-width: 375px) and (max-width:665px) {
            .cart-page .table input {
                width: 33px;
            }
        }
    </style>
</head>
<body>
    <?php include('inc/header.php'); ?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-wrap">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="product-list.php">Products</a></li>
                <li class="breadcrumb-item active">Cart</li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Cart Start -->
    <div class="cart-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                <?php
                                $total_price = 0;
                                while ($row = oci_fetch_assoc($stmt)) {
                                    $image_path = "images/" . $row['IMAGE'];
                                    $product_id = $row['ADD_PRODUCT_ID'];
                                    $quantity = $_SESSION['cart'][$product_id];
                                    $product_total = $row['PRICE'] * $quantity;
                                    $total_price += $product_total;
                                    echo '
                                    <tr data-id="' . $product_id . '" data-price="' . $row['PRICE'] . '">
                                        <td><a href="#"><img src="' . $image_path . '" alt="Image"></a></td>
                                        <td><a href="product-detail.php?id=' . $product_id . '">' . $row['PRODUCT_NAME'] . '</a></td>
                                        <td>$<span class="unit-price">' . $row['PRICE'] . '</span></td>
                                        <td>
                                            <div class="qty">
                                                <button class="btn-minus"><i class="fa fa-minus"></i></button>
                                                <input type="text" value="' . $quantity . '" class="quantity">
                                                <button class="btn-plus"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </td>
                                        <td>$<span class="total-price">' . $product_total . '</span></td>
                                        <td><a href="remove-from-cart.php?id=' . $product_id . '"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    ';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="coupon">
                                <input type="text" placeholder="Coupon Code">
                                <button>Apply Code</button>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <h3 class="fw-bold fs-4">Collection Slot Available </h3>

                            <?php
$dates = array("", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
$today = date('l');
$time = date("H:i");

if ($today === 'Friday') {
    echo '
    <h4 class="text-warning">'.$dates[3].'</h4>
    <form action="" method="post">
        <div class="form-group checkbox-group">
            <label class="fw-bold fs-4"><input type="checkbox" name="position" value="Bakery"> 10:00 AM - 1:00 PM</label><br>
            <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
            <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
        </div>
    </form>';
} elseif ($today === 'Tuesday' && $time >= "10:00") {
    echo '
    <h4 class="text-warning">'.$dates[3].'</h4>';
    if ($time <= "13:00") {
        echo '
        <form action="" method="post">
            <div class="form-group checkbox-group">
                <label class="fw-bold fs-4"><input type="checkbox" name="position" value="Bakery"> 10:00 AM - 1:00 PM</label><br>
                <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
                <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
            </div>
        </form>';
    } elseif ($time > "13:00" && $time <= "17:00") {
        echo '
        <form action="" method="post">
            <div class="form-group checkbox-group">
                <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
            </div>
        </form>';
    } else {
        echo '<h4>No slot available</h4>';
    }
} elseif ($today === 'Wednesday' && $time >= "10:00") {
    echo '
    <h4 class="text-warning">'.$dates[4].'</h4>';
    if ($time <= "13:00") {
        echo '
        <form action="" method="post">
            <div class="form-group checkbox-group">
                <label class="fw-bold fs-4"><input type="checkbox" name="position" value="Bakery"> 10:00 AM - 1:00 PM</label><br>
                <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
                <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
            </div>
        </form>';
    } elseif ($time > "13:00" && $time <= "17:00") {
        echo '
        <form action="" method="post">
            <div class="form-group checkbox-group">
                <label><input type="checkbox" name="position" value="Fishmonger"> 1:00 PM - 4:00 PM</label><br>
            </div>
        </form>';
    } else {
        echo '<h4>No slot available</h4>';
    }
} else {
    echo '<h4>No slot available</h4>';
}
?>


                              
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="cart-summary">
                        <div class="cart-content">
                            <h3>Cart Summary</h3>
                            <p>Sub Total<span id="subtotal">$<?php echo $total_price; ?></span></p>
                            <p>Discount <span id="discount">10%</span></p>
                            <?php
                            $discount_amount = $total_price * 0.10;
                            $total_after_discount = $total_price - $discount_amount;
                            ?>
                            <p>Total After Discount<span id="total_after_discount">$<?php echo number_format($total_after_discount, 2); ?></span></p>
                            <h4>Grand Total<span id="grandtotal">$<?php echo number_format($total_after_discount, 2); ?></span></h4>
                        </div>
                        <div class="cart-btn">
                            

                            <form action="<?php echo $paypalURL; ?>" method="post">
                                <!-- PayPal Variables -->
                                <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
                                <input type="hidden" name="cmd" value="_cart">
                                <input type="hidden" name="upload" value="1">
                                <input type="hidden" name="currency_code" value="USD">
                                <input type="hidden" name="return" value="http://localhost/group-project/user_order_details.php">
                                <input type="hidden" name="cancel_return" value="http://localhost/group-project/cart.php">

                                <?php
                                $i = 1;
                                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                                    $query = "SELECT PRODUCT_NAME, PRICE FROM products WHERE ADD_PRODUCT_ID = :product_id";
                                    $stmt = oci_parse($conn, $query);
                                    oci_bind_by_name($stmt, ':product_id', $product_id);
                                    oci_execute($stmt);
                                    $row = oci_fetch_assoc($stmt);

                                    echo '<input type="hidden" name="item_name_' . $i . '" value="' . $row['PRODUCT_NAME'] . '">';
                                    echo '<input type="hidden" name="amount_' . $i . '" value="' . $total_after_discount . '">';
                                    echo '<input type="hidden" name="quantity_' . $i . '" value="' . $quantity . '">';
                                    $i++;
                                }
                                ?>
                                <button type="submit" name="paynow" alt="PayPal - The safer, easier way to pay online">Pay Now</button>
                            </form>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
    <?php include('inc/footer.php'); ?>
    <script>
       document.addEventListener('DOMContentLoaded', function() {
    const updateTotals = () => {
        let subtotal = 0;
        let totalQuantity = 0;
        const maxOrder = 20;

        document.querySelectorAll('tbody tr').forEach(row => {
            const quantityInput = row.querySelector('.quantity');
            let quantity = parseInt(quantityInput.value);
            const price = parseFloat(row.getAttribute('data-price'));

            totalQuantity += quantity;
            if (totalQuantity > maxOrder) {
                alert("You can only order a maximum of 20 products.");
                quantityInput.value = maxOrder - (totalQuantity - quantity);
                quantity = parseInt(quantityInput.value);
                totalQuantity = maxOrder;
            }

            const total = quantity * price;
            row.querySelector('.total-price').textContent = total.toFixed(2);
            subtotal += total;
        });

        const discountPercentage = 0.10;
        const discountAmount = subtotal * discountPercentage;
        const totalAfterDiscount = subtotal - discountAmount;
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('discount').textContent = (discountPercentage * 100) + '%';
        document.getElementById('total_after_discount').textContent = '$' + totalAfterDiscount.toFixed(2);
        document.getElementById('grandtotal').textContent = '$' + totalAfterDiscount.toFixed(2);

        // Update the cart quantity display
        document.getElementById('cart-quantity').textContent = totalQuantity;
    };

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.nextElementSibling;
            let quantity = parseInt(input.value);
            if (quantity > 1) {
                input.value = quantity --;
                updateTotals();
            }
        });
    });

    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            let quantity = parseInt(input.value);
            if (quantity < 20) {
                input.value = +quantity;
                updateTotals();
            } else {
                alert("You can only order a maximum of 20.");
            }
        });
    });

    document.querySelectorAll('.quantity').forEach(input => {
        input.addEventListener('change', function() {
            let quantity = parseInt(this.value);
            if (quantity < 1) {
                this.value = 1;
            } else if (quantity > 20) {
                this.value = 20;
                alert("You can only order a maximum of 20.");
            }
            updateTotals();
        });
    });

    updateTotals();
});

    </script>
</body>
</html>
<?php
// Close database connection
oci_close($conn);
?>
