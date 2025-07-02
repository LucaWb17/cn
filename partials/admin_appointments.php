<?php
// This partial is included in dashboardAdmin.php when tab=appointments
// Ensure $mysqli is available (from config.php)
// Ensure user is admin (checked by dashboardAdmin.php)
// require_once '../config.php'; // Already included by dashboardAdmin.php
// require_once '../utils/functions.php'; // Already included
?>
<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-white text-2xl sm:text-3xl font-bold tracking-tight">Manage Appointments</h2>
        <a href="<?php echo BASE_URL . '/dashboardAdmin.php?tab=add_booking'; ?>" class="bg-[#fcdd53] text-[#232010] hover:bg-[#fadc70] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
            Add New Appointment
        </a>
    </div>

    <!-- Filters could go here: by date range, by status, by client -->
    <div id="appointmentsTableContainer" class="bg-[#353017] p-2 sm:p-4 rounded-lg shadow @container">
        <div class="overflow-x-auto">
            <table id="appointmentsTable" class="min-w-full divide-y divide-[#4a4321]">
                <thead class="bg-[#4a4321]">
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">ID</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white hidden @[600px]:table-cell">Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Time</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Client</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white hidden @[800px]:table-cell">Service</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white hidden @[1000px]:table-cell">Vehicle</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Status</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#4a4321] text-gray-300" id="appointmentsTableBody">
                    <!-- Rows will be populated by JavaScript -->
                    <tr><td colspan="8" class="text-center p-8 text-gray-400">Loading appointments...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Booking Details Modal (similar to cliente_bookings but can be admin specific) -->
<div id="adminBookingDetailsModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 hidden z-[100]">
    <div class="bg-[#232010] p-6 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto border border-[#4a4321]">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-white">Booking Details</h3>
            <button onclick="closeAdminBookingDetailsModal()" class="text-white hover:text-gray-300 text-2xl">&times;</button>
        </div>
        <div id="adminModalContent" class="text-[#cdc28e] space-y-2 text-sm">
            <!-- Details will be populated by JavaScript -->
        </div>
        <div class="mt-6 text-right">
            <button onclick="closeAdminBookingDetailsModal()" class="px-4 py-2 bg-[#4a4321] text-white rounded-lg hover:bg-[#5f552a]">Close</button>
        </div>
    </div>
</div>


<script>
const statusColors = {
    pending: 'bg-yellow-500 text-black',
    confirmed: 'bg-green-500 text-white',
    completed: 'bg-blue-500 text-white',
    cancelled: 'bg-red-500 text-white',
    'no-show': 'bg-gray-600 text-white'
};

