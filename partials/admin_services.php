<?php
// Questo partial è incluso in dashboardAdmin.php per il tab 'services'
// Assicura che $mysqli sia disponibile (da config.php)
// Assicura che l'utente sia admin (controllato da dashboardAdmin.php)
?>
<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-white text-2xl sm:text-3xl font-bold tracking-tight">Manage Services</h2>
        <button onclick="openServiceModal()" class="bg-[#fcdd53] text-[#232010] hover:bg-[#fadc70] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
            Add New Service
        </button>
    </div>

    <div id="servicesTableContainer" class="bg-[#353017] p-2 sm:p-4 rounded-lg shadow @container">
        <div class="overflow-x-auto">
            <table id="servicesTable" class="min-w-full divide-y divide-[#4a4321]">
                <thead class="bg-[#4a4321]">
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">ID</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Name</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white hidden @[700px]:table-cell">Description</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Price</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white hidden @[500px]:table-cell">Duration (mins)</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#4a4321] text-gray-300" id="servicesTableBody">
                    <!-- Rows will be populated by JavaScript -->
                    <tr><td colspan="6" class="text-center p-8 text-gray-400">Loading services...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="serviceManagementFlashMessage" class="fixed bottom-5 right-5 z-[100]"></div>
</div>

<!-- Service Modal (Add/Edit) -->
<div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 hidden z-[100]">
    <div class="bg-[#232010] p-6 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto border border-[#4a4321]">
        <form id="serviceForm" class="space-y-4">
            <input type="hidden" name="service_id" id="service_id_modal">
            <input type="hidden" name="action" id="service_action_modal">

            <h3 id="serviceModalTitle" class="text-xl font-semibold text-white mb-4">Add New Service</h3>
            <div id="serviceFormMessageModal" class="hidden p-3 mb-2 rounded-md text-sm"></div>

            <div>
                <label for="service_name_modal" class="block text-sm font-medium text-[#cdc28e] mb-1">Service Name</label>
                <input type="text" name="name" id="service_name_modal" required
                       class="mt-1 block w-full rounded-md bg-[#353017] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring-[#fcdd53] p-3">
            </div>
            <div>
                <label for="service_description_modal" class="block text-sm font-medium text-[#cdc28e] mb-1">Description</label>
                <textarea name="description" id="service_description_modal" rows="3"
                          class="mt-1 block w-full rounded-md bg-[#353017] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring-[#fcdd53] p-3"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="service_price_modal" class="block text-sm font-medium text-[#cdc28e] mb-1">Price ($)</label>
                    <input type="number" name="price" id="service_price_modal" required step="0.01" min="0"
                           class="mt-1 block w-full rounded-md bg-[#353017] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring-[#fcdd53] p-3">
                </div>
                <div>
                    <label for="service_duration_modal" class="block text-sm font-medium text-[#cdc28e] mb-1">Duration (minutes)</label>
                    <input type="number" name="duration" id="service_duration_modal" required min="5" step="5"
                           class="mt-1 block w-full rounded-md bg-[#353017] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring-[#fcdd53] p-3">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeServiceModal()" class="px-4 py-2 text-sm font-medium text-gray-300 bg-[#4a4321] rounded-md hover:bg-[#5f552a]">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-[#232010] bg-[#fcdd53] rounded-md hover:bg-[#fadc70]">Save Service</button>
            </div>
        </form>
    </div>
</div>

<script>
function escapeHtmlService(unsafe) {
    if (unsafe === null || typeof unsafe === 'undefined') return '';
    return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

function fetchServices() {
    const tableBody = document.getElementById('servicesTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center p-8 text-gray-400">Loading services...</td></tr>';

    fetch('<?php echo BASE_URL . "/manage_services.php"; ?>') // GET request by default
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = '';
            if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="6" class="text-center p-8 text-red-400">Error: ${escapeHtmlService(data.error)}</td></tr>`;
                return;
            }
            if (data.data && data.data.length > 0) {
                data.data.forEach(service => {
                    const row = tableBody.insertRow();
                    row.className = `service-row-${service.id}`;
                    row.innerHTML = `
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">${service.id}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap font-medium text-white">${escapeHtmlService(service.name)}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm text-gray-400 hidden @[700px]:table-cell max-w-xs truncate" title="${escapeHtmlService(service.description)}">${escapeHtmlService(service.description ? service.description.substring(0, 50) + (service.description.length > 50 ? '...' : '') : 'N/A')}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">$${parseFloat(service.price).toFixed(2)}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap hidden @[500px]:table-cell">${service.duration}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">
                            <button onclick='openServiceModal(${JSON.stringify(service)})' class="text-[#fcdd53] hover:text-yellow-300 mr-2">Edit</button>
                            <button onclick='deleteService(${service.id})' class="text-red-400 hover:text-red-300">Delete</button>
                        </td>
                    `;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center p-8 text-gray-400">No services found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching services:', error);
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center p-8 text-red-400">Failed to load services.</td></tr>`;
        });
}

function openServiceModal(service = null) {
    const modal = document.getElementById('serviceModal');
    const form = document.getElementById('serviceForm');
    const title = document.getElementById('serviceModalTitle');
    const actionInput = document.getElementById('service_action_modal');
    const idInput = document.getElementById('service_id_modal');
    const formMessage = document.getElementById('serviceFormMessageModal');

    form.reset();
    formMessage.textContent = '';
    formMessage.className = 'hidden p-3 mb-2 rounded-md text-sm';

    if (service) {
        title.textContent = 'Edit Service';
        actionInput.value = 'edit';
        idInput.value = service.id;
        document.getElementById('service_name_modal').value = service.name;
        document.getElementById('service_description_modal').value = service.description || '';
        document.getElementById('service_price_modal').value = parseFloat(service.price).toFixed(2);
        document.getElementById('service_duration_modal').value = service.duration;
    } else {
        title.textContent = 'Add New Service';
        actionInput.value = 'add';
        idInput.value = '';
    }
    modal.classList.remove('hidden');
}

function closeServiceModal() {
    document.getElementById('serviceModal').classList.add('hidden');
}

document.getElementById('serviceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const formMessage = document.getElementById('serviceFormMessageModal');
    formMessage.textContent = 'Processing...';
    formMessage.className = 'p-3 mb-2 rounded-md text-sm bg-yellow-600/30 border border-yellow-500 text-yellow-300';
    formMessage.classList.remove('hidden');

    fetch('<?php echo BASE_URL . "/manage_services.php"; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            formMessage.textContent = data.message;
            formMessage.className = 'p-3 mb-2 rounded-md text-sm bg-green-600/30 border border-green-500 text-green-300';
            fetchServices();
            setTimeout(closeServiceModal, 1500);
            showServiceManagementFlash(data.message, 'success');
        } else {
            formMessage.textContent = 'Error: ' + (data.message || 'Could not save service.');
            formMessage.className = 'p-3 mb-2 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        formMessage.textContent = 'An unexpected network error occurred.';
        formMessage.className = 'p-3 mb-2 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
    });
});

