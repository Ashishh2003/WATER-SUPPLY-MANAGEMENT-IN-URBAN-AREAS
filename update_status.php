<?php
include 'db.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable CORS if needed
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode([
        'success' => false, 
        'error' => 'Method not allowed',
        'allowed_methods' => ['POST']
    ]));
}

// Get and validate input
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$complaintId = filter_var($input['complaint_id'] ?? null, FILTER_VALIDATE_INT);
$table = filter_var($input['table'] ?? null, FILTER_SANITIZE_STRING);
$adminId = filter_var($input['admin_id'] ?? null, FILTER_VALIDATE_INT);

// Validate required parameters
$errors = [];
if (!$complaintId) $errors[] = 'Invalid or missing complaint_id';
if (!$table) $errors[] = 'Invalid or missing table parameter';
if (!$adminId) $errors[] = 'Invalid or missing admin_id';

if (!empty($errors)) {
    http_response_code(400);
    die(json_encode([
        'success' => false,
        'error' => 'Invalid parameters',
        'details' => $errors
    ]));
}

// Validate table name against whitelist
$allowedTables = ['sewerage', 'drainage', 'illegal_connections'];
if (!in_array($table, $allowedTables)) {
    http_response_code(400);
    die(json_encode([
        'success' => false, 
        'error' => 'Invalid table',
        'allowed_tables' => $allowedTables
    ]));
}

try {
    // Begin transaction for atomic operations
    $conn->begin_transaction();
    
    // 1. Verify admin exists and has permissions
    $adminCheck = $conn->prepare("SELECT id FROM admins WHERE id = ? AND is_active = 1");
    $adminCheck->bind_param("i", $adminId);
    $adminCheck->execute();
    
    if ($adminCheck->get_result()->num_rows === 0) {
        throw new Exception("Unauthorized or inactive admin");
    }
    
    // 2. Verify complaint exists and isn't already completed
    $complaintCheck = $conn->prepare("SELECT status FROM $table WHERE id = ?");
    $complaintCheck->bind_param("i", $complaintId);
    $complaintCheck->execute();
    $result = $complaintCheck->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Complaint not found");
    }
    
    $currentStatus = $result->fetch_assoc()['status'];
    if ($currentStatus === 'completed') {
        throw new Exception("Complaint already completed");
    }
    
    // 3. Update status with admin who completed it
    $updateStmt = $conn->prepare("
        UPDATE $table 
        SET status = 'completed', 
            completed_at = NOW(), 
            completed_by = ?
        WHERE id = ?
    ");
    $updateStmt->bind_param("ii", $adminId, $complaintId);
    $updateStmt->execute();
    
    // 4. Log the status change
    $logStmt = $conn->prepare("
        INSERT INTO complaint_status_logs 
        (complaint_id, table_name, old_status, new_status, changed_by, changed_at)
        VALUES (?, ?, ?, 'completed', ?, NOW())
    ");
    $logStmt->bind_param("issi", $complaintId, $table, $currentStatus, $adminId);
    $logStmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Status updated successfully',
        'data' => [
            'complaint_id' => $complaintId,
            'previous_status' => $currentStatus,
            'updated_status' => 'completed',
            'completed_by' => $adminId,
            'completed_at' => date('Y-m-d H:i:s')
        ]
    ]);
    
} catch (Exception $e) {
    // Rollback on error
    if (isset($conn) && $conn->in_transaction) {
        $conn->rollback();
    }
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database operation failed',
        'system_error' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
    
    // Log detailed error for debugging
    error_log("Complaint Status Update Error: " . $e->getMessage());
    
} finally {
    // Clean up
    if (isset($adminCheck)) $adminCheck->close();
    if (isset($complaintCheck)) $complaintCheck->close();
    if (isset($updateStmt)) $updateStmt->close();
    if (isset($logStmt)) $logStmt->close();
    if (isset($conn)) $conn->close();
}
?>