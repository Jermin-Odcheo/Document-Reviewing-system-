<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../../../../config/db.php'; // Ensure this file exists and is correct

header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    $response = [];

    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
        $fileName = $_FILES['files']['name'][$index];
        $fileTmpPath = $_FILES['files']['tmp_name'][$index];
        $uploadDir = '../../../../config/pdf/';
        $uploadPath = $uploadDir . basename($fileName);

        // Ensure uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            $uploaderId = 1; // Example uploader ID
            $reviewStatus = 'pending'; // Default review status
            $dateUploaded = date('Y-m-d H:i:s'); // Current timestamp
            $dateApproved = null; // Default to null for now

            $stmt = $db->prepare("INSERT INTO documents (uploader_id, file_path, date_uploaded, date_approved, review_status) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("issss", $uploaderId, $uploadPath, $dateUploaded, $dateApproved, $reviewStatus);
                if ($stmt->execute()) {
                    $response[] = [
                        'file' => $fileName,
                        'status' => 'success',
                        'message' => 'File uploaded and saved successfully'
                    ];
                } else {
                    $response[] = [
                        'file' => $fileName,
                        'status' => 'error',
                        'message' => 'Failed to save file details in the database'
                    ];
                }
                $stmt->close();
            } else {
                $response[] = [
                    'file' => $fileName,
                    'status' => 'error',
                    'message' => 'Failed to prepare database statement'
                ];
            }
        } else {
            $response[] = [
                'file' => $fileName,
                'status' => 'error',
                'message' => 'Failed to upload file'
            ];
        }

    }
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid request']);
}
