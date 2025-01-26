<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../../vendor/autoload.php'; 

include("../../../../config/db.php");
session_start();

$message = "";
$message_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_reg = trim($_POST['email']);

    if (!filter_var($email_reg, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        $stmt = $db->prepare("SELECT first_name, last_name, email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email_reg);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message_success = "Please check your email inbox or spam folder and follow the steps.";

            // Generate a unique password reset key
            $key = md5(time() + rand(4000, 55000000));

            // Insert reset key into forget_password table
            $stmt_insert = $db->prepare("INSERT INTO forget_password (email, temp_key) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $email_reg, $key);
            $stmt_insert->execute();

            // Email setup with PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'samcistmdd@gmail.com'; 
                $mail->Password = 'tbfy ejqm fuuf atmb'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email details
                $mail->setFrom('noreply@yourwebsite.com', 'Document Reviewing System');
                $mail->addAddress($email_reg);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Click the link below to reset your password:\n" .
                              "http://localhost/Document-Reviewing-system--main/public/php/general/confirmations/reset_password.php?key=$key&email=$email_reg";

                // Send email
                $mail->send();
            } catch (Exception $e) {
                $message = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $message = "No account is associated with this email.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../assets/styles/forget_password.css">
    <link rel="icon" href="../../../../assets/img/SLU Logo.png" type="image/png">
</head>
<body>

    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow custom-card-size">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Forgot Password</h1>
                        <p class="text-center text-muted mb-4">Enter your email address to receive a password reset link.</p>
                
                        <?php if (!empty($message)) echo "<p class='alert alert-danger text-center'>$message</p>"; ?>
                        <?php if (!empty($message_success)) echo "<p class='alert alert-success text-center'>$message_success</p>"; ?>

                        <form method="POST" class="mt-4">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" required>
                                <label for="email">Enter your email address</label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100 py-2">Send Reset Link</button>
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
