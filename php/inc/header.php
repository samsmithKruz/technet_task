<?php

// Check if the requested URL starts with /api
$requestUri = $_SERVER['REQUEST_URI'];

if (strpos($requestUri, '/api/') === 0) {
    // Redirect to /Api.php and preserve the request URL
    header("Location: /Api.php" .  str_replace('/api', '', $requestUri));
    exit();
}

// If we want to block access to the vendor and php directories
if (strpos($requestUri, '/vendor/') !== false || strpos($requestUri, '/php/') !== false) {
    // Redirect or show a 404 error if these directories are accessed directly
    http_response_code(404);
    echo "Not Found";
    exit();
}

// Handle other routes here, or load your main app logic
require_once __DIR__ . "/../bootstrap.php";

