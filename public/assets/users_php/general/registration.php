<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/css/add_user.css">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
</head>
<body>

    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                <div class="card shadow extra-large-card custom-card-size">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <h1 class="text-left mb-4">Create Account</h1>
                            <p class="text-left text-muted mb-4">Create a new account</p>

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
                                        <input type="email" class="form-control" id="email" name="email" required>
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
                                        <input type="password" class="form-control" id="password" name="password" required>
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
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../src\js/show_pwd.js"></script>

    <?php include "./footer.php"?>
</body>
</html>