function fetchAppointments() {
    const tableBody = document.getElementById('appointmentsTableBody');
    tableBody.innerHTML = '<tr><td colspan="8" class="text-center p-8 text-gray-400">Loading appointments...</td></tr>';

    fetch('<?php echo BASE_URL . "/get_all_bookings.php"; ?>')
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = ''; // Clear loading message
            if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="8" class="text-center p-8 text-red-400">Error: ${data.error}</td></tr>`;
                return;
            }
            if (data.data && data.data.length > 0) {
                data.data.forEach(booking => {
                    const row = tableBody.insertRow();
                    row.className = `booking-row-${booking.id}`;
                    row.innerHTML = `
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">${booking.id}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap hidden @[600px]:table-cell">${formatDateForDisplay(booking.booking_date_formatted)}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">${formatTimeForDisplay(booking.booking_time_formatted)}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm">
                            <div class="font-medium text-white">${escapeHtml(booking.client_name)}</div>
                            <div class="text-gray-400 text-xs">${escapeHtml(booking.client_email)}</div>
                        </td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap hidden @[800px]:table-cell">${escapeHtml(booking.service_name)}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap hidden @[1000px]:table-cell">
                            ${escapeHtml(booking.vehicle_make || '')} ${escapeHtml(booking.vehicle_model || '')} (${escapeHtml(booking.vehicle_year || 'N/A')})
                            <br><span class="text-xs text-gray-400">${escapeHtml(booking.vehicle_license_plate || '')}</span>
                        </td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">
                            <select id="status-${booking.id}" class="status-dropdown text-xs p-1.5 rounded-md border-gray-600 bg-[#232010] text-white focus:ring-[#fcdd53] focus:border-[#fcdd53]" data-booking-id="${booking.id}" data-initial-status="${booking.status}">
                                <option value="pending" ${booking.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="confirmed" ${booking.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                <option value="completed" ${booking.status === 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="cancelled" ${booking.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                <option value="no-show" ${booking.status === 'no-show' ? 'selected' : ''}>No-Show</option>
                            </select>
                            <span id="status-badge-${booking.id}" class="mt-1 capitalize px-2 py-0.5 text-xs rounded-full ${statusColors[booking.status] || 'bg-gray-400 text-black'}">${escapeHtml(booking.status.replace('-', ' '))}</span>
                        </td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">
                            <button onclick='showAdminBookingDetailsModal(${JSON.stringify(booking)})' class="text-[#fcdd53] hover:text-yellow-300 mr-2">Details</button>
                            <button onclick='deleteBooking(${booking.id})' class="text-red-400 hover:text-red-300">Delete</button>
                        </td>
                    `;
                     // Add event listener after row is in DOM
                    setTimeout(() => { // Ensure element exists
                        const statusDropdown = document.getElementById(`status-${booking.id}`);
                        if (statusDropdown) {
                            statusDropdown.addEventListener('change', handleStatusChange);
                        }
                    }, 0);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center p-8 text-gray-400">No appointments found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching appointments:', error);
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center p-8 text-red-400">Failed to load appointments.</td></tr>`;
        });
}

function handleStatusChange(event) {
    const bookingId = event.target.dataset.bookingId;
    const newStatus = event.target.value;
    const initialStatus = event.target.dataset.initialStatus;
    const statusBadge = document.getElementById(`status-badge-${bookingId}`);

    if (newStatus === initialStatus) return; // No change

    if (!confirm(`Are you sure you want to change the status of booking #${bookingId} to "${newStatus}"?`)) {
        event.target.value = initialStatus; // Revert dropdown if cancelled
        return;
    }

    const formData = new FormData();
    formData.append('action', 'update_status');
    formData.append('booking_id', bookingId);
    formData.append('status', newStatus);

    fetch('<?php echo BASE_URL . "/manage_bookings.php"; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // alert(data.message); // Or use a less intrusive notification
            event.target.dataset.initialStatus = newStatus; // Update initial status for next change
            if(statusBadge) {
                statusBadge.textContent = newStatus.replace('-', ' ');
                statusBadge.className = `mt-1 capitalize px-2 py-0.5 text-xs rounded-full ${statusColors[newStatus] || 'bg-gray-400 text-black'}`;
            }
             // Optional: Show a temporary success message on the page
            showFlashMessage(data.message, 'success');
        } else {
            // alert('Error: ' + data.message);
            event.target.value = initialStatus; // Revert dropdown on error
             showFlashMessage('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        // alert('An unexpected error occurred.');
        event.target.value = initialStatus; // Revert dropdown on error
        showFlashMessage('An unexpected error occurred while updating status.', 'error');
    });
}

function deleteBooking(bookingId) {
    if (!confirm(`Are you sure you want to permanently delete booking #${bookingId}? This action cannot be undone.`)) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete_booking');
    formData.append('booking_id', bookingId);

    fetch('<?php echo BASE_URL . "/manage_bookings.php"; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // alert(data.message);
            // Remove the row from the table
            const rowToRemove = document.querySelector(`.booking-row-${bookingId}`);
            if (rowToRemove) {
                rowToRemove.remove();
            }
            // If no rows left, show "No appointments" message
            if (document.getElementById('appointmentsTableBody').rows.length === 0) {
                 document.getElementById('appointmentsTableBody').innerHTML = '<tr><td colspan="8" class="text-center p-8 text-gray-400">No appointments found.</td></tr>';
            }
            showFlashMessage(data.message, 'success');
        } else {
            // alert('Error: ' + data.message);
            showFlashMessage('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting booking:', error);
        // alert('An unexpected error occurred while deleting the booking.');
        showFlashMessage('An unexpected error occurred while deleting the booking.', 'error');
    });
}

function showAdminBookingDetailsModal(booking) {
    const modal = document.getElementById('adminBookingDetailsModal');
    const content = document.getElementById('adminModalContent');

    let vehicleInfo = 'N/A';
    if(booking.vehicle_make && booking.vehicle_model) { // License plate might be null for older data
        vehicleInfo = `${booking.vehicle_make} ${booking.vehicle_model} (${booking.vehicle_year || 'N/A'})` +
                      (booking.vehicle_license_plate ? ` - ${booking.vehicle_license_plate}` : '');
    }

    let notesInfo = booking.notes ? `<p><strong>Notes:</strong> ${nl2br(escapeHtml(booking.notes))}</p>` : '<p><strong>Notes:</strong> None</p>';

    content.innerHTML = `
        <p><strong>Booking ID:</strong> ${booking.id}</p>
        <p><strong>Client:</strong> ${escapeHtml(booking.client_name)} (${escapeHtml(booking.client_email)})</p>
        <p><strong>Service:</strong> ${escapeHtml(booking.service_name)}</p>
        <p><strong>Date:</strong> ${formatDateForDisplay(booking.booking_date_formatted)}</p>
        <p><strong>Time:</strong> ${formatTimeForDisplay(booking.booking_time_formatted)}</p>
        <p><strong>Vehicle:</strong> ${escapeHtml(vehicleInfo)}</p>
        <p><strong>Status:</strong> <span class="capitalize px-2 py-0.5 text-xs rounded-full ${statusColors[booking.status] || 'bg-gray-400 text-black'}">${escapeHtml(booking.status.replace('-', ' '))}</span></p>
        ${notesInfo}
    `;
    modal.classList.remove('hidden');
}

function closeAdminBookingDetailsModal() {
    document.getElementById('adminBookingDetailsModal').classList.add('hidden');
}

function escapeHtml(unsafe) {
    if (unsafe === null || typeof unsafe === 'undefined') return '';
    return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
function nl2br(str) {
    if (typeof str === 'undefined' || str === null) return '';
    return str.replace(/(?:\r\n|\r|\n)/g, '<br>');
}

function formatDateForDisplay(dateStr) { // Expects YYYY-MM-DD
    if (!dateStr) return 'N/A';
    const [year, month, day] = dateStr.split('-');
    const dateObj = new Date(year, month - 1, day);
    return dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function formatTimeForDisplay(timeStr) { // Expects HH:MM
    if (!timeStr) return 'N/A';
    let [hours, minutes] = timeStr.split(':');
    hours = parseInt(hours, 10);
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes.padStart(2, '0');
    return `${hours}:${minutes} ${ampm}`;
}

// Function to display flash messages (can be moved to a global JS file if used elsewhere)
function showFlashMessage(message, type = 'info') {
    const container = document.querySelector('.layout-content-container > .p-4.sm\\:p-6.space-y-6'); // Adjust selector if needed
    if (!container) return;

    const existingFlash = container.querySelector('.flash-message-dynamic');
    if(existingFlash) existingFlash.remove();

    const flashDiv = document.createElement('div');
    flashDiv.className = `flash-message-dynamic p-4 mb-4 text-sm rounded-lg ${type === 'success' ? 'bg-green-700/80 text-green-200 border border-green-600' : (type === 'error' ? 'bg-red-700/80 text-red-200 border border-red-600' : 'bg-blue-700/80 text-blue-200 border border-blue-600')}`;
    flashDiv.setAttribute('role', 'alert');
    flashDiv.textContent = message;

    container.insertBefore(flashDiv, container.firstChild);

    setTimeout(() => {
        flashDiv.style.opacity = '0';
        flashDiv.style.transition = 'opacity 0.5s ease-out';
        setTimeout(() => flashDiv.remove(), 500);
    }, 3000);
}


document.addEventListener('DOMContentLoaded', fetchAppointments);
</script>
<style>
    /* Ensure dropdown arrow is visible in dark mode */
    .status-dropdown {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>
