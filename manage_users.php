<?php
require_once 'config.php';
require_once 'auth_check.php';
require_admin(); // Only admins can manage users

header('Content-Type: application/json');
$response = ['data' => [], 'error' => null];

// Fetch all users (excluding sensitive info like password for general listing)
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";

if ($stmt = $mysqli->prepare($sql)) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['created_at_formatted'] = date("Y-m-d H:i", strtotime($row['created_at']));
            $response['data'][] = $row;
        }
    } else {
        $response['error'] = 'Failed to fetch users: ' . $stmt->error;
    }
    $stmt->close();
} else {
    $response['error'] = 'Database error (prepare): ' . $mysqli->error;
}

echo json_encode($response);
$mysqli->close();
?>
