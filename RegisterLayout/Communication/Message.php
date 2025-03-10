<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "../conn.php";

$user_id = isset($_COOKIE['UID']) ? $_COOKIE['UID'] : null;
// Set proper headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the raw POST data
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Check if JSON was valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON: ' . json_last_error_msg());
        }
        
        $Type = isset($data['Type']) ? $data['Type'] : '';
        
        switch ($Type) {
            case "createGroup":
                try {
                    // Get the raw POST data
                    $json = file_get_contents('php://input');
                    $groupData = json_decode($json, true);
            
                    // Check if JSON was valid
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new Exception('Invalid JSON: ' . json_last_error_msg());
                    }
                    
                    // Validate required fields
                    if (empty($groupData['name'])) {
                        throw new Exception('Group name is required');
                    }
                    
                    // Process the group data
                    $groupName = $groupData['name'];
                    $groupDescription = isset($groupData['description']) ? $groupData['description'] : '';
                    $memberEmail = isset($groupData['members']) ? $groupData['members'] : '';
                    $role = isset($groupData['role']) ? $groupData['role'] : 'member';
                    
                    // First check if this is the group creator (admin)
                    if ($role === 'ADMIN') {
                        $userId = isset($_COOKIE['UID']) ? $_COOKIE['UID'] : null;
                        if (empty($userId)) {
                            throw new Exception('User ID is required');
                        }
                        try {
                            // Insert into groupusers table first and get the GroupID
                            $stmt = $_conn->prepare("INSERT INTO groupusers (GroupName, GroupRole, UserID) VALUES (?, ?, ?)");
                            if (!$stmt) {
                                throw new Exception("Database error: " . $_conn->error);
                            }
                            
                            $stmt->bind_param("ssi", $groupName, $role, $userId);
                            $stmt->execute();
                            
                            if ($stmt->affected_rows === 0) {
                                throw new Exception("Failed to create group");
                            }
                            
                            // Get the GroupID that was just created
                            $groupId = $_conn->insert_id;
                            $stmt->close();
                            
                            // Now insert into groupchat with the GroupID
                            $stmt = $_conn->prepare("INSERT INTO groupinfo (GroupDesc, GroupID) VALUES (?, ?)");
                            if (!$stmt) {
                                throw new Exception("Database error: " . $_conn->error);
                            }
                            
                            $stmt->bind_param("si", $groupDescription, $groupId);
                            $stmt->execute();
                            
                            if ($stmt->affected_rows === 0) {
                                throw new Exception("Failed to create group chat");
                            }
                            $stmt->close();
                            
                            // Commit the transaction
                            $_conn->commit();
                            
                            // Return success response
                            $response = [
                                'status' => 'success',
                                'message' => 'Group created successfully',
                                'data' => [
                                    'name' => $groupName,
                                    'description' => $groupDescription,
                                    'member' => $memberEmail,
                                    'role' => $role,
                                    'groupId' => $groupId
                                ]
                            ];
                            
                            echo json_encode($response);
                            
                        } catch (Exception $e) {
                            $_conn->rollback();
                            throw $e;
                        }
                        
                    } else {
                        // This is a regular member being added to group
                        // First check if the user exists
                        $sql = "SELECT id FROM users WHERE Email = ?";
                        $stmt = $_conn->prepare($sql);
                        
                        if (!$stmt) {
                            throw new Exception("Database error: " . $_conn->error);
                        }
                        
                        $stmt->bind_param("s", $memberEmail);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows === 0) {
                            // User doesn't exist - send a special response
                            $stmt->close();
                            http_response_code(202); // Accepted but not processed
                            echo json_encode([
                                'status' => 'warning',
                                'message' => "User with email $memberEmail not found. An invitation will be sent when they register.",
                                'data' => [
                                    'name' => $groupName,
                                    'email' => $memberEmail
                                ]
                            ]);
                            exit;
                        }
                        
                        $row = $result->fetch_assoc();
                        $userId = $row['id'];
                        $stmt->close();
                        
                        // Get the GroupID from the group name
                        $stmt = $_conn->prepare("SELECT GroupID FROM groupusers WHERE GroupName = ? LIMIT 1");
                        if (!$stmt) {
                            throw new Exception("Database error: " . $_conn->error);
                        }
                        
                        $stmt->bind_param("s", $groupName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows === 0) {
                            $stmt->close();
                            throw new Exception("Group does not exist");
                        }
                        
                        $row = $result->fetch_assoc();
                        $groupId = $row['GroupID'];
                        $stmt->close();
                        
                        // Add the user to the group
                        $stmt = $_conn->prepare("INSERT INTO groupusers (GroupID, GroupName, GroupRole, UserID) VALUES (?, ?, ?, ?)");
                        if (!$stmt) {
                            throw new Exception("Database error: " . $_conn->error);
                        }
                        
                        $stmt->bind_param("issi", $groupId, $groupName, $role, $userId);
                        $stmt->execute();
                        
                        if ($stmt->affected_rows === 0) {
                            throw new Exception("Failed to add user to group");
                        }
                        $stmt->close();
                        
                        // Return success response
                        $response = [
                            'status' => 'success',
                            'message' => 'User added to group successfully',
                            'data' => [
                                'name' => $groupName,
                                'member' => $memberEmail,
                                'role' => $role,
                                'groupId' => $groupId
                            ]
                        ];
                        
                        echo json_encode($response);
                    }
                    
                } catch (Exception $e) {
                    // Return error response
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
                break;
            case "sendMessageToServer":
                sendMessageToServer($data);
                break;
            default:
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Unknown action type'
                ]);
                break;
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if Type parameter exists
    if (isset($_GET['Type'])) {
        $Type = $_GET['Type'];

        switch ($Type) {
            case "GetDataLoadDefaultPage":
                // Check if UID cookie exists
                if (isset($_COOKIE['UID'])) {
                    $userId = $_COOKIE['UID'];
                    $groups = getGroupData($userId);
                    echo json_encode($groups);
                } else {
                    // Handle missing UID cookie
                    http_response_code(401); // Unauthorized
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'User not authenticated. Please log in.'
                    ]);
                }
                break;
            case "GetMessageInfo":
                $GroupID = isset($_GET['GroupID']) ? $_GET['GroupID'] : null;
                if (empty($GroupID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID is required'
                    ]);
                    exit;
                }
                $response = getMessageInfoForGroup($GroupID);

                echo json_encode($response);
                break;
            default:
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Unknown Type parameter value'
                ]);
                break;
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing Type parameter'
        ]);
    }
} else {
    // ...existing code for handling other request methods...
}

