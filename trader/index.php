<?php
session_start();
include('inc/connection.php');

if (isset($_POST['login'])) {
    // Retrieve form data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if email and password are provided
    if (!empty($email) && !empty($password)) {
        // Prepare the query using OCI parsing
        $query = "SELECT * FROM trader WHERE email=:email";
        $stmt = oci_parse($conn, $query);

        // Bind the parameters
        oci_bind_by_name($stmt, ':email', $email);

        // Execute the query
        oci_execute($stmt);

        // Fetch the row
        $row = oci_fetch_assoc($stmt);

        // Verify password
        if ($row && password_verify($password, $row['TRADER_PASSWORD'])) {
            $_SESSION['fullname_name'] = $row['FULL_NAME'];
            $_SESSION['trader_id'] = $row['TRADER_ID']; // Store trader ID in session
            $_SESSION['position'] = $row['POSITION'];
            $_SESSION['phone'] = $row['PHONE_NUM'];
            $_SESSION['street'] = $row['STREET'];
            $_SESSION['city'] = $row['CITY'];
            $_SESSION['state'] = $row['STATE'];
            $_SESSION['email'] = $row['EMAIL'];
            header('location: trader_dashboard.php');
            exit; // Stop further execution
        } else {
            $error = "Login failed";
        }
    } else {
        $error = "Please provide both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Login</title>
    <?php 
        include('inc/link.php');
    ?>
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
            <h4 class="bg-dark text-white py-3">Trader Login Panel</h4>
            <div class="p-4">
                <?php if(isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <input name="email" type="email" class="form-control shadow-none text-center"
                        placeholder="Email" required>
                </div>
                <div class="mb-4">
                    <input name="password" type="password" class="form-control shadow-none text-center"
                        placeholder="Password" required>
                </div>
            </div>
            <div class="mb-4">
                <button name="login" type="submit" class="btn btn-primary shadow-none mx-3">LOGIN</button>
                <a href="register.php" class="btn btn-success shadow-none">Register</a>
            </div>
        </form>
    </div>
    <?php
        include('inc/footer.php');
    ?>
</body>
</html>
