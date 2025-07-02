<?php
require_once 'config.php';
require_once 'auth_check.php'; // Ensures user is logged in

header('Content-Type: application/json'); // We'll return JSON data

if (!is_logged_in()) {
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$bookings = [
    'upcoming' => [],
    'past' => []
];

// Fetch upcoming bookings
// Includes bookings for today onwards
$sql_upcoming = "SELECT b.id, b.booking_date, b.booking_time, s.name as service_name,
                        b.vehicle_make, b.vehicle_model, b.vehicle_year, b.vehicle_license_plate, b.status
                 FROM bookings b
                 JOIN services s ON b.service_id = s.id
                 WHERE b.user_id = ? AND b.booking_date >= CURDATE() AND b.status NOT IN ('completed', 'cancelled', 'no-show')
                 ORDER BY b.booking_date ASC, b.booking_time ASC";

if ($stmt_upcoming = $mysqli->prepare($sql_upcoming)) {
    $stmt_upcoming->bind_param("i", $user_id);
    if ($stmt_upcoming->execute()) {
        $result_upcoming = $stmt_upcoming->get_result();
        while ($row = $result_upcoming->fetch_assoc()) {
            // Format date and time for display if needed
            $row['booking_date_formatted'] = date("M d, Y", strtotime($row['booking_date']));
            $row['booking_time_formatted'] = date("h:i A", strtotime($row['booking_time']));
            $bookings['upcoming'][] = $row;
        }
    } else {
        echo json_encode(['error' => 'Failed to fetch upcoming bookings: ' . $stmt_upcoming->error]);
        $stmt_upcoming->close();
        $mysqli->close();
        exit;
    }
    $stmt_upcoming->close();
} else {
    echo json_encode(['error' => 'Database error (upcoming bookings prepare): ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

// Fetch past bookings
// Includes bookings before today OR completed/cancelled/no-show bookings
$sql_past = "SELECT b.id, b.booking_date, b.booking_time, s.name as service_name,
                    b.vehicle_make, b.vehicle_model, b.vehicle_year, b.vehicle_license_plate, b.status
             FROM bookings b
             JOIN services s ON b.service_id = s.id
             WHERE b.user_id = ? AND (b.booking_date < CURDATE() OR b.status IN ('completed', 'cancelled', 'no-show'))
             ORDER BY b.booking_date DESC, b.booking_time DESC";

if ($stmt_past = $mysqli->prepare($sql_past)) {
    $stmt_past->bind_param("i", $user_id);
    if ($stmt_past->execute()) {
        $result_past = $stmt_past->get_result();
        while ($row = $result_past->fetch_assoc()) {
            $row['booking_date_formatted'] = date("M d, Y", strtotime($row['booking_date']));
            $row['booking_time_formatted'] = date("h:i A", strtotime($row['booking_time']));
            $bookings['past'][] = $row;
        }
    } else {
        echo json_encode(['error' => 'Failed to fetch past bookings: ' . $stmt_past->error]);
        $stmt_past->close();
        $mysqli->close();
        exit;
    }
    $stmt_past->close();
} else {
    echo json_encode(['error' => 'Database error (past bookings prepare): ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

echo json_encode($bookings);
$mysqli->close();
?>
