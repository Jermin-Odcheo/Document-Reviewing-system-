<?php
    require('../../config/db.php');

    $email = $POST['email'];
    $pwd = $POST['password'];

    $loginQuery = "SELECT user_id, email, password, first_name, last_name, account_type, online_status, forgot_pass FROM users WHERE email = ?";
    $stmt = $db->prepare($loginQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
?>