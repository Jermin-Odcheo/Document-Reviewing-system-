<?php
    $first_name = "";
    $last_name = "";
    $email = "";
    $password = "";
    $account_type = "";

    include "C:\wamp64\www\Document-Reviewing-system-\config\db.php";

    $error_message = "";
    $success_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $account_type = $_POST["account_type"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        
        do {
            // Check for empty fields
            if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($account_type) || empty($confirm_password)) {
                $error_message = "All fields are required";
                break;
            }

            // Check if passwords match
            if ($password !== $confirm_password) {
                $error_message = "Passwords do not match";
                break;
            }

            // Check if the email already exists
            $email_check_query = "SELECT user_id FROM users WHERE email = ?";
            $stmt = $db->prepare($email_check_query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Email already exists. Please use a different email.";
                break;
            }

            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert into database
            $sql = "INSERT INTO users (first_name, last_name, email, password, account_type) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $account_type);
            $result = $stmt->execute();

            if (!$result) {
                $error_message = "Error adding user: " . $db->error;
                break;
            }

            // Reset input fields
            $first_name = "";
            $last_name = "";
            $email = "";
            $password = "";
            $account_type = "";

            $success_message = "User added successfully";

            header("Location: admin_user_manager.php");
            exit;
        } while (false);
    }
?>


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../styles/add_user.css">
    <link rel="icon" type="png" href="../../assets/img/SLU Logo.png">
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
                                    <?php 
                                        if(!empty($error_message)){
                                            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    <strong>'.$error_message.'</strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                        }
                                    ?>
                                </div>
                                <div class="col-md-6 text-end">
                                    <img src="../../assets/img/SLU Logo.png" style="height: 50px;">
                                </div>
                            </div>


                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">First Name</h6>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="" required>
                                        <label for="first_name">Enter your first name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Last Name</h6>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="" required>
                                        <label for="last_name">Enter your last name</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Email Address</h6>
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" value="" required>
                                        <label for="email">Enter your email address</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">User Role</h6>
                                    <div class="form-floating">
                                        <select class="form-select" id="account_type" name="account_type" required>
                                            <option value="" selected disabled>Select a role</option>
                                            <option value="admin">Admin</option>
                                            <option value="uploader">Uploader</option>
                                            <option value="reviewer">Reviewer</option>
                                        </select>
                                        <label for="account_type">Select user role</label>
                                    </div>
                                </div>
                            </div>

                            <?php
                                if(!empty($success_message)){
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>'.$success_message.'</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                }
                            ?>

                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Password</h6>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password" name="password" value="" required>
                                        <label for="password">Create your password</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Confirm Password</h6>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="" required>
                                        <label for="confirm_password">Repeat your password</label>
                                    </div>
                                </div>
                            </div>
                            <?php
                                if(!empty($success_message)){
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                           <strong>'.$success_message.'</strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                 }
                            ?>
                           

                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; width: 170px;">Add User</button>
                            </div>

                            <div class="text-center mt-1">
                                <a href="./admin_user_manager.php" class="btn btn-secondary" style="padding: 0.5rem 1rem; width: 170px;">Back</a>
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
                    <p>User added successfully!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="okButton">OK</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bottom">
        <?php include "./footer.php"?>
    </footer>
</body>
</html>