<?php
// This partial is included in areacliente.php when tab=overview or no tab is set.
// Ensure $mysqli is available from config.php, and user is logged in (checked by areacliente.php).
// require_once '../config.php'; // Already included by areacliente.php
// require_once '../utils/functions.php'; // Already included by areacliente.php

$user_id = $_SESSION['user_id'];
$upcoming_bookings_count = 0;
$past_bookings_count = 0;
$total_vehicles = 0;

// Get count of upcoming bookings
$stmt_upcoming = $mysqli->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ? AND booking_date >= CURDATE() AND status NOT IN ('completed', 'cancelled', 'no-show')");
if ($stmt_upcoming) {
    $stmt_upcoming->bind_param("i", $user_id);
    $stmt_upcoming->execute();
    $result = $stmt_upcoming->get_result()->fetch_assoc();
    $upcoming_bookings_count = $result['count'] ?? 0;
    $stmt_upcoming->close();
}

// Get count of past bookings
$stmt_past = $mysqli->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ? AND (booking_date < CURDATE() OR status IN ('completed', 'cancelled', 'no-show'))");
if ($stmt_past) {
    $stmt_past->bind_param("i", $user_id);
    $stmt_past->execute();
    $result = $stmt_past->get_result()->fetch_assoc();
    $past_bookings_count = $result['count'] ?? 0;
    $stmt_past->close();
}

// Get count of user's vehicles
$stmt_vehicles = $mysqli->prepare("SELECT COUNT(*) as count FROM vehicles WHERE user_id = ?");
if ($stmt_vehicles) {
    $stmt_vehicles->bind_param("i", $user_id);
    $stmt_vehicles->execute();
    $result = $stmt_vehicles->get_result()->fetch_assoc();
    $total_vehicles = $result['count'] ?? 0;
    $stmt_vehicles->close();
}

$latest_upcoming_booking = null;
$sql_latest_upcoming = "SELECT b.id, b.booking_date, b.booking_time, s.name as service_name, b.status
                        FROM bookings b
                        JOIN services s ON b.service_id = s.id
                        WHERE b.user_id = ? AND b.booking_date >= CURDATE() AND b.status NOT IN ('completed', 'cancelled', 'no-show')
                        ORDER BY b.booking_date ASC, b.booking_time ASC LIMIT 1";
if($stmt_latest = $mysqli->prepare($sql_latest_upcoming)){
    $stmt_latest->bind_param("i", $user_id);
    $stmt_latest->execute();
    $result_latest = $stmt_latest->get_result();
    if($row = $result_latest->fetch_assoc()){
        $latest_upcoming_booking = $row;
    }
    $stmt_latest->close();
}

?>
<div class="flex flex-col p-4 space-y-6">
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-white tracking-light text-2xl sm:text-3xl font-bold leading-tight">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <p class="text-[#cdc28e] text-sm font-normal leading-normal">Here's a quick overview of your account.</p>
        </div>
        <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>" class="flex min-w-[120px] max-w-[200px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#fcdd53] text-[#232010] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]">
            <span class="truncate">Book New Service</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-[#353017] p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Upcoming Bookings</h3>
            <p class="text-white text-3xl font-bold"><?php echo $upcoming_bookings_count; ?></p>
            <a href="<?php echo BASE_URL . '/areacliente.php?tab=bookings'; ?>" class="text-[#fcdd53] text-sm mt-2 inline-block hover:underline">View Upcoming</a>
        </div>
        <div class="bg-[#353017] p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Past Bookings</h3>
            <p class="text-white text-3xl font-bold"><?php echo $past_bookings_count; ?></p>
            <a href="<?php echo BASE_URL . '/areacliente.php?tab=bookings#past-bookings'; ?>" class="text-[#fcdd53] text-sm mt-2 inline-block hover:underline">View Past</a>
        </div>
        <div class="bg-[#353017] p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">My Vehicles</h3>
            <p class="text-white text-3xl font-bold"><?php echo $total_vehicles; ?></p>
            <a href="<?php echo BASE_URL . '/areacliente.php?tab=vehicles'; ?>" class="text-[#fcdd53] text-sm mt-2 inline-block hover:underline">Manage Vehicles</a>
        </div>
    </div>

    <!-- Next Upcoming Appointment -->
    <?php if ($latest_upcoming_booking): ?>
    <div class="bg-[#353017] p-6 rounded-lg shadow mt-6">
        <h3 class="text-white text-xl font-semibold mb-3">Your Next Appointment</h3>
        <div class="text-[#cdc28e] space-y-1">
            <p><span class="font-medium text-white">Service:</span> <?php echo htmlspecialchars($latest_upcoming_booking['service_name']); ?></p>
            <p><span class="font-medium text-white">Date:</span> <?php echo format_date_display($latest_upcoming_booking['booking_date']); ?></p>
            <p><span class="font-medium text-white">Time:</span> <?php echo format_time_display($latest_upcoming_booking['booking_time']); ?></p>
            <p><span class="font-medium text-white">Status:</span> <span class="capitalize px-2 py-1 text-xs rounded-full
                <?php echo $latest_upcoming_booking['status'] === 'confirmed' ? 'bg-green-600 text-white' : ($latest_upcoming_booking['status'] === 'pending' ? 'bg-yellow-500 text-black' : 'bg-gray-500 text-white'); ?>">
                <?php echo htmlspecialchars(str_replace('-', ' ', $latest_upcoming_booking['status'])); ?>
            </span></p>
        </div>
        <a href="<?php echo BASE_URL . '/areacliente.php?tab=bookings'; ?>" class="text-[#fcdd53] text-sm mt-4 inline-block hover:underline">View All Bookings</a>
    </div>
    <?php else: ?>
    <div class="bg-[#353017] p-6 rounded-lg shadow mt-6 text-center">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBTx7KH-1iv5nulO-ghbA2gLiQuGrRWbnfM8wf9Ne_urcl__YYHu0d56KXpB9YFYzCklKAfbUpYcxxGPDliIjTLEQMC1ogLygZ3ypj59hiff0DE3HP1JfE9P-S-2Z7f8XsuKoitkR_7Kl_S9iyh7GbMGuaXLS0Xj9M79nYwRLmThxUyNjq4GQcxJDaz8GIPc-olKawSdpJahG1vAAG4fbh_HaP7hH5hLuc-eCK6NDHCIMeyQKtkA00B9H-Ntnfn9Mx9V4eMEz2YtaY" alt="No bookings" class="mx-auto h-40 w-auto mb-4 opacity-50">
        <p class="text-white text-lg font-bold leading-tight">No upcoming bookings.</p>
        <p class="text-[#cdc28e] text-sm mb-4">Schedule your next vehicle inspection today.</p>
        <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>" class="flex w-full sm:w-auto max-w-xs mx-auto cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#fcdd53] text-[#232010] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]">
            <span class="truncate">Book a Service Now</span>
        </a>
    </div>
    <?php endif; ?>

</div>
