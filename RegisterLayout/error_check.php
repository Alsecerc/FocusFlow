<?php
// Clear any output buffering
if (ob_get_level()) ob_end_clean();

// Set content type to prevent any formatting
header('Content-Type: text/plain');

echo "PHP Version: " . phpversion() . "\n\n";

echo "=== PHP Configuration ===\n";
echo "display_errors: " . ini_get('display_errors') . "\n";
echo "error_reporting: " . ini_get('error_reporting') . "\n";
echo "output_buffering: " . ini_get('output_buffering') . "\n\n";

echo "=== Test JSON Response ===\n";
$test_data = ['status' => 'success', 'message' => 'This is a test JSON response'];
$json = json_encode($test_data);
echo "JSON Encoded: $json\n\n";

echo "=== Headers ===\n";
$headers = headers_list();
foreach ($headers as $header) {
    echo "$header\n";
}

echo "\n=== MySQL Connection Test ===\n";
if (file_exists("conn.php")) {
    include "conn.php";
    if (isset($_conn) && !$_conn->connect_error) {
        echo "Database connection successful\n";
        
        // Test a simple query
        $result = $_conn->query("SELECT 1 as test");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "Query test successful: " . $row['test'] . "\n";
        } else {
            echo "Query failed: " . $_conn->error . "\n";
        }
    } else {
        echo "Database connection failed\n";
        if (isset($_conn) && $_conn->connect_error) {
            echo "Error: " . $_conn->connect_error . "\n";
        }
    }
} else {
    echo "conn.php not found\n";
}

echo "\n=== End of diagnostics ===\n";
?>
