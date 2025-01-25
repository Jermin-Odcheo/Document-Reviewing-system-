<?php
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'document_reviewing_system';


$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle "Reviewed" button click and update review status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reviewed_id'])) {
    $docId = intval($_POST['reviewed_id']);
    $sqlUpdate = "UPDATE documents SET review_status = 1 WHERE doc_id = $docId";
    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<script>alert('Document status updated to reviewed.');</script>";
    } else {
        echo "<script>alert('Error updating document status.');</script>";
    }
}


// Get filter and search values
$statusFilter = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : 'all';
$searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Build SQL query based on filter and search
$sql = "SELECT d.doc_id, d.file_path, d.review_status, d.uploader_id, 
        u.first_name, u.last_name 
        FROM documents d 
        JOIN users u ON d.uploader_id = u.user_id";

$conditions = [];

// Add filter condition
if ($statusFilter === 'reviewed') {
    $conditions[] = "d.review_status = 1"; 
} elseif ($statusFilter === 'pending') {
    $conditions[] = "d.review_status = 0"; 
} elseif ($statusFilter === 'unreviewed') {
    $conditions[] = "d.review_status = 0"; 
}

// Add search condition
if (!empty($searchQuery)) {
    $conditions[] = "(d.file_path LIKE '%$searchQuery%' OR u.first_name LIKE '%$searchQuery%' OR u.last_name LIKE '%$searchQuery%')";
}

// Combine conditions into SQL
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
   <title>
      TMDD - Reviewer Dashboard
   </title>
   <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
   <link href="../../assets/styles/admin.css" rel="stylesheet">
   <link rel="stylesheet" href="../../assets/styles/status.css">

   <link rel="icon" type="png" href="../../assets/img/SLU Logo.png">
</head>

<body>
   <header>
      <?php include "./rev_header.php" ?>
   </header>
   <div class="d-flex">
      <div class="flex-grow-1">
         <div class="content">
         <main>
        <div class="container">
            <h1>Reviews and Status</h1>
            <div class="filter-section">
                <form method="GET" action="" id="filterForm">
                    <label for="status-filter">Select Status:</label>
                    <select id="status-filter" name="status" onchange="document.getElementById('filterForm').submit();">
                        <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All</option>
                        <option value="reviewed" <?= $statusFilter === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                        <option value="unreviewed" <?= $statusFilter === 'unreviewed' ? 'selected' : '' ?>>Unreviewed</option>
                        <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                    </select>

                    <input 
                        type="text" 
                        id="status-search" 
                        name="search" 
                        placeholder="Search..." 
                        value="<?= htmlspecialchars($searchQuery) ?>" 
                        onkeypress="if(event.key === 'Enter') document.getElementById('filterForm').submit();">
                </form>
            </div>

            <table class="status-table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date Updated</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status = $row['review_status'] ? "Reviewed" : "Pending";
                            echo "<tr>
                                <td>{$row['file_path']}</td>
                                <td>{$row['first_name']} {$row['last_name']}</td>
                                <td>{$status}</td>
                                <td>
                      <div class='actions'>
                        <form method='POST' action=''>
                            <input type='hidden' name='reviewed_id' value='{$row['doc_id']}'>
                            <button type='submit' class='btn green'>Reviewed</button>
                        </form>
                                        <button class='btn red'>Update</button>
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr>
                            <td colspan='4'>No documents found</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    

            </div>
         </div>
      </div>
   </div>
   <footer class="bottom">
        <?php include "../general/footer.php"?>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
