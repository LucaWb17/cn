<?php
require_once 'config.php';
require_once 'auth_check.php';
require_admin(); // Ensure only admin can access
require_once 'utils/functions.php'; // For send_email and other utilities

$response = ['success' => false, 'message' => '', 'errors' => []];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verify_csrf_token();
    // --- Form Data Collection and Basic Sanitization ---
    $service_id = isset($_POST['service_id']) ? (int)$_POST['service_id'] : null;
    $booking_date = isset($_POST['booking_date']) ? sanitize_input($_POST['booking_date']) : null;
    $booking_time = isset($_POST['booking_time']) ? sanitize_input($_POST['booking_time']) : null;
    $booking_status = isset($_POST['booking_status']) ? sanitize_input($_POST['booking_status']) : 'pending'; // Default status

    // Client type: 'existing_user' or 'new_guest'
    $client_type = isset($_POST['client_type']) ? $_POST['client_type'] : null;
    $existing_user_id = isset($_POST['existing_user_id']) ? (int)$_POST['existing_user_id'] : null;

    $guest_name = isset($_POST['guest_name']) ? sanitize_input($_POST['guest_name']) : null;
    $guest_email = isset($_POST['guest_email']) ? sanitize_input($_POST['guest_email']) : null;
    $guest_phone = isset($_POST['guest_phone']) ? sanitize_input($_POST['guest_phone']) : null;

    // Vehicle details
    $vehicle_make = isset($_POST['vehicle_make']) ? sanitize_input($_POST['vehicle_make']) : null;
    $vehicle_model = isset($_POST['vehicle_model']) ? sanitize_input($_POST['vehicle_model']) : null;
    $vehicle_year = isset($_POST['vehicle_year']) ? (int)$_POST['vehicle_year'] : null;
    $license_plate = isset($_POST['license_plate']) ? sanitize_input(strtoupper(str_replace(' ', '',$_POST['license_plate']))) : null;
    $selected_vehicle_id = isset($_POST['selected_vehicle_id']) && $_POST['selected_vehicle_id'] !== 'new' && !empty($_POST['selected_vehicle_id']) ? (int)$_POST['selected_vehicle_id'] : null;

    $notes = isset($_POST['notes']) ? sanitize_input($_POST['notes']) : null;

    $user_id_for_booking = null; // This will be the user_id if existing, or null for guest

    // --- Validation ---
    if (empty($service_id)) $response['errors']['service_id'] = "Please select a service.";
    if (empty($booking_date)) $response['errors']['booking_date'] = "Please select a date.";
    if (empty($booking_time)) $response['errors']['booking_time'] = "Please select a time.";
    if (empty($booking_status) || !in_array($booking_status, ['pending', 'confirmed', 'completed', 'cancelled', 'no-show'])) {
        $response['errors']['booking_status'] = "Invalid booking status.";
    }

    if ($client_type === 'existing_user') {
        if (empty($existing_user_id)) {
            $response['errors']['existing_user_id'] = "Please select an existing user or choose 'New Guest'.";
        } else {
            // Verify user exists
            $stmt_user = $mysqli->prepare("SELECT id, name, email FROM users WHERE id = ?");
            if ($stmt_user) {
                $stmt_user->bind_param("i", $existing_user_id);
                $stmt_user->execute();
                $result_user = $stmt_user->get_result();
                if ($user_data = $result_user->fetch_assoc()) {
                    $user_id_for_booking = $user_data['id'];
                    // For notifications, use these details
                    $guest_name = $user_data['name']; // Use actual user name
                    $guest_email = $user_data['email']; // Use actual user email
                } else {
                    $response['errors']['existing_user_id'] = "Selected user not found.";
                }
                $stmt_user->close();
            }
        }
    } elseif ($client_type === 'new_guest') {
        if (empty($guest_name)) $response['errors']['guest_name'] = "Guest name is required.";
        if (empty($guest_email) || !filter_var($guest_email, FILTER_VALIDATE_EMAIL)) $response['errors']['guest_email'] = "Valid guest email is required.";
        // Guest phone is optional for admin, can be made required
    } else {
        $response['errors']['client_type'] = "Please specify client type.";
    }

    // Vehicle validation (only if not selecting an existing vehicle of a registered user)
    if ($client_type === 'new_guest' || ($client_type === 'existing_user' && !$selected_vehicle_id) ) {
        if (empty($vehicle_make)) $response['errors']['vehicle_make'] = "Vehicle make is required.";
        if (empty($vehicle_model)) $response['errors']['vehicle_model'] = "Vehicle model is required.";
        if (empty($vehicle_year) || !is_numeric($vehicle_year) || $vehicle_year < 1900 || $vehicle_year > (date("Y") + 1)) {
            $response['errors']['vehicle_year'] = "Valid vehicle year is required.";
        }
        if (empty($license_plate)) $response['errors']['license_plate'] = "License plate is required.";
    } elseif ($client_type === 'existing_user' && $selected_vehicle_id) {
        // Fetch details of selected vehicle to store as snapshot
        $stmt_veh = $mysqli->prepare("SELECT make, model, year, license_plate FROM vehicles WHERE id = ? AND user_id = ?");
        if ($stmt_veh) {
            $stmt_veh->bind_param("ii", $selected_vehicle_id, $user_id_for_booking);
            $stmt_veh->execute();
            $result_veh = $stmt_veh->get_result();
            if ($vehicle_data = $result_veh->fetch_assoc()) {
                $vehicle_make = $vehicle_data['make'];
                $vehicle_model = $vehicle_data['model'];
                $vehicle_year = $vehicle_data['year'];
                $license_plate = $vehicle_data['license_plate'];
            } else {
                $response['errors']['selected_vehicle_id'] = "Selected vehicle not found or does not belong to the user.";
            }
            $stmt_veh->close();
        }
    }


    if (empty($response['errors'])) {
        $sql = "INSERT INTO bookings (user_id, guest_name, guest_email, guest_phone, service_id, vehicle_id, vehicle_make, vehicle_model, vehicle_year, vehicle_license_plate, booking_date, booking_time, status, notes, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        if ($stmt = $mysqli->prepare($sql)) {
            $vehicle_id_to_insert_db = ($client_type === 'existing_user' && $selected_vehicle_id) ? $selected_vehicle_id : null;
            // If it's a new guest, or existing user with new vehicle details, guest_name, guest_email, guest_phone are used.
            // If existing user, user_id_for_booking is set.
            $actual_guest_name = ($client_type === 'existing_user') ? null : $guest_name;
            $actual_guest_email = ($client_type === 'existing_user') ? null : $guest_email;
            $actual_guest_phone = ($client_type === 'existing_user') ? null : $guest_phone;


            $stmt->bind_param(
                "isssiisssssss",
                $user_id_for_booking,    // user_id (null if guest)
                $actual_guest_name,      // guest_name (null if registered user)
                $actual_guest_email,     // guest_email (null if registered user)
                $actual_guest_phone,     // guest_phone (null if registered user)
                $service_id,
                $vehicle_id_to_insert_db,// actual vehicle.id from vehicles table (null if new vehicle for guest, or new for user and not saved yet)
                $vehicle_make,           // Snapshot of make
                $vehicle_model,          // Snapshot of model
                $vehicle_year,           // Snapshot of year
                $license_plate,          // Snapshot of license
                $booking_date,
                $booking_time,
                $booking_status,
                $notes
            );

            if ($stmt->execute()) {
                $booking_id = $mysqli->insert_id;
                $response['success'] = true;
                $response['message'] = "Booking (ID: {$booking_id}) created successfully by admin.";

                // --- Send Email Notifications ---
                $service_name_email = "Service"; // Fallback
                $stmt_s = $mysqli->prepare("SELECT name FROM services WHERE id = ?");
                if($stmt_s){ $stmt_s->bind_param("i", $service_id); $stmt_s->execute(); $res_s = $stmt_s->get_result(); if($s_data = $res_s->fetch_assoc()){ $service_name_email = $s_data['name']; } $stmt_s->close(); }

                // Client/Guest name and email for notification
                $notify_client_name = ($client_type === 'existing_user' && isset($user_data['name'])) ? $user_data['name'] : $guest_name;
                $notify_client_email = ($client_type === 'existing_user' && isset($user_data['email'])) ? $user_data['email'] : $guest_email;

                // Admin Notification
                $admin_subject = "Admin Created Booking - ID: " . $booking_id;
                $admin_message_html = "<h2>Admin Created Booking</h2>
                                  <p>An administrator has created/modified a booking:</p>
                                  <ul>
                                    <li><strong>Booking ID:</strong> " . $booking_id . "</li>
                                    <li><strong>Client Name:</strong> " . htmlspecialchars($notify_client_name) . "</li>
                                    <li><strong>Client Email:</strong> " . htmlspecialchars($notify_client_email) . "</li>
                                    <li><strong>Service:</strong> " . htmlspecialchars($service_name_email) . "</li>
                                    <li><strong>Date:</strong> " . htmlspecialchars(format_date_display($booking_date)) . "</li>
                                    <li><strong>Time:</strong> " . htmlspecialchars(format_time_display($booking_time)) . "</li>
                                    <li><strong>Status:</strong> " . htmlspecialchars(ucfirst($booking_status)) . "</li>
                                    <li><strong>Vehicle:</strong> " . htmlspecialchars($vehicle_make . ' ' . $vehicle_model . ' (' . $vehicle_year . ') - ' . $license_plate) . "</li>
                                    <li><strong>Notes:</strong> " . nl2br(htmlspecialchars($notes ?? 'N/A')) . "</li>
                                  </ul>";
                send_email(ADMIN_EMAIL, $admin_subject, $admin_message_html, FROM_EMAIL, FROM_NAME);

                // Client Notification (if email is available)
                if (!empty($notify_client_email)) {
                    $client_subject = "Your Booking Details (Admin Update) - ID: " . $booking_id;
                    $client_message_html = "<h2>Booking Details Updated/Created by Admin</h2>
                                       <p>Dear " . htmlspecialchars($notify_client_name) . ",</p>
                                       <p>An administrator has created or updated a booking for you. The details are as follows:</p>
                                       <ul>
                                         <li><strong>Booking ID:</strong> " . $booking_id . "</li>
                                         <li><strong>Service:</strong> " . htmlspecialchars($service_name_email) . "</li>
                                         <li><strong>Date:</strong> " . htmlspecialchars(format_date_display($booking_date)) . "</li>
                                         <li><strong>Time:</strong> " . htmlspecialchars(format_time_display($booking_time)) . "</li>
                                         <li><strong>Status:</strong> " . htmlspecialchars(ucfirst($booking_status)) . "</li>
                                         <li><strong>Vehicle:</strong> " . htmlspecialchars($vehicle_make . ' ' . $vehicle_model . ' (' . $vehicle_year . ') - ' . $license_plate) . "</li>
                                         <li><strong>Notes:</strong> " . nl2br(htmlspecialchars($notes ?? 'N/A')) . "</li>
                                       </ul>
                                       <p>If you have any questions, please contact us.</p>
                                       <p>Thank you,<br>" . FROM_NAME . "</p>";
                    send_email($notify_client_email, $client_subject, $client_message_html, FROM_EMAIL, FROM_NAME);
                }

            } else {
                $response['message'] = "Booking creation failed: " . $stmt->error;
                $response['errors']['database'] = $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = "Database error (prepare statement): " . $mysqli->error;
            $response['errors']['database'] = $mysqli->error;
        }
    } else {
        $response['message'] = "Please correct the errors below.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

header('Content-Type: application/json');
echo json_encode($response);
$mysqli->close();
?>
