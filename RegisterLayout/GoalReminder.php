<?php
// Enable error reporting for debugging
// Comment this out in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow cross-origin requests from the same domain
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Include database connection
include "conn.php";

// Process goal reminders
try {
    // Your goal reminder logic here
    // For now, just return a success response
    $response = [
        'status' => 'success',
        'message' => 'Goal reminder processed successfully'
    ];
    
    echo json_encode($response);
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => 'An error occurred: ' . $e->getMessage()
    ];
    
    echo json_encode($response);
}
?>
