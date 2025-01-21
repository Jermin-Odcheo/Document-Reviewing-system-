<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Reviewer Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="index.css" rel="stylesheet">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
    <script src="../src\js/login.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">Login</div>
        <form action="login.php" method="post">
            <div class="email"> 
                <label form="idNum">ID Number:</label>
                <input type="text" id="email" name="email" required>
            </div>

            <div class="pwd">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="showPwd"> 
                <input type="checkbox" onclick="showLoginPassword()">
                <span>Show Password</span>
            </div>

            <div class="button"> 
                <input type="submit" value="Login">
            </div>

            <button class="signup_button" type="button" onclick="window.location.href=''">Sign-Up</button>
            <br><br>

            <a href="">Forgot Password?</a>
        </form>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <img src="assets/img/Samcis logo no bg.png" class="samcis_logo">
        </div>
        <div class="col-xs-6">
            <div class="mb-3">
            </div>
        </div>
    </div>
</body>
</html>