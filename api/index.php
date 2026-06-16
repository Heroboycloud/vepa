<?php
// Add proper response headers
header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'status' => 'success',
    'message' => 'PHP app is running!',
    'path' => $_SERVER['REQUEST_URI']
]);
?>
