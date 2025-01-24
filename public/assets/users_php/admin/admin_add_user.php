<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../styles/add_user.css">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
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
                                    <img src="../../img/SLU Logo.png" style="height: 50px;">
                                </div>
                            </div>


                            <div class="row g-4">
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

                            <div class="row g-4 align-items-center">
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
                                        <select class="form-select" id="user_role" name="user_role" required>
                                            <option value="" selected disabled>Select a role</option>
                                            <option value="admin">Admin</option>
                                            <option value="uploader">Uploader</option>
                                            <option value="reviewer">Reviewer</option>
                                        </select>
                                        <label for="user_role">Select user role</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4"></div>

                            <div class="row g-4 align-items-center">
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

                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to all the <a href="#" data-bs-toggle="modal" data-bs-target="#myModal">Terms and Privacy Policy</a>
                                </label>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2">Add User</button>
                            </div>

                            <div class="text-center mt-3">
                                <p>Changed your mind? <a href="../../../index.php" class="link-secondary">Go Back</a></p>
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
                    <h5 class="modal-title" id="successModalLabel">Registration Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your account has been created successfully!</p>
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