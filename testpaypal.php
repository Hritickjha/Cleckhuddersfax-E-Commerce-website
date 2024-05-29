<?php
$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; 
$paypalID = 'sb-s43oyo30555884@business.example.com'; //Business Email

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>PayPal Standard Payment Gateway Integration by CodexWorld</title>
</head>
<body>
<br/>Name: Movie Kabaali
<br/>Price: 101
<form action="<?php echo $paypalURL; ?>" method="post">

    <input type="hidden" name="business" value="<?php echo $paypalID;?>">

    <!-- Specify a Buy Now button. -->
    <input type="hidden" name="cmd" value="_xclick">
     <input type="hidden" name="item_name" value="Kabali movie ticket">
    <input type="hidden" name="item_number" value="2">
    <input type="hidden" name="amount" value="100">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="quantity" value="2">

    <!-- Specify URLs -->
    <input type='hidden' name='cancel_return' value='http://localhost/paypal_jatin/cancel.php'>
    <input type='hidden' name='return' value='http://localhost/paypal_jatin/success.php'>
    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
    <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
</form>
 </body>
 </html>