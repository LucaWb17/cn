<?php
// This partial is included in areacliente.php when tab=vehicles.
// Ensure $mysqli and $user_id are available.
// require_once '../config.php'; // Already included
// require_once '../utils/functions.php'; // Already included

$user_id = $_SESSION['user_id'];
$vehicles = get_user_vehicles($mysqli, $user_id);
?>
<div class="p-4 space-y-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <h2 class="text-white text-2xl font-bold tracking-tight">My Vehicles</h2>
        <button onclick="openVehicleModal()" class="flex items-center min-w-[120px] max-w-[200px] cursor-pointer justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#fcdd53] text-[#232010] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
              <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            <span class="truncate">Add New Vehicle</span>
        </button>
    </div>

    <div id="vehicleList" class="space-y-4">
        <?php if (!empty($vehicles)): ?>
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="bg-[#353017] p-4 rounded-lg shadow flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 vehicle-item" data-id="<?php echo $vehicle['id']; ?>">
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            <?php echo htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']); ?>
                            <span class="text-sm text-[#cdc28e]">(<?php echo htmlspecialchars($vehicle['year']); ?>)</span>
                        </h3>
                        <p class="text-sm text-[#cdc28e]">License Plate: <span class="font-medium text-white"><?php echo htmlspecialchars($vehicle['license_plate']); ?></span></p>
                    </div>
                    <div class="flex gap-2 mt-2 sm:mt-0 flex-shrink-0">
                        <button onclick='openVehicleModal(<?php echo json_encode($vehicle); ?>)' class="text-xs px-3 py-1.5 bg-[#4a4321] text-white rounded-md hover:bg-[#5f552a] transition-colors">Edit</button>
                        <button onclick='deleteVehicle(<?php echo $vehicle['id']; ?>)' class="text-xs px-3 py-1.5 bg-red-700 text-white rounded-md hover:bg-red-600 transition-colors">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div id="noVehiclesMessage" class="text-center py-10 px-4 bg-[#353017] rounded-lg">
                 <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-[#6a5f2f] mx-auto mb-3" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
                <p class="text-white text-lg font-semibold">No vehicles found.</p>
                <p class="text-[#cdc28e] text-sm">Add your vehicles to make booking appointments faster.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Vehicle Modal -->
<div id="vehicleModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-[#232010] p-6 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <form id="vehicleForm" class="space-y-4">
            <input type="hidden" name="vehicle_id" id="vehicle_id">
            <input type="hidden" name="action" id="vehicle_action">

            <h3 id="vehicleModalTitle" class="text-xl font-semibold text-white mb-4">Add New Vehicle</h3>

            <div>
                <label for="make" class="block text-sm font-medium text-[#cdc28e]">Make</label>
                <input type="text" name="make" id="make" required class="mt-1 block w-full rounded-md bg-[#353017] border-[#6a5f2f] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3">
            </div>
            <div>
                <label for="model" class="block text-sm font-medium text-[#cdc28e]">Model</label>
                <input type="text" name="model" id="model" required class="mt-1 block w-full rounded-md bg-[#353017] border-[#6a5f2f] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3">
            </div>
            <div>
                <label for="year" class="block text-sm font-medium text-[#cdc28e]">Year</label>
                <input type="number" name="year" id="year" required min="1900" max="<?php echo date('Y') + 2; ?>" class="mt-1 block w-full rounded-md bg-[#353017] border-[#6a5f2f] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3">
            </div>
            <div>
                <label for="license_plate" class="block text-sm font-medium text-[#cdc28e]">License Plate</label>
                <input type="text" name="license_plate" id="license_plate" required class="mt-1 block w-full rounded-md bg-[#353017] border-[#6a5f2f] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3">
            </div>

            <div id="vehicleFormMessage" class="text-sm"></div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeVehicleModal()" class="px-4 py-2 text-sm font-medium text-gray-300 bg-[#4a4321] rounded-md hover:bg-[#5f552a]">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-[#232010] bg-[#fcdd53] rounded-md hover:bg-[#fadc70]">Save Vehicle</button>
            </div>
        </form>
    </div>
</div>

<script>
function openVehicleModal(vehicle = null) {
    const modal = document.getElementById('vehicleModal');
    const form = document.getElementById('vehicleForm');
    const title = document.getElementById('vehicleModalTitle');
    const actionInput = document.getElementById('vehicle_action');
    const idInput = document.getElementById('vehicle_id');
    const formMessage = document.getElementById('vehicleFormMessage');

    form.reset(); // Clear previous data
    formMessage.textContent = '';
    formMessage.className = 'text-sm';


    if (vehicle) {
        title.textContent = 'Edit Vehicle';
        actionInput.value = 'edit';
        idInput.value = vehicle.id;
        document.getElementById('make').value = vehicle.make;
        document.getElementById('model').value = vehicle.model;
        document.getElementById('year').value = vehicle.year;
        document.getElementById('license_plate').value = vehicle.license_plate;
    } else {
        title.textContent = 'Add New Vehicle';
        actionInput.value = 'add';
        idInput.value = '';
    }
    modal.classList.remove('hidden');
}

function closeVehicleModal() {
    document.getElementById('vehicleModal').classList.add('hidden');
}

document.getElementById('vehicleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const formMessage = document.getElementById('vehicleFormMessage');
    formMessage.textContent = 'Processing...';
    formMessage.className = 'text-sm text-yellow-400';


    fetch('<?php echo BASE_URL . "/manage_vehicles.php"; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            formMessage.textContent = data.message;
            formMessage.className = 'text-sm text-green-400';
            // Refresh vehicle list or update UI
            loadVehicles(); // You'll need to implement this function or reload the page/section
            setTimeout(closeVehicleModal, 1500);
        } else {
            formMessage.textContent = 'Error: ' + data.message;
            formMessage.className = 'text-sm text-red-400';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        formMessage.textContent = 'An unexpected error occurred.';
        formMessage.className = 'text-sm text-red-400';
    });
});