function deleteService(serviceId) {
    if (!confirm(`Are you sure you want to delete service ID ${serviceId}? This might affect existing bookings if not handled by backend logic.`)) {
        return;
    }
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('service_id', serviceId);

    fetch('<?php echo BASE_URL . "/manage_services.php"; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showServiceManagementFlash(data.message, 'success');
            fetchServices(); // Refresh the list
        } else {
            showServiceManagementFlash('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting service:', error);
        showServiceManagementFlash('An unexpected network error occurred.', 'error');
    });
}

function showServiceManagementFlash(message, type = 'info') {
    const flashContainer = document.getElementById('serviceManagementFlashMessage');
    if (!flashContainer) return;

    const flashDiv = document.createElement('div');
    flashDiv.className = `p-3 mb-2 text-sm rounded-lg shadow-lg ${type === 'success' ? 'bg-green-700 text-green-100' : (type === 'error' ? 'bg-red-700 text-red-100' : 'bg-blue-700 text-blue-100')}`;
    flashDiv.setAttribute('role', 'alert');
    flashDiv.textContent = message;

    flashContainer.innerHTML = ''; // Clear previous messages
    flashContainer.appendChild(flashDiv);

    setTimeout(() => {
        flashDiv.style.opacity = '0';
        flashDiv.style.transition = 'opacity 0.5s ease-out';
        setTimeout(() => flashDiv.remove(), 500);
    }, 4000);
}

document.addEventListener('DOMContentLoaded', function() {
    fetchServices();
    // Check for hash to open modal directly (e.g., from a "Add New Service" link elsewhere)
    if(window.location.hash === '#add') {
        openServiceModal();
    }
});
</script>