function getMessageInfoForGroup($groupId){
    global $_conn;
    $sql = "SELECT * FROM groupchat WHERE GroupID = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $groupId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['GroupID'],
            'username' => UsernameFromID($row['user_id']),
            'message' => $row['GroupMessage'],
            'messageType' => $row['GroupMessageType'],
            'timestamp' => $row['CreatedTime']
        ];
    }
    $stmt->close();
    return $messages;
}

function UsernameFromID($userId){
    global $_conn;
    $sql = "SELECT name FROM users WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['name'];
}

function getGroupData($userId) {
    global $_conn;
    
    $sql = "SELECT * FROM groupusers WHERE UserID = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $groups = [];
    while ($row = $result->fetch_assoc()) {
        $groups[] = [
            'id' => $row['GroupID'],
            'name' => $row['GroupName'],
            'role' => $row['GroupRole'],
            'Time' => $row['CreatedTime']
        ];
    }
    $stmt->close();
    return $groups;
}

function JsonEnCode($response){
    return json_encode($response);
}

function sendMessageToServer($messageData) {
    global $_conn, $user_id;
    try {
        // Use the UserID from the request, or fallback to the cookie
        $userId = !empty($messageData['UserID']) ? $messageData['UserID'] : $user_id;
        
        if (empty($userId)) {
            throw new Exception('User ID is required');
        }
        if (empty($messageData['GroupID'])) {
            throw new Exception('Group ID is required');
        }
        if (empty($messageData['message'])) {
            throw new Exception('Message content is required');
        }

        $groupId = $messageData['GroupID'];
        $message = $messageData['message'];
        $messageType = $messageData['messageType'] ?? 'TEXT';

        // Debug output
        error_log("Sending message. GroupID: $groupId, UserID: $userId, Message: $message");

        // Fix the SQL query - add the correct number of placeholders
        $stmt = $_conn->prepare("INSERT INTO groupchat (GroupID, user_id, GroupMessage, GroupMessageType) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $_conn->error);
        }

        $stmt->bind_param("iiss", $groupId, $userId, $message, $messageType);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to send message");
        }
        $stmt->close();

        $response = [
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [
                'groupId' => $groupId,
                'userId' => $userId,
                'message' => $message,
                'messageType' => $messageType,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
?>