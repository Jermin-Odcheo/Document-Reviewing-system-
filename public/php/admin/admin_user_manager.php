<!DOCTYPE html>
<html>
<head>
  <title>TMDD - Document Reviewer</title>
  <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="../../assets/styles/admin.css" rel="stylesheet">
  <link rel="icon" type="png" href="../../assets/img/SLU Logo.png"> 
</head>
<body>
<header>
  <?php include "./admin_header.php"; ?>
</header>
<div class="d-flex">
  <div class="content flex-grow-1">
    <h2>User Accounts</h2>
    <div class="d-flex mb-3">
      <input class="form-control me-2" placeholder="Search Name" type="text"/>
      <select class="form-select me-2">
        <option>All Roles</option>
      </select>
      <select class="form-select me-2">
        <option>All Types</option>
      </select>
      <button class="btn btn-secondary me-2">Apply</button>
      <button class="btn btn-secondary">Clear All</button>
    </div>

    <div class="table-container">
      <h3>User Accounts Manager</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Email</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          include "../../../config/db.php"; 
          $sql = "SELECT email, first_name, last_name, account_type FROM users";
          $result = $db->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['user_id']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['first_name']}</td>
                      <td>{$row['last_name']}</td>
                      <td>{$row['account_type']}</td>
                      <td>
                        <a class='btn btn-primary btn-sm' href = 'edit_user.php?user_id={$row['user_id']}'>Edit</a>
                        <a class='btn btn-danger btn-sm' href = 'delete_user.php?user_id={$row['user_id']}'>Delete</a>
                      </td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='5'>No users found.</td></tr>";
          }
          $db->close();
          ?>
        </tbody>
      </table>
      <button class="btn btn-primary mt-3">+ Add User</button>
    </div>
  </div>
</div>
<footer class="bottom">
  <?php include "../general/footer.php"; ?>
</footer>

<script>
  document.getElementById('addUserBtn').addEventListener('click', function() {
    window.location.href = 'admin_add_user.php';
  });
</script>
</body>
</html>