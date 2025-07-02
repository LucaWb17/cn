<?php
require_once 'config.php';
require_once 'auth_check.php'; // Ensures user is logged in
require_admin(); // Ensures only admin can access

header('Content-Type: application/json');

$response = [
    'data' => [],
    'error' => null,
    'stats' => [
        'total' => 0,
        'pending' => 0,
        'confirmed' => 0,
        'completed' => 0,
        'cancelled' => 0,
        'no_show' => 0
    ]
];

// Fetch all bookings for admin dashboard
// Includes user details (name/email or guest name/email)
$sql = "SELECT
            b.id,
            b.booking_date,
            b.booking_time,
            s.name as service_name,
            COALESCE(u.name, b.guest_name) as client_name,
            COALESCE(u.email, b.guest_email) as client_email,
            b.vehicle_make,
            b.vehicle_model,
            b.vehicle_year,
            b.vehicle_license_plate,
            b.status,
            b.notes
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        LEFT JOIN users u ON b.user_id = u.id
        ORDER BY b.booking_date DESC, b.booking_time DESC";

if ($stmt = $mysqli->prepare($sql)) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['booking_date_formatted'] = date("Y-m-d", strtotime($row['booking_date'])); // Consistent format
            $row['booking_time_formatted'] = date("H:i", strtotime($row['booking_time'])); // 24hr format for consistency
            $response['data'][] = $row;

            // Calculate stats
            $response['stats']['total']++;
            if (isset($response['stats'][strtolower($row['status'])])) {
                $response['stats'][strtolower($row['status'])]++;
            }
        }
    } else {
        $response['error'] = 'Failed to fetch bookings: ' . $stmt->error;
    }
    $stmt->close();
} else {
    $response['error'] = 'Database error (prepare): ' . $mysqli->error;
}

echo json_encode($response);
$mysqli->close();
?>
