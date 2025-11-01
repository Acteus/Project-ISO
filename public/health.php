<?php
/**
 * Simple health check endpoint for Railway
 * This file bypasses Laravel routing for faster response
 */

header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'status' => 'healthy',
    'timestamp' => date('c'),
    'service' => 'laravel-app'
]);

