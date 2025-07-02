<?php
// This partial is included in dashboardAdmin.php when tab=add_booking
// Ensure $mysqli is available (from config.php)
// Ensure user is admin (checked by dashboardAdmin.php)

$all_services = get_all_services($mysqli);
$all_users = []; // Will be fetched by JS or passed if small number
$stmt_users = $mysqli->query("SELECT id, name, email FROM users ORDER BY name ASC");
if($stmt_users) {
    while($row = $stmt_users->fetch_assoc()){
        $all_users[] = $row;
    }
    $stmt_users->close();
}
?>
<div class="p-4 sm:p-6 space-y-6">
    <h2 class="text-white text-2xl sm:text-3xl font-bold tracking-tight">Add New Appointment</h2>

    <form id="adminAddBookingForm" class="bg-[#353017] p-4 sm:p-6 rounded-lg shadow space-y-6">
        <div id="adminBookingFormMessage" class="hidden p-3 rounded-md text-sm"></div>

        <!-- Client Information -->
        <fieldset class="border border-[#4a4321] p-4 rounded-md">
            <legend class="text-lg font-semibold text-white px-2">Client Information</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="client_type" class="block text-sm font-medium text-[#cdc28e] mb-1">Client Type</label>
                    <select name="client_type" id="client_type" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                        <option value="existing_user">Existing User</option>
                        <option value="new_guest">New Guest</option>
                    </select>
                </div>
            </div>
            <div id="existingUserSection" class="mt-4 space-y-4">
                <div>
                    <label for="existing_user_id" class="block text-sm font-medium text-[#cdc28e] mb-1">Select User</label>
                    <select name="existing_user_id" id="existing_user_id" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                        <option value="">-- Select User --</option>
                        <?php foreach($all_users as $user): ?>
                            <option value="<?php echo $user['id']; ?>" data-email="<?php echo htmlspecialchars($user['email']); ?>"><?php echo htmlspecialchars($user['name'] . ' (' . $user['email'] . ')'); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p id="error_existing_user_id" class="text-red-400 text-xs mt-1"></p>
                </div>
                 <div>
                    <label for="selected_vehicle_id" class="block text-sm font-medium text-[#cdc28e] mb-1">User's Vehicle (Optional)</label>
                    <select name="selected_vehicle_id" id="selected_vehicle_id" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]" disabled>
                        <option value="">-- Select Vehicle or Add New Below --</option>
                        <!-- Options will be populated by JS -->
                    </select>
                </div>
            </div>
            <div id="newGuestSection" class="hidden mt-4 space-y-4">
                <div>
                    <label for="guest_name" class="block text-sm font-medium text-[#cdc28e] mb-1">Guest Full Name</label>
                    <input type="text" name="guest_name" id="guest_name" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                    <p id="error_guest_name" class="text-red-400 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="guest_email" class="block text-sm font-medium text-[#cdc28e] mb-1">Guest Email</label>
                    <input type="email" name="guest_email" id="guest_email" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                    <p id="error_guest_email" class="text-red-400 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="guest_phone" class="block text-sm font-medium text-[#cdc28e] mb-1">Guest Phone (Optional)</label>
                    <input type="tel" name="guest_phone" id="guest_phone" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                </div>
            </div>
        </fieldset>

        <!-- Vehicle Information -->
        <fieldset id="vehicleDetailsSection" class="border border-[#4a4321] p-4 rounded-md">
            <legend class="text-lg font-semibold text-white px-2">Vehicle Information</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="vehicle_make" class="block text-sm font-medium text-[#cdc28e] mb-1">Make</label>
                    <input type="text" name="vehicle_make" id="vehicle_make" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                    <p id="error_vehicle_make" class="text-red-400 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="vehicle_model" class="block text-sm font-medium text-[#cdc28e] mb-1">Model</label>
                    <input type="text" name="vehicle_model" id="vehicle_model" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                    <p id="error_vehicle_model" class="text-red-400 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="vehicle_year" class="block text-sm font-medium text-[#cdc28e] mb-1">Year</label>
                    <input type="number" name="vehicle_year" id="vehicle_year" min="1900" max="<?php echo date('Y') + 1; ?>" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                    <p id="error_vehicle_year" class="text-red-400 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="license_plate" class="block text-sm font-medium text-[#cdc28e] mb-1">License Plate</label>
                    <input type="text" name="license_plate" id="license_plate" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                    <p id="error_license_plate" class="text-red-400 text-xs mt-1"></p>
                </div>
            </div>
        </fieldset>

        <!-- Appointment Details -->
        <fieldset class="border border-[#4a4321] p-4 rounded-md">
            <legend class="text-lg font-semibold text-white px-2">Appointment Details</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="service_id" class="block text-sm font-medium text-[#cdc28e] mb-1">Service</label>
                    <select name="service_id" id="service_id" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                        <option value="">-- Select Service --</option>
                        <?php foreach ($all_services as $service): ?>
                            <option value="<?php echo $service['id']; ?>">
                                <?php echo htmlspecialchars($service['name'] . ' ($' . $service['price'] . ' - ' . $service['duration'] . ' mins)'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p id="error_service_id" class="text-red-400 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="booking_status" class="block text-sm font-medium text-[#cdc28e] mb-1">Status</label>
                    <select name="booking_status" id="booking_status" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]">
                        <option value="pending">Pending</option>
                        <option value="confirmed" selected>Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="no-show">No-Show</option>
                    </select>
                    <p id="error_booking_status" class="text-red-400 text-xs mt-1"></p>
                </div>
                 <div>
                    <label for="booking_date" class="block text-sm font-medium text-[#cdc28e] mb-1">Date</label>
                    <input type="text" name="booking_date" id="booking_date_admin" placeholder="Select date" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53] admin-datepicker">
                    <p id="error_booking_date" class="text-red-400 text-xs mt-1"></p>
                </div>
                <div>
                    <label for="booking_time" class="block text-sm font-medium text-[#cdc28e] mb-1">Time</label>
                    <input type="text" name="booking_time" id="booking_time_admin" placeholder="Select time" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53] admin-timepicker">
                    <p id="error_booking_time" class="text-red-400 text-xs mt-1"></p>
                </div>
            </div>
            <div class="mt-4">
                <label for="notes" class="block text-sm font-medium text-[#cdc28e] mb-1">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3" class="w-full p-3 rounded-md bg-[#232010] border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53]"></textarea>
            </div>
        </fieldset>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-[#fcdd53] text-[#232010] hover:bg-[#fadc70] font-semibold rounded-lg text-sm px-6 py-3 text-center">
                Add Appointment
            </button>
        </div>
    </form>
</div>
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    flatpickr(".admin-datepicker", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        // minDate: "today" // Admin might need to book in the past for record keeping
    });
    flatpickr(".admin-timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        altInput: true,
        altFormat: "h:i K",
        minuteIncrement: 15
    });

    const clientTypeSelect = document.getElementById('client_type');
    const existingUserSection = document.getElementById('existingUserSection');
    const newGuestSection = document.getElementById('newGuestSection');
    const existingUserIdSelect = document.getElementById('existing_user_id');
    const userVehicleSelect = document.getElementById('selected_vehicle_id');
    const vehicleFields = {
        make: document.getElementById('vehicle_make'),
        model: document.getElementById('vehicle_model'),
        year: document.getElementById('vehicle_year'),
        plate: document.getElementById('license_plate')
    };
    const vehicleDetailsSection = document.getElementById('vehicleDetailsSection');


    function toggleClientSections() {
        if (clientTypeSelect.value === 'existing_user') {
            existingUserSection.classList.remove('hidden');
            newGuestSection.classList.add('hidden');
            setGuestFieldsRequired(false);
            setExistingUserFieldsRequired(true);
            vehicleDetailsSection.classList.remove('hidden'); // Show vehicle details for existing user
            loadUserVehicles(existingUserIdSelect.value);
        } else { // new_guest
            existingUserSection.classList.add('hidden');
            newGuestSection.classList.remove('hidden');
            setGuestFieldsRequired(true);
            setExistingUserFieldsRequired(false);
            vehicleDetailsSection.classList.remove('hidden'); // Show vehicle details for new guest
            clearVehicleFields();
            userVehicleSelect.innerHTML = '<option value="">-- Select Vehicle or Add New Below --</option>';
            userVehicleSelect.disabled = true;
            setVehicleFieldsRequired(true);
        }
    }

    function setGuestFieldsRequired(isRequired) {
        document.getElementById('guest_name').required = isRequired;
        document.getElementById('guest_email').required = isRequired;
    }
    function setExistingUserFieldsRequired(isRequired) {
         existingUserIdSelect.required = isRequired;
    }
    function setVehicleFieldsRequired(isRequired) {
        vehicleFields.make.required = isRequired;
        vehicleFields.model.required = isRequired;
        vehicleFields.year.required = isRequired;
        vehicleFields.plate.required = isRequired;
    }


    clientTypeSelect.addEventListener('change', toggleClientSections);

    existingUserIdSelect.addEventListener('change', function() {
        if (this.value) {
            loadUserVehicles(this.value);
        } else {
            userVehicleSelect.innerHTML = '<option value="">-- Select Vehicle or Add New Below --</option>';
            userVehicleSelect.disabled = true;
            clearVehicleFields();
            setVehicleFieldsRequired(true); // If no user selected, admin must provide vehicle details
        }
    });

    userVehicleSelect.addEventListener('change', function() {
        if (this.value && this.value !== 'new') {
            const selectedOption = this.options[this.selectedIndex];
            vehicleFields.make.value = selectedOption.dataset.make || '';
            vehicleFields.model.value = selectedOption.dataset.model || '';
            vehicleFields.year.value = selectedOption.dataset.year || '';
            vehicleFields.plate.value = selectedOption.dataset.plate || '';
            setVehicleFieldsRequired(false); // Details are filled, not strictly required to re-type
        } else { // "Add New" or no vehicle selected
            clearVehicleFields();
            setVehicleFieldsRequired(true);
        }
    });

    function loadUserVehicles(userId) {
        userVehicleSelect.innerHTML = '<option value="">Loading vehicles...</option>';
        userVehicleSelect.disabled = true;
        clearVehicleFields();

        if (!userId) {
            userVehicleSelect.innerHTML = '<option value="">-- Select User First --</option>';
            setVehicleFieldsRequired(true); // If no user, vehicle details must be entered
            return;
        }

        fetch(`<?php echo BASE_URL . "/manage_vehicles.php"; ?>?user_id_admin_view=${userId}`) // Add a param to specify admin view for specific user
            .then(response => response.json())
            .then(data => {
                userVehicleSelect.innerHTML = '<option value="">-- Select Vehicle or Add New Below --</option>';
                if (data.success && data.data.length > 0) {
                    data.data.forEach(vehicle => {
                        const option = document.createElement('option');
                        option.value = vehicle.id;
                        option.textContent = `${vehicle.make} ${vehicle.model} (${vehicle.license_plate})`;
                        option.dataset.make = vehicle.make;
                        option.dataset.model = vehicle.model;
                        option.dataset.year = vehicle.year;
                        option.dataset.plate = vehicle.license_plate;
                        userVehicleSelect.appendChild(option);
                    });
                }
                const addNewOption = document.createElement('option');
                addNewOption.value = "new";
                addNewOption.textContent = "-- Add New Vehicle Details Below --";
                userVehicleSelect.appendChild(addNewOption);
                userVehicleSelect.disabled = false;
                setVehicleFieldsRequired(true); // Default to requiring new vehicle details unless one is selected
            })
            .catch(error => {
                console.error('Error fetching user vehicles:', error);
                userVehicleSelect.innerHTML = '<option value="">Error loading vehicles</option>';
                setVehicleFieldsRequired(true);
            });
    }

    function clearVehicleFields() {
        vehicleFields.make.value = '';
        vehicleFields.model.value = '';
        vehicleFields.year.value = '';
        vehicleFields.plate.value = '';
    }

    // Initial setup based on default selection
    toggleClientSections();


    document.getElementById('adminAddBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('adminBookingFormMessage');

        // Clear previous errors
        document.querySelectorAll('p[id^="error_"]').forEach(el => el.textContent = '');
        messageDiv.textContent = 'Processing...';
        messageDiv.className = 'p-3 rounded-md text-sm bg-yellow-600/30 border border-yellow-500 text-yellow-300';
        messageDiv.classList.remove('hidden');

        fetch('<?php echo BASE_URL . "/admin_add_booking.php"; ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.textContent = data.message;
                messageDiv.className = 'p-3 rounded-md text-sm bg-green-600/30 border border-green-500 text-green-300';
                this.reset(); // Reset form
                toggleClientSections(); // Reset client type sections
                // Optionally redirect or update another part of the page
                if(typeof fetchAppointments === 'function') fetchAppointments(); // If on appointments page, refresh list
            } else {
                messageDiv.textContent = data.message || 'An error occurred. Please check the fields.';
                messageDiv.className = 'p-3 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
                if (data.errors) {
                    for (const key in data.errors) {
                        const errorEl = document.getElementById(`error_${key}`);
                        if (errorEl) {
                            errorEl.textContent = data.errors[key];
                        }
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.textContent = 'An unexpected network error occurred.';
            messageDiv.className = 'p-3 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
        });
    });
});
</script>