function deleteVehicle(vehicleId) {
    if (!confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('vehicle_id', vehicleId);

    const formMessageContainer = document.querySelector('.layout-content-container > .p-4.space-y-6'); // Find a place to show general messages if needed

    fetch('<?php echo BASE_URL . "/manage_vehicles.php"; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // alert(data.message); // Or use a more integrated notification
            // Remove vehicle item from DOM
            const vehicleItem = document.querySelector(`.vehicle-item[data-id='${vehicleId}']`);
            if (vehicleItem) {
                vehicleItem.remove();
            }
            // Check if no vehicles are left
            if (document.querySelectorAll('.vehicle-item').length === 0) {
                document.getElementById('vehicleList').innerHTML = `
                 <div id="noVehiclesMessage" class="text-center py-10 px-4 bg-[#353017] rounded-lg">
                     <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-[#6a5f2f] mx-auto mb-3" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                    <p class="text-white text-lg font-semibold">No vehicles found.</p>
                    <p class="text-[#cdc28e] text-sm">Add your vehicles to make booking appointments faster.</p>
                </div>`;
            }
            // Optionally show a success message at the top
            if(formMessageContainer) {
                const successDiv = document.createElement('div');
                successDiv.className = 'flash-message success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                successDiv.setAttribute('role', 'alert');
                successDiv.textContent = data.message;
                formMessageContainer.insertBefore(successDiv, formMessageContainer.firstChild);
                setTimeout(() => successDiv.remove(), 3000);
            }

        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred while deleting the vehicle.');
    });
}

// Function to reload vehicles (can be more sophisticated, e.g., fetch and re-render)
function loadVehicles() {
    // For simplicity, we can just reload the vehicles tab content.
    // A true SPA feel would fetch JSON and update the DOM.
    // This will effectively reload the vehicles partial if areacliente.php handles tabs by re-including.
    // If not, a full page reload or AJAX based update is needed.
    // window.location.reload(); // Simplest, but full page reload.

    // AJAX based update:
    fetch('<?php echo BASE_URL . "/manage_vehicles.php"; ?>') // Assuming GET request to manage_vehicles.php returns list
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            const vehicleListDiv = document.getElementById('vehicleList');
            vehicleListDiv.innerHTML = ''; // Clear current list
            if (data.data.length > 0) {
                data.data.forEach(vehicle => {
                    const vehicleDiv = document.createElement('div');
                    vehicleDiv.className = 'bg-[#353017] p-4 rounded-lg shadow flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 vehicle-item';
                    vehicleDiv.dataset.id = vehicle.id;
                    vehicleDiv.innerHTML = `
                        <div>
                            <h3 class="text-lg font-semibold text-white">
                                ${escapeHtml(vehicle.make + ' ' + vehicle.model)}
                                <span class="text-sm text-[#cdc28e]">(${escapeHtml(vehicle.year)})</span>
                            </h3>
                            <p class="text-sm text-[#cdc28e]">License Plate: <span class="font-medium text-white">${escapeHtml(vehicle.license_plate)}</span></p>
                        </div>
                        <div class="flex gap-2 mt-2 sm:mt-0 flex-shrink-0">
                            <button onclick='openVehicleModal(${JSON.stringify(vehicle)})' class="text-xs px-3 py-1.5 bg-[#4a4321] text-white rounded-md hover:bg-[#5f552a] transition-colors">Edit</button>
                            <button onclick='deleteVehicle(${vehicle.id})' class="text-xs px-3 py-1.5 bg-red-700 text-white rounded-md hover:bg-red-600 transition-colors">Delete</button>
                        </div>
                    `;
                    vehicleListDiv.appendChild(vehicleDiv);
                });
                 const noMsg = document.getElementById('noVehiclesMessage');
                 if(noMsg) noMsg.remove();
            } else {
                vehicleListDiv.innerHTML = `
                 <div id="noVehiclesMessage" class="text-center py-10 px-4 bg-[#353017] rounded-lg">
                     <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-[#6a5f2f] mx-auto mb-3" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                    <p class="text-white text-lg font-semibold">No vehicles found.</p>
                    <p class="text-[#cdc28e] text-sm">Add your vehicles to make booking appointments faster.</p>
                </div>`;
            }
        }
    })
    .catch(error => console.error('Error reloading vehicles:', error));
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
</script>
