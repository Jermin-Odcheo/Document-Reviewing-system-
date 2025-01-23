<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMDD-Document Reviewer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <input type="text" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <p class="signup-text"><a href="forgot-password.php">Forgot password?</a></p>
                <button type="submit">Log In</button>
      <div class="signup-container">
              <h6>Don't have an account?</h6>
              <a href="./assets/users_php/general/registration.php">Create an Account</a>
         </div>
            </form> 
        </div>
    </div>
    <footer>
    <p class="mb-0">&copy; 2025 TMDD Interns | Alagad ni SLU </p>
    </footer>
</body>

<script>
    window.onload = function() {
        const params = getQueryParams();
        if (params.logout === 'success') {
            alert('You have successfully logged out.');
        } else {
            alert('No user is logged in');
        }
    };
</script>
</html>
