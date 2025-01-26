<?php
include("../../../../config/db.php");
session_start();

$message = "";

if (isset($_GET['key']) && isset($_GET['email'])) {
    $key = $_GET['key'];
    $email = $_GET['email'];

    $check = mysqli_query($db, "SELECT * FROM forget_password WHERE email='$email' AND temp_key='$key'");
    if (mysqli_num_rows($check) != 1) {
        echo "Invalid or expired reset link.";
        exit;
    }
} else {
    header('Location: index.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password1 = mysqli_real_escape_string($db, $_POST['password1']);
    $password2 = mysqli_real_escape_string($db, $_POST['password2']);

    if ($password1 === $password2) {
        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

        $stmt = $db->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();
        $stmt->close();


        $stmt_delete = $db->prepare("DELETE FROM forget_password WHERE email=?");
        $stmt_delete->bind_param("s", $email);
        $stmt_delete->execute();
        $stmt_delete->close();

        echo "Your password has been reset successfully. <a href='index.php'>Go to login</a>";
    } else {
        $message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../assets/styles/reset_password.css"> <!-- Link to your shared CSS -->
    <link rel="icon" href="../../../assets/img/SLU Logo.png" type="image/png">
</head>
<body>

    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow custom-card-size">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Reset Your Password</h1>
                        <p class="text-center text-muted mb-4">Please enter a new password below.</p>

                        <!-- Display messages -->
                        <?php if (!empty($message)) echo "<p class='alert alert-danger text-center'>$message</p>"; ?>

                        <form method="POST" class="mt-4">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password1" name="password1" required>
                                <label for="password1">New Password</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password2" name="password2" required>
                                <label for="password2">Confirm Password</label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100 py-2">Reset Password</button>
                            </div>

                            <div class="text-center mt-3">
                                <p>Remember your password? <a href="../../../index.php" class="link-secondary">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bottom">
        <?php include "../footer.php"; ?> 
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
