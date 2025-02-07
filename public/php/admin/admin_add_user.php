<?php
$error_message = '';
$fname_error = '';
$lname_error = '';
$email_error = '';
$role_error = '';
$password_error = '';
$form_valid = true;
$registration_success = false;

$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$account_type = isset($_POST['user_role']) ? $_POST['user_role'] : '';
$online_status = 0;
$forgot_pass = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../../config/db.php';

    $first_name = $db->real_escape_string(trim(preg_replace('/\s+/', ' ', $_POST['first_name'])));
    $last_name = $db->real_escape_string(trim(preg_replace('/\s+/', ' ', $_POST['last_name'])));
    $email = $db->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($first_name)) {
        $fname_error = "This field is required.";
        $fname_class = 'is-invalid';
        $form_valid = false;
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s'-.]+$/", $first_name)) {
        $fname_error = "First Name contains invalid characters.";
        $fname_class = 'is-invalid';
        $form_valid = false;
    }

    if (empty($last_name)) {
        $lname_error = "This field is required.";
        $lname_class = 'is-invalid';
        $form_valid = false;
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s'-.]+$/", $last_name)) {
        $lname_error = "Last Name contains invalid characters.";
        $lname_class = 'is-invalid';
        $form_valid = false;
    }

    if (empty($email)) {
        $email_error = "This field is required.";
        $email_class = 'is-invalid';
        $form_valid = false;
    } else {
        $email_check_sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $db->query($email_check_sql);
        if ($result->num_rows > 0) {
            $email_error = "The email address is already taken.";
            $email_class = 'is-invalid';
            $form_valid = false;
        }
    }

    if (empty($account_type)) {
        $role_error = "This field is required.";
        $role_class = 'is-invalid';
        $form_valid = false;
    }

    if (empty($password)) {
        $password_error = "This field is required.";
        $password_class = 'is-invalid';
        $form_valid = false;
    } elseif (strlen($password) < 8 || strlen($password) > 16) {
        $password_error = "Password must be between 8 and 16 characters.";
        $password_class = 'is-invalid';
        $form_valid = false;
    } elseif ($password !== $confirm_password) {
        $password_error = "Passwords do not match.";
        $password_class = 'is-invalid';
        $form_valid = false;
    }

    if ($form_valid) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $addUserSQL = "INSERT INTO users (email, password, first_name, last_name, account_type, online_status, forgot_pass) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $addUserStmt = $db->prepare($addUserSQL);
        $addUserStmt->bind_param("sssssis", $email, $hashed_password, $first_name, $last_name, $account_type, $online_status, $forgot_pass);

        if ($addUserStmt->execute()) {
            $registration_success = true;
        } else {
            $error_message = "Error: " . $addUserSQL . "<br>" . $db->error;
        }
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMDD - Document Reviewer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" type="png" href="../../assets/img/SLU Logo.png"> 
    <link rel="stylesheet" type="text/css" href="../../styles/add_user.css">
    <style>
        .container {
            padding-bottom: 60px;
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
                                    <h1 class="text-start mb-4">Add User</h1>
                                </div>
                                <div class="col-md-6 text-end">
                                    <img src="../../assets/img/SLU Logo.png" style="height: 50px;">
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">First Name</h6>
                                    <div class="form-floating">
                                        <input type="text" class="form-control <?php echo $fname_class; ?>" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                                        <label for="first_name">Enter your first name</label>
                                        <div class="invalid-feedback">
                                            <?php echo $fname_error; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Last Name</h6>
                                    <div class="form-floating">
                                        <input type="text" class="form-control <?php echo $lname_class; ?>" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                                        <label for="last_name">Enter your last name</label>
                                        <div class="invalid-feedback">
                                            <?php echo $lname_error; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mt-3">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Email Address</h6>
                                    <div class="form-floating">
                                        <input type="email" class="form-control <?php echo $email_class; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                        <label for="email">Enter your email address</label>
                                        <div class="invalid-feedback">
                                            <?php echo $email_error; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">User Role</h6>
                                    <div class="form-floating">
                                        <select class="form-select <?php echo $role_class; ?>" id="user_role" name="user_role">
                                            <option value="" selected disabled>Select a role</option>
                                            <option value="admin">Admin</option>
                                            <option value="uploader">Uploader</option>
                                            <option value="reviewer">Reviewer</option>
                                        </select>
                                        <label for="user_role">Select user role</label>
                                        <div class="invalid-feedback">
                                            <?php echo $role_error; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mt-3">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Password</h6>
                                    <div class="form-floating">
                                        <input type="password" class="form-control <?php echo $password_class; ?>" id="password" name="password" value="">
                                        <label for="password">Create your password</label>
                                        <div class="invalid-feedback">
                                            <?php echo $password_error; ?>
                                        </div>
                                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Confirm Password</h6>
                                    <div class="form-floating">
                                        <input type="password" class="form-control <?php echo $password_class; ?>" id="confirm_password" name="confirm_password" value="">
                                        <label for="confirm_password">Repeat your password</label>
                                        <div class="invalid-feedback">
                                            <?php echo $password_error; ?>
                                        </div>
                                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('confirm_password', this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Add User</button>
                            </div>

                            <div class="text-center mt-3">
                                <p>Changed your mind? <a href="./admin_user_manager.php" class="link-secondary">Go Back</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">User Added</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>The user has been added successfully!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="okButton">OK</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bottom">
        <?php include "../general/footer.php"?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../src/js/show_pwd.js"></script>
    <script src="../../../src/js/registration.js"></script>
    <script>
        <?php if ($registration_success): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            });
        <?php endif; ?>

        document.getElementById('okButton').addEventListener('click', function() {
            window.location.href = './admin_user_manager.php';
        });
    </script>
</body>
</html>