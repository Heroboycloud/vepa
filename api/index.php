<?php
// api/index.php - Enhanced Router with Query Parameter Support

// ============================================
// 1. Get Clean Path (Strip Query Parameters)
// ============================================
$fullPath = $_SERVER['REQUEST_URI'];
$path = strtok($fullPath, '?'); // Remove everything after ?
$path = rtrim($path, '/');      // Remove trailing slash
if (empty($path)) $path = '/';  // Root path

// ============================================
// 2. Route Definitions
// ============================================

// --- EXACT MATCH ROUTES ---
if ($path === '/phpinfo') {
    require 'app.php';
}
elseif ($path === '/home') {
    require 'home.php';
}
elseif ($path === '/csv-json') {
    require 'excel-json.php';
}
elseif ($path === '/dbview') {
    require 'db-viewer.php';
}
elseif ($path === '/dashboard') {
    require 'dashboard.php';
}
elseif ($path === '/random-user') {
    require 'randomuser.php';
}

// --- LOCATION ROUTE (with lat & lon query parameters) ---
elseif ($path === '/location') {
    // lat and lon are automatically available in $_GET
    // Example: /location?lat=40.7128&lon=-74.0060
    require 'locget.php';
}

// --- 404 Not Found ---
else {
    http_response_code(404);
    require '404.php';
}
?>
