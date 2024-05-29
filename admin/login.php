<?php
session_start();
include('inc/connection.php');

// Include the function to hash passwords
function hash_password($password) {
    // Connect to the database
    global $conn;
    $hashed_password = ''; // Initialize the variable
    
    // Prepare the query to hash the password
    $query = "BEGIN :hashed_password := hash_password(:password); END;";
    $stmt = oci_parse($conn, $query);
    
    // Bind the parameters
    oci_bind_by_name($stmt, ':password', $password);
    oci_bind_by_name($stmt, ':hashed_password', $hashed_password, 2000);
    
    // Execute the query
    oci_execute($stmt);
    
    // Free the statement handle
    oci_free_statement($stmt);
    
    // Return the hashed password
    return $hashed_password;
}



if(isset($_POST['login'])) {
    $admin_name = $_POST['admin_name'];
    $admin_pass = $_POST['admin_pass'];

    // Hash the provided password
    $hashed_password = hash_password($admin_pass);

    // Prepare the query using OCI parsing
    $query = "SELECT * FROM admin WHERE admin_name=:admin_name";
    $stmt = oci_parse($conn, $query);

    // Bind the parameters
    oci_bind_by_name($stmt, ':admin_name', $admin_name);

    // Execute the query
    oci_execute($stmt);

    // Fetch the result
    $row = oci_fetch_assoc($stmt);

    // Free the statement handle
    oci_free_statement($stmt);

    // If a row is returned and the hashed passwords match
    if($row && $hashed_password === $row['ADMIN_PASSWORD']){
        $_SESSION['admin_name'] = $admin_name;
        header('location: dashboard.php');
        exit; // Stop further execution
    } else {
        $error = "Login failed";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <?php include('inc/link.php'); ?>
    <link rel="stylesheet" href="css/common.css">
    <style>
        div.login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="post" id="form-data" autocomplete="off">
            <h4 class="bg-dark text-white py-3">ADMIN LOGIN PANEL</h4>
            <div class="p-4">
                <?php if(isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <input name="admin_name" type="text" class="form-control shadow-none text-center"
                        placeholder="Admin Name" required>
                </div>
                <div class="mb-4">
                    <input name="admin_pass" type="password" class="form-control shadow-none text-center"
                        placeholder="Admin Password" required>
                </div>
                <div class="mb-4"></div>
                <button name="login" type="submit" class="btn text-white custom-bg shadow-none">LOGIN</button>
            </div>
        </form>
    </div>
    <?php include('inc/footer.php'); ?>
</body>
</html>