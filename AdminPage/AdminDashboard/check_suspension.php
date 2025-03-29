<?php
include "../../RegisterLayout/conn.php";

// Check and update suspended users
$checkSuspensionSql = "UPDATE users 
                       SET UserStatus = 'Active', suspension_end = NULL 
                       WHERE UserStatus = 'Suspended' 
                       AND suspension_end < NOW()";
$result = $_conn->query($checkSuspensionSql);

header('Content-Type: application/json');
echo json_encode(['updated' => $result && $result->affected_rows > 0]);