<?php
// Establish connection to the database
include('inc/connection.php');

// Initialize error messages
$error_pass = $email_error = $registration_error = '';
$fullname_error = $phone_error = $street_error = $city_error = $state_error = $zipcode_error = $position_error = $businessdesc_error = $email_error = $password_error = $confirmpassword_error = '';

// Initialize form fields
$fullname = $phone = $street = $city = $state = $zipcode = $position = $businessdesc = $email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zipcode = trim($_POST['zipcode']);
    $position = isset($_POST['position']) ? $_POST['position'] : ''; // Get selected position
    $businessdesc = trim($_POST['businessdesc']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmpassword = trim($_POST['confirmpassword']);

    // Validate form data
    if (empty($fullname)) {
        $fullname_error = "Full Name is required.";
    }
    if (empty($phone)) {
        $phone_error = "Phone Number is required.";
    }
    if (empty($street)) {
        $street_error = "Street is required.";
    }
    if (empty($city)) {
        $city_error = "City is required.";
    }
    if (empty($state)) {
        $state_error = "State is required.";
    }
    if (empty($zipcode)) {
        $zipcode_error = "Zipcode is required.";
    }
    if (empty($position)) {
        $position_error = "Position is required.";
    }
    if (empty($businessdesc)) {
        $businessdesc_error = "Business Description is required.";
    }
    if (empty($email)) {
        $email_error = "Email is required.";
    }
    if (empty($password)) {
        $password_error = "Password is required.";
    }
    if (empty($confirmpassword)) {
        $confirmpassword_error = "Confirm Password is required.";
    }

    // Check if passwords match
    if ($password != $confirmpassword) {
        $error_pass = "Passwords do not match.";
    }

    // Proceed if there are no validation errors
    if (empty($fullname_error) && empty($phone_error) && empty($street_error) && empty($city_error) && empty($state_error) && empty($zipcode_error) && empty($position_error) && empty($businessdesc_error) && empty($email_error) && empty($password_error) && empty($confirmpassword_error) && empty($error_pass)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_query = "SELECT COUNT(*) FROM trader WHERE email = :email";
        $check_stmt = oci_parse($conn, $check_query);
        oci_bind_by_name($check_stmt, ':email', $email);
        oci_execute($check_stmt);
        $row = oci_fetch_assoc($check_stmt);
        $email_exists = $row['COUNT(*)'];
        oci_free_statement($check_stmt);

        if ($email_exists > 0) {
            $email_error = "Email already exists.";
        } else {
            // Prepare the SQL statement
            $query = "INSERT INTO trader (trader_id, full_name, phone_num, street, city, state, zipcode, position, trader_password, business_desp, email) 
                      VALUES (trader_id_seq.NEXTVAL, :fullname, :phone, :street, :city, :state, :zipcode, :position, :password, :businessdesc, :email)";

            $stmt = oci_parse($conn, $query);

            // Bind parameters
            oci_bind_by_name($stmt, ':fullname', $fullname);
            oci_bind_by_name($stmt, ':phone', $phone);
            oci_bind_by_name($stmt, ':street', $street);
            oci_bind_by_name($stmt, ':city', $city);
            oci_bind_by_name($stmt, ':state', $state);
            oci_bind_by_name($stmt, ':zipcode', $zipcode);
            oci_bind_by_name($stmt, ':position', $position);
            oci_bind_by_name($stmt, ':password', $hashed_password);
            oci_bind_by_name($stmt, ':businessdesc', $businessdesc);
            oci_bind_by_name($stmt, ':email', $email);

            // Execute the SQL statement
            $result = oci_execute($stmt);

            if ($result) {
                echo "<script>alert('Thanks for registering.');</script>";
                header('location: index.php');
            } else {
                $registration_error = "Error: Unable to register trader.";
            }

            // Free statement and close connection
            oci_free_statement($stmt);
        }
    }

    oci_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Register</title>
    <link href="css/trader_register.css" rel="stylesheet">
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container2">
        <div class="header2">Trader Registration</div>
        <div class="sub-header">Traders can register here using the form below</div>
        <div class="form-container2">
            <form action="#" method="POST">
                <?php if ($error_pass): ?>
                    <div class="error-message"><?php echo $error_pass; ?></div>
                <?php endif; ?>
                <?php if ($email_error): ?>
                    <div class="error-message"><?php echo $email_error; ?></div>
                <?php endif; ?>
                <?php if ($registration_error): ?>
                    <div class="error-message"><?php echo $registration_error; ?></div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo isset($fullname) ? htmlspecialchars($fullname) : ''; ?>">
                    <?php if ($fullname_error): ?>
                        <div class="error-message"><?php echo $fullname_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                    <?php if ($phone_error): ?>
                        <div class="error-message"><?php echo $phone_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="street">Street:</label>
                    <input type="text" id="street" name="street" value="<?php echo isset($street) ? htmlspecialchars($street) : ''; ?>">
                    <?php if ($street_error): ?>
                        <div class="error-message"><?php echo $street_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" value="<?php echo isset($city) ? htmlspecialchars($city) : ''; ?>">
                    <?php if ($city_error): ?>
                        <div class="error-message"><?php echo $city_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="state">State:</label>
                    <input type="text" id="state" name="state" value="<?php echo isset($state) ? htmlspecialchars($state) : ''; ?>">
                    <?php if ($state_error): ?>
                        <div class="error-message"><?php echo $state_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="zipcode">Zipcode:</label>
                    <input type="text" id="zipcode" name="zipcode" value="<?php echo isset($zipcode) ? htmlspecialchars($zipcode) : ''; ?>">
                    <?php if ($zipcode_error): ?>
                        <div class="error-message"><?php echo $zipcode_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group checkbox-group">
                    <label>Position:</label>
                    <label><input type="checkbox" name="position" value="Bakery" <?php echo ($position == 'Bakery') ? 'checked' : ''; ?>> Bakery</label>
                    <label><input type="checkbox" name="position" value="Fishmonger" <?php echo ($position == 'Fishmonger') ? 'checked' : ''; ?>> Fishmonger</label>
                    <label><input type="checkbox" name="position" value="Butcher" <?php echo ($position == 'Butcher') ? 'checked' : ''; ?>> Butcher</label>
                    <label><input type="checkbox" name="position" value="Delicatessen" <?php echo ($position == 'Delicatessen') ? 'checked' : ''; ?>> Delicatessen</label>
                    <label><input type="checkbox" name="position" value="Greengrocer" <?php echo ($position == 'Greengrocer') ? 'checked' : ''; ?>> Greengrocer</label>
                    <?php if ($position_error): ?>
                        <div class="error-message"><?php echo $position_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="businessdesc">Business Description:</label>
                    <textarea id="businessdesc" name="businessdesc" rows="4"><?php echo isset($businessdesc) ? htmlspecialchars($businessdesc) : ''; ?></textarea>
                    <?php if ($businessdesc_error): ?>
                        <div class="error-message"><?php echo $businessdesc_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    <?php if ($email_error): ?>
                        <div class="error-message"><?php echo $email_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                    <?php if ($password_error): ?>
                        <div class="error-message"><?php echo $password_error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="confirmpassword">Confirm Password:</label>
                    <input type="password" id="confirmpassword" name="confirmpassword">
                    <?php if ($confirmpassword_error): ?>
                        <div class="error-message"><?php echo $confirmpassword_error; ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="submit-btn">Submit</button>
                <a href="index.php" class="btn btn-secondary bg-dark">Back To Login</a>
            </form>
        </div>
    </div>
    <script>
        var checkboxes = document.querySelectorAll('input[type="checkbox"][name="position"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    checkboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
