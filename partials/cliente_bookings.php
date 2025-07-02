<?php
// This partial is included in areacliente.php when tab=bookings.
// Ensure $mysqli and $user_id are available.
// require_once '../config.php'; // Already included
// require_once '../utils/functions.php'; // Already included

// Fetch bookings (this logic could also be an AJAX call if preferred for SPA-like feel)
$user_id = $_SESSION['user_id'];
$bookings_data = ['upcoming' => [], 'past' => []];

// Fetch upcoming bookings
$sql_upcoming = "SELECT b.id, b.booking_date, b.booking_time, s.name as service_name,
                        b.vehicle_make, b.vehicle_model, b.vehicle_year, b.vehicle_license_plate, b.status, b.notes
                 FROM bookings b
                 JOIN services s ON b.service_id = s.id
                 WHERE b.user_id = ? AND b.booking_date >= CURDATE() AND b.status NOT IN ('completed', 'cancelled', 'no-show')
                 ORDER BY b.booking_date ASC, b.booking_time ASC";
if ($stmt_upcoming = $mysqli->prepare($sql_upcoming)) {
    $stmt_upcoming->bind_param("i", $user_id);
    if ($stmt_upcoming->execute()) {
        $result_upcoming = $stmt_upcoming->get_result();
        while ($row = $result_upcoming->fetch_assoc()) {
            $bookings_data['upcoming'][] = $row;
        }
    } else { /* Handle error */ }
    $stmt_upcoming->close();
}

// Fetch past bookings
$sql_past = "SELECT b.id, b.booking_date, b.booking_time, s.name as service_name,
                    b.vehicle_make, b.vehicle_model, b.vehicle_year, b.vehicle_license_plate, b.status, b.notes
             FROM bookings b
             JOIN services s ON b.service_id = s.id
             WHERE b.user_id = ? AND (b.booking_date < CURDATE() OR b.status IN ('completed', 'cancelled', 'no-show'))
             ORDER BY b.booking_date DESC, b.booking_time DESC";
if ($stmt_past = $mysqli->prepare($sql_past)) {
    $stmt_past->bind_param("i", $user_id);
    if ($stmt_past->execute()) {
        $result_past = $stmt_past->get_result();
        while ($row = $result_past->fetch_assoc()) {
            $bookings_data['past'][] = $row;
        }
    } else { /* Handle error */ }
    $stmt_past->close();
}

