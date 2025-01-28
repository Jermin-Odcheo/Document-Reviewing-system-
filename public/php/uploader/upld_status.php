<?php
// Mock Data for Documents
$documents = [
    ['file_path' => 'Document1.pdf', 'upload_date' => '2025-01-01', 'status' => 'pending'],
    ['file_path' => 'Document2.pdf', 'upload_date' => '2025-01-02', 'status' => 'approved'],
    ['file_path' => 'Document3.pdf', 'upload_date' => '2025-01-03', 'status' => 'unreviewed'],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploader Dashboard</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="../../assets/styles/upld_status.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include "./upld_header.php"; ?>
    </header>

    <div class="container">
        <h1>Uploads and Status</h1>
        <div class="filter-section">
            <label for="status-filter">Select Status:</label>
            <select id="status-filter" name="status">
                <option value="all">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="unreviewed">Unreviewed</option>
            </select>
        </div>

        <table class="status-table">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Date Uploaded</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc) : ?>
                    <tr>
                        <td><?= htmlspecialchars($doc['file_path']) ?></td>
                        <td><?= htmlspecialchars($doc['upload_date']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($doc['status'])) ?></td>
                        <td>
                            <?php if ($doc['status'] === 'approved') : ?>
                                <button class="btn btn-secondary" disabled>Resubmit</button>
                                <button class="btn btn-success" onclick="markAsDone(this)">Mark as Done</button>
                            <?php else : ?>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Resubmit</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Documents</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <?php include "./upload_download/uploads.php"; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function markAsDone(button) {
            if (confirm("Are you sure you want to mark this as done? Once marked, you cannot undo this action.")) {
                button.textContent = "Done";
                button.classList.remove("btn-success");
                button.classList.add("btn-secondary");
                button.setAttribute("disabled", "true");
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Ns13lV6LMYkID2RrZxLFdxh+vAY+iikQ3hAxNqEaqG+y0R0EuyBOEe+FlKa+N6lV" crossorigin="anonymous"></script>
</body>

</html>
