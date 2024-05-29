<?php
$conn = oci_connect('root1', 'root1', '//localhost/xe');
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
} else {
   // echo "Connected to Oracle!"; 
}

// Return the connection object
return $conn;
?>