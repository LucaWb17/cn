<?php
require_once 'config.php';
require_once 'auth_check.php'; // Ensures user is logged in

header('Content-Type: application/json');
$response = ['success' => false, 'message' => '', 'data' => []];

if (!is_logged_in()) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Determine whose vehicles to fetch. Default to logged-in user.
// If admin is viewing for a specific user (e.g., in admin_add_booking_form), use that user's ID.
$user_id_to_fetch_vehicles = $user_id;
if (is_admin() && isset($_GET['user_id_admin_view'])) {
    $user_id_to_fetch_vehicles = (int)$_GET['user_id_admin_view'];
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all vehicles for the determined user
    $sql = "SELECT id, make, model, year, license_plate FROM vehicles WHERE user_id = ? ORDER BY make ASC, model ASC";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $user_id_to_fetch_vehicles); // Use the potentially overridden user ID
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;
            }
            $response['success'] = true;
        } else {
            $response['message'] = 'Failed to fetch vehicles: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Database error (prepare GET): ' . $mysqli->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    // IMPORTANT: For POST actions (add, edit, delete), always operate on the LOGGED-IN user's vehicles,
    // UNLESS it's an admin performing an action that explicitly allows managing other users' vehicles (not implemented here for vehicles).
    // The GET request for fetching vehicles for admin view is separate.
    // So, for POST, $user_id (logged-in user) is the one to use for ownership checks.

    $vehicle_id = isset($_POST['vehicle_id']) ? (int)$_POST['vehicle_id'] : null;
    $make = isset($_POST['make']) ? sanitize_input($_POST['make']) : null;
    $model = isset($_POST['model']) ? sanitize_input($_POST['model']) : null;
    $year = isset($_POST['year']) ? (int)$_POST['year'] : null;
    $license_plate = isset($_POST['license_plate']) ? sanitize_input(strtoupper(str_replace(' ', '', $_POST['license_plate']))) : null;

    // Basic Validation (applies to the logged-in user managing their own vehicles)
    $errors = [];
    if (empty($make)) $errors[] = "Make is required.";
    if (empty($model)) $errors[] = "Model is required.";
    if (empty($year) || !is_numeric($year) || $year < 1900 || $year > (date("Y") + 2)) $errors[] = "Valid year is required.";
    if (empty($license_plate)) $errors[] = "License plate is required.";
    // Potentially more specific license plate format validation here

    if (!empty($errors)) {
        $response['message'] = implode(" ", $errors);
        echo json_encode($response);
        exit;
    }

    // Check for duplicate license plate for the same user (if adding or editing to a new plate)
    $check_lp_sql = "SELECT id FROM vehicles WHERE license_plate = ? AND user_id = ?";
    if ($action === 'edit') {
        $check_lp_sql .= " AND id != ?"; // Exclude current vehicle being edited
    }

    if($stmt_lp_check = $mysqli->prepare($check_lp_sql)){
        if($action === 'edit'){
            $stmt_lp_check->bind_param("sii", $license_plate, $user_id, $vehicle_id);
        } else { // add action
            $stmt_lp_check->bind_param("si", $license_plate, $user_id);
        }
        $stmt_lp_check->execute();
        $stmt_lp_check->store_result();
        if($stmt_lp_check->num_rows > 0){
            $response['message'] = "A vehicle with this license plate already exists in your account.";
            $stmt_lp_check->close();
            echo json_encode($response);
            exit;
        }
        $stmt_lp_check->close();
    } else {
        $response['message'] = "Database error (license plate check): " . $mysqli->error;
        echo json_encode($response);
        exit;
    }


    if ($action === 'add') {
        $sql = "INSERT INTO vehicles (user_id, make, model, year, license_plate) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("issis", $user_id, $make, $model, $year, $license_plate);
            if ($stmt->execute()) {
                $new_vehicle_id = $mysqli->insert_id;
                $response['success'] = true;
                $response['message'] = 'Vehicle added successfully.';
                $response['data'] = ['id' => $new_vehicle_id, 'make' => $make, 'model' => $model, 'year' => $year, 'license_plate' => $license_plate];
            } else {
                $response['message'] = 'Failed to add vehicle: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Database error (prepare add): ' . $mysqli->error;
        }
    } elseif ($action === 'edit') {
        if (!$vehicle_id) {
            $response['message'] = 'Vehicle ID is required for editing.';
        } else {
            $sql = "UPDATE vehicles SET make = ?, model = ?, year = ?, license_plate = ? WHERE id = ? AND user_id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssisii", $make, $model, $year, $license_plate, $vehicle_id, $user_id);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = 'Vehicle updated successfully.';
                        $response['data'] = ['id' => $vehicle_id, 'make' => $make, 'model' => $model, 'year' => $year, 'license_plate' => $license_plate];
                    } else {
                        $response['message'] = 'Vehicle not found, no changes made, or not authorized.';
                    }
                } else {
                    $response['message'] = 'Failed to update vehicle: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = 'Database error (prepare edit): ' . $mysqli->error;
            }
        }
    } elseif ($action === 'delete') {
        if (!$vehicle_id) {
            $response['message'] = 'Vehicle ID is required for deletion.';
        } else {
            // Optional: Check if vehicle is tied to any non-completed bookings.
            // For simplicity, we allow deletion. If needed, add a check here.
            // $check_bookings_sql = "SELECT COUNT(*) as count FROM bookings WHERE vehicle_id = ? AND status NOT IN ('completed', 'cancelled', 'no-show')";
            // ... if count > 0, prevent deletion or warn user.

            $sql = "DELETE FROM vehicles WHERE id = ? AND user_id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ii", $vehicle_id, $user_id);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = 'Vehicle deleted successfully.';
                    } else {
                        $response['message'] = 'Vehicle not found or not authorized.';
                    }
                } else {
                    $response['message'] = 'Failed to delete vehicle: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = 'Database error (prepare delete): ' . $mysqli->error;
            }
        }
    } else {
        $response['message'] = 'Invalid action specified.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
$mysqli->close();
?>
