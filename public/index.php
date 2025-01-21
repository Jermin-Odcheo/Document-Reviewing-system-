<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>None atm</title>
    <link rel="stylesheet" href="../public/assets/styles/index.css">
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
              <a href="signup.php">Create an Account</a>
         </div>
            </form> 
        </div>
    </div>
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
