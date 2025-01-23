<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMDD-Document Reviewer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/styles/index.css">
    <link rel="icon" type="png" href="./assets/img/SLU Logo.png">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="../public/assets/img/SLU Logo.png" alt="Logo">
        </div>
        <div class="right-section">
            <form class="login-form" action="../db/log-in.php" method="POST">
                <h2 class="welcome-message">Welcome Back!</h2>
                
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="form-group password-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>

                <div class="form-options">
                    <div class="show-password">
                        <input type="checkbox" id="showPassword" class="form-check-input">
                        <label for="showPassword">Show Password</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit">Log In</button>

                <div class="signup-container">
                    <span>Don't have an account?</span>
                    <a href="./assets/users_php/general/registration.php">Create an Account</a>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <p class="mb-0">&copy; 2025 TMDD Interns | Alagad ni SLU</p>
    </footer>

    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>
