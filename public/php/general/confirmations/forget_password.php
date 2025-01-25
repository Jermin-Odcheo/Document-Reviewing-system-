<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/css/add_user.css">
    <link rel="icon" type="png" href="../../assets/img/SLU Logo.png">
</head>
<body>

    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                <div class="card shadow extra-large-card custom-card-size">
                    <div class="card-body">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Email Address</h6>
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <label for="email">Enter your email address</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Adding space between email and password -->
                            <div class="mb-4"></div> <!-- This div adds the space -->

                            <div class="row g-4 align-items-center">
                                <div class="col-md-6">
                                    <h6 class="form-label mb-2">Password</h6>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <label for="password">New password</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="showPassword1">
                                            <label class="form-check-label" for="showPassword1"> Show Password</label>
                                        </div>
                                    </div>
                                </div>

                            <!--Submit Button-->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2">Sign Up</button>
                            </div>

                            <!--Go Back-->
                            <div class="text-center mt-3">
                                <p>Changed your mind? <a href="#" class="link-secondary">Go Back</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
