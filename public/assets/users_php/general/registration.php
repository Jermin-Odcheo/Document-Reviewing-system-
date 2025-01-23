<?php
$error_message = '';
$password_class = '';
$email_class = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../../../config/db.php';

    $first_name = $db->real_escape_string($_POST['first_name']);
    $last_name = $db->real_escape_string($_POST['last_name']);
    $email = $db->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $account_type = 'Uploader';
    $online_status = '1';
    $forgot_pass = '0';

    // Check if email is already taken
    $email_check_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($email_check_sql);

    if ($result->num_rows > 0) {
        $error_message = "The email address is already taken.";
        $email_class = 'is-invalid';  // Add error class to email field
    } elseif ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (first_name, last_name, email, password, account_type, online_status, forgot_pass) 
                VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$account_type', '$online_status', '$forgot_pass')";

        if ($db->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }
    } else {
        $error_message = "Passwords do not match.";
        $password_class = 'is-invalid';
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../styles/registration.css">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
    <style>
        .container {
            padding-bottom: 60px; /* Adjust this value based on your footer height */
        }
    </style>
</head>
<body>

    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                <div class="card shadow extra-large-card custom-card-size">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h1 class="text-start mb-4">Create Account</h1>
                                <p class="text-start text-muted mb-4">Create a new account</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <img src="../../img/SLU Logo.png" style="height: 50px;">
                            </div>
                        </div> 

                            <!-- Display Error message -->
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">First Name</h6>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                        <label for="first_name">Enter your first name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Last Name</h6>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                        <label for="last_name">Enter your last name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Email Address</h6>
                                    <div class="form-floating">
                                        <input type="email" class="form-control <?php echo $email_class; ?>" id="email" name="email" required>
                                        <label for="email">Enter your email address</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Adding space between email and password -->
                            <div class="mb-4"></div>

                            <div class="row g-4 align-items-center">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Password</h6>
                                    <div class="form-floating">
                                        <input type="password" class="form-control <?php echo $password_class; ?>" id="password" name="password" required>
                                        <label for="password">Create your password</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="showPwd1">
                                            <label class="form-check-label" for="showPwd1"> Show Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Confirm Password</h6>
                                    <div class="form-floating">
                                        <input type="password" class="form-control <?php echo $password_class; ?>" id="confirm_password" name="confirm_password" required>
                                        <label for="confirm_password">Repeat your password</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="showPwd2">
                                            <label class="form-check-label" for="showPwd2"> Show Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Agree to all terms-->
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to all the <a href="#" data-bs-toggle="modal" data-bs-target="#myModal">Terms and Privacy Policy</a>
                                </label>
                            </div>

                            <!--Submit Button-->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2">Sign Up</button>
                            </div>

                            <!--Go Back-->
                            <div class="text-center mt-3">
                                <p>Changed your mind? <a href="../../../index.php" class="link-secondary">Go Back</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="myModalLabel"> Terms and Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
Website Privacy Policy Template [Text Format]
PRIVACY NOTICE
</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bottom">
        <?php include "./footer.php"?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../src\js/show_pwd.js"></script>
</body>
</html>