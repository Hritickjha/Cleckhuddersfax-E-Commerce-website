<!DOCTYPE html>
<html>
<head>
    <title>Product Page</title>
</head>
<body>
    <h2>Product Name: Your Product</h2>
    <p>Description: Description of your product.</p>
    <p>Price: $100</p>
    <form action="process_payment.php" method="post">
        <input type="hidden" name="product_name" value="Your Product">
        <input type="hidden" name="product_description" value="Description of your product.">
        <input type="hidden" name="amount" value="100">
        <input type="submit" value="Buy Now">
    </form>
</body>
</html>