function get_status_badge_class($status) {
    switch (strtolower($status)) {
        case 'confirmed': return 'bg-green-500 text-white';
        case 'pending': return 'bg-yellow-400 text-black';
        case 'completed': return 'bg-blue-500 text-white';
        case 'cancelled': return 'bg-red-500 text-white';
        case 'no-show': return 'bg-gray-500 text-white';
        default: return 'bg-gray-400 text-black';
    }
}
?>
<div class="p-4 space-y-8">
    <div>
        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
            <h2 class="text-white text-2xl font-bold tracking-tight">Upcoming Bookings</h2>
            <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>" class="flex min-w-[120px] max-w-[200px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#fcdd53] text-[#232010] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]">
                <span class="truncate">Book New Service</span>
            </a>
        </div>
        <?php if (!empty($bookings_data['upcoming'])): ?>
            <div class="overflow-x-auto @container">
                <table class="min-w-full bg-[#353017] rounded-lg overflow-hidden">
                    <thead class="bg-[#4a4321]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider">Date & Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider hidden @[600px]:table-cell">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider hidden @[800px]:table-cell">Vehicle</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#4a4321]">
                        <?php foreach ($bookings_data['upcoming'] as $booking): ?>
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-white">
                                <?php echo format_date_display($booking['booking_date']); ?><br>
                                <span class="text-xs text-[#cdc28e]"><?php echo format_time_display($booking['booking_time']); ?></span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-[#cdc28e] hidden @[600px]:table-cell"><?php echo htmlspecialchars($booking['service_name']); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-[#cdc28e] hidden @[800px]:table-cell">
                                <?php echo htmlspecialchars($booking['vehicle_make'] . ' ' . $booking['vehicle_model'] . ' (' . $booking['vehicle_license_plate'] . ')'); ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo get_status_badge_class($booking['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $booking['status']))); ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-[#fcdd53]">
                                <button onclick="showBookingDetailsModal(<?php echo htmlspecialchars(json_encode($booking)); ?>)" class="hover:underline">View Details</button>
                                <?php if ($booking['status'] === 'pending' || $booking['status'] === 'confirmed'): ?>
                                    <!-- Add cancel button here if needed, linking to a cancel_booking.php script -->
                                    <!-- <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>" class="text-red-400 hover:underline ml-2">Cancel</a> -->
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-10 px-4 bg-[#353017] rounded-lg">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBTx7KH-1iv5nulO-ghbA2gLiQuGrRWbnfM8wf9Ne_urcl__YYHu0d56KXpB9YFYzCklKAfbUpYcxxGPDliIjTLEQMC1ogLygZ3ypj59hiff0DE3HP1JfE9P-S-2Z7f8XsuKoitkR_7Kl_S9iyh7GbMGuaXLS0Xj9M79nYwRLmThxUyNjq4GQcxJDaz8GIPc-olKawSdpJahG1vAAG4fbh_HaP7hH5hLuc-eCK6NDHCIMeyQKtkA00B9H-Ntnfn9Mx9V4eMEz2YtaY" alt="No upcoming bookings" class="mx-auto h-32 w-auto mb-4 opacity-60">
                <p class="text-white text-lg font-semibold">No upcoming bookings.</p>
                <p class="text-[#cdc28e] text-sm">Ready for your next service? Book an appointment now.</p>
            </div>
        <?php endif; ?>
    </div>

    <div id="past-bookings">
        <h2 class="text-white text-2xl font-bold tracking-tight mb-4">Past Bookings</h2>
        <?php if (!empty($bookings_data['past'])): ?>
            <div class="overflow-x-auto @container">
                <table class="min-w-full bg-[#353017] rounded-lg overflow-hidden">
                    <thead class="bg-[#4a4321]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider">Date & Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider hidden @[600px]:table-cell">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider hidden @[800px]:table-cell">Vehicle</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#cdc28e] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#4a4321]">
                        <?php foreach ($bookings_data['past'] as $booking): ?>
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-white">
                                <?php echo format_date_display($booking['booking_date']); ?><br>
                                <span class="text-xs text-[#cdc28e]"><?php echo format_time_display($booking['booking_time']); ?></span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-[#cdc28e] hidden @[600px]:table-cell"><?php echo htmlspecialchars($booking['service_name']); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-[#cdc28e] hidden @[800px]:table-cell">
                                <?php echo htmlspecialchars($booking['vehicle_make'] . ' ' . $booking['vehicle_model'] . ' (' . $booking['vehicle_license_plate'] . ')'); ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo get_status_badge_class($booking['status']); ?>">
                                   <?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $booking['status']))); ?>
                                </span>
                            </td>
                             <td class="px-4 py-4 whitespace-nowrap text-sm text-[#fcdd53]">
                                <button onclick="showBookingDetailsModal(<?php echo htmlspecialchars(json_encode($booking)); ?>)" class="hover:underline">View Details</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-[#cdc28e] text-center py-6">No past bookings found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-[#232010] p-6 rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-white">Booking Details</h3>
            <button onclick="closeBookingDetailsModal()" class="text-white hover:text-gray-300 text-2xl">&times;</button>
        </div>
        <div id="modalContent" class="text-[#cdc28e] space-y-2 text-sm">
            <!-- Details will be populated by JavaScript -->
        </div>
        <div class="mt-6 text-right">
            <button onclick="closeBookingDetailsModal()" class="px-4 py-2 bg-[#4a4321] text-white rounded-lg hover:bg-[#5f552a]">Close</button>
        </div>
    </div>
</div>

<script>
function showBookingDetailsModal(booking) {
    const modal = document.getElementById('bookingDetailsModal');
    const content = document.getElementById('modalContent');

    let vehicleInfo = 'N/A';
    if(booking.vehicle_make && booking.vehicle_model && booking.vehicle_license_plate) {
        vehicleInfo = `${booking.vehicle_make} ${booking.vehicle_model} (${booking.vehicle_year || 'N/A'}) - ${booking.vehicle_license_plate}`;
    }

    let notesInfo = booking.notes ? `<p><strong>Notes:</strong> ${escapeHtml(booking.notes)}</p>` : '<p><strong>Notes:</strong> None</p>';

    content.innerHTML = `
        <p><strong>Service:</strong> ${escapeHtml(booking.service_name)}</p>
        <p><strong>Date:</strong> ${formatDate(booking.booking_date)}</p>
        <p><strong>Time:</strong> ${formatTime(booking.booking_time)}</p>
        <p><strong>Vehicle:</strong> ${escapeHtml(vehicleInfo)}</p>
        <p><strong>Status:</strong> <span class="capitalize px-2 py-0.5 text-xs rounded-full ${getStatusClass(booking.status)}">${escapeHtml(booking.status.replace('-', ' '))}</span></p>
        ${notesInfo}
    `;
    modal.classList.remove('hidden');
}

function closeBookingDetailsModal() {
    document.getElementById('bookingDetailsModal').classList.add('hidden');
}

function escapeHtml(unsafe) {
    if (unsafe === null || typeof unsafe === 'undefined') return '';
    return unsafe
         .toString()
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString + 'T00:00:00').toLocaleDateString(undefined, options); // Ensure date is parsed as local
}

function formatTime(timeString) {
    if (!timeString) return 'N/A';
    // Assuming timeString is HH:MM:SS or HH:MM
    const [hours, minutes] = timeString.split(':');
    const date = new Date();
    date.setHours(hours, minutes, 0);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
}
function getStatusClass(status) {
    status = status.toLowerCase();
    if (status === 'confirmed') return 'bg-green-500 text-white';
    if (status === 'pending') return 'bg-yellow-400 text-black';
    if (status === 'completed') return 'bg-blue-500 text-white';
    if (status === 'cancelled') return 'bg-red-500 text-white';
    if (status === 'no-show') return 'bg-gray-500 text-white';
    return 'bg-gray-400 text-black';
}

// Handle hash for past bookings anchor
if(window.location.hash === "#past-bookings") {
    const pastBookingsElement = document.getElementById('past-bookings');
    if(pastBookingsElement) {
        pastBookingsElement.scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
<style>
    /* Additional styling for the modal if Tailwind classes are not enough */
    #bookingDetailsModal strong {
        color: #f0e68c; /* Khaki like color for emphasis */
    }
</style>
