<?php
include "../../../config/db.php";

$showModal = false; // Flag to determine if the modal should be shown

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT email, first_name, last_name, account_type FROM users WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $account_type = $row['account_type'];
    } else {
        echo "<script>alert('User not found!'); window.location.href = 'admin_user_manager.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href = 'admin_user_manager.php';</script>";
    exit();
}

// Handle form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $account_type = $_POST['user_role'];
    $password = $_POST['password'];

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($account_type)) {
        echo "<script>alert('All fields except password are required!'); window.history.back();</script>";
        exit();
    }

    // Prepare the SQL query
    if (!empty($password)) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, account_type = ?, password = ? WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $account_type, $hashed_password, $user_id);
    } else {
        // Update without password
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, account_type = ? WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $account_type, $user_id);
    }

    // Execute the query and handle response
    if ($stmt->execute()) {
        $showModal = true; // Set flag to show modal
    } else {
        echo "<script>alert('Error updating user! Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMDD - Document Reviewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../styles/add_user.css" rel="stylesheet">
</head>
<body>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card shadow extra-large-card custom-card-size">
                <div class="card-body">
                    <form action="" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="form-label mb-2">First Name</h6>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                                    <label for="first_name">Enter your first name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="form-label mb-2">Last Name</h6>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                                    <label for="last_name">Enter your last name</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mt-3">
                            <div class="col-md-6">
                                <h6 class="form-label mb-2">Email Address</h6>
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    <label for="email">Enter your email address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="form-label mb-2">User Role</h6>
                                <div class="form-floating">
                                    <select class="form-select" id="user_role" name="user_role">
                                        <option value="admin" <?php echo ($account_type === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                        <option value="uploader" <?php echo ($account_type === 'Uploader') ? 'selected' : ''; ?>>Uploader</option>
                                        <option value="reviewer" <?php echo ($account_type === 'Reviewer') ? 'selected' : ''; ?>>Reviewer</option>
                                    </select>
                                    <label for="user_role">Select user role</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mt-3">
                            <div class="col-md-6">
                                <h6 class="form-label mb-2">New Password (Optional)</h6>
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" name="password">
                                    <label for="password">Enter a new password (leave blank if unchanged)</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>

                        <div class="text-center mt-3">
                            <p>Changed your mind? <a href="./admin_user_manager.php" class="link-secondary">Go Back</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<?php if ($showModal): ?>
<div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" style="display: block; background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
            </div>
            <div class="modal-body">
                User updated successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="redirectToUserManager()">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    function redirectToUserManager() {
        window.location.href = 'admin_user_manager.php';
    }
</script>
<?php endif; ?>

<footer class="bottom">
    <?php include "../general/footer.php"; ?>
</footer>

</body>
</html>