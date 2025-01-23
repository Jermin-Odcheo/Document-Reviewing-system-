<?php
// download.php
$file = $_GET['file'];
$filePath = __DIR__ . '../../../../config/pdf/' . basename($file);

if (file_exists($filePath)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    echo 'File not found.';
}

?>