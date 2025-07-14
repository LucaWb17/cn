<html>
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Space+Grotesk%3Awght%40400%3B500%3B700"
    />

    <title>Stitch Design</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  </head>
  <body>
    <?php
    require_once 'config.php';
    require_once 'utils/functions.php';

    $all_services = get_all_services($mysqli);
    $user_vehicles = [];
    if (is_logged_in()) {
        $user_vehicles = get_user_vehicles($mysqli, $_SESSION['user_id']);
    }

    // Retrieve form data and errors from session if they exist (after redirect from book_appointment.php)
    $form_data = $_SESSION['form_data'] ?? [];
    $form_errors = $_SESSION['form_errors'] ?? [];
    unset($_SESSION['form_data'], $_SESSION['form_errors']);

    $selected_service_id = $form_data['service_id'] ?? ($_GET['service_id'] ?? '');

    // Generate CSRF token
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];
    ?>
    <div
      class="relative flex size-full min-h-screen flex-col bg-[#232010] dark group/design-root overflow-x-hidden"
      style='--select-button-svg: url(&apos;data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2724px%27 height=%2724px%27 fill=%27rgb(205,194,142)%27 viewBox=%270 0 256 256%27%3e%3cpath d=%27M181.66,170.34a8,8,0,0,1,0,11.32l-48,48a8,8,0,0,1-11.32,0l-48-48a8,8,0,0,1,11.32-11.32L128,212.69l42.34-42.35A8,8,0,0,1,181.66,170.34Zm-96-84.68L128,43.31l42.34,42.35a8,8,0,0,0,11.32-11.32l-48-48a8,8,0,0,0-11.32,0l-48,48A8,8,0,0,0,85.66,85.66Z%27%3e%3c/path%3e%3c/svg%3e&apos;); font-family: "Space Grotesk", "Noto Sans", sans-serif;'
    >
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#4a4321] px-4 sm:px-10 py-3">
          <a href="<?php echo BASE_URL . '/home.php'; ?>" class="flex items-center gap-4 text-white">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z"
                  fill="currentColor"
                ></path>
              </svg>
            </div>
            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">CN Auto</h2>
          </a>
          <div class="flex flex-1 justify-end items-center gap-2 sm:gap-6">
             <nav class="hidden sm:flex items-center gap-6">
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/home.php'; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'page' : ''; ?>">Home</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/servizi.php'; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'servizi.php') ? 'page' : ''; ?>">Services</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="#">About</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/contact.php'; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'page' : ''; ?>">Contact</a>
            </nav>
             <?php if (is_logged_in()): ?>
                <a href="<?php echo is_admin() ? BASE_URL . '/dashboardAdmin.php' : BASE_URL . '/areacliente.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors"><?php echo is_admin() ? 'Admin' : 'My Account'; ?></a>
                <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a]">Logout</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL . '/login.php'; ?>" class="flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a]">Log In</a>
            <?php endif; ?>
          </div>
        </header>
        <main class="px-4 sm:px-10 md:px-20 lg:px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col w-full sm:w-[512px] max-w-[512px] py-5 flex-1">
            <h2 class="text-white tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-3 pt-5">Book Your Service</h2>
            <?php echo display_flash_message(); ?>

            <form id="bookingForm" action="<?php echo BASE_URL . '/book_appointment.php'; ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div class="form-group px-4 py-3">
                    <label for="service_id" class="block text-[#cdc28e] text-sm font-medium mb-1">Service</label>
                    <select name="service_id" id="service_id" required
                      class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] text-base <?php echo isset($form_errors['service_id']) ? 'border-red-500' : ''; ?>"
                    >
                      <option value="">Select service</option>
                      <?php foreach ($all_services as $service): ?>
                        <option value="<?php echo htmlspecialchars($service['id']); ?>" <?php echo ($selected_service_id == $service['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($service['name']); ?> (<?php echo htmlspecialchars($service['duration']); ?> mins - $<?php echo htmlspecialchars($service['price']); ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <?php if(isset($form_errors['service_id'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['service_id']; ?></p><?php endif; ?>
                </div>

                <?php if (!is_logged_in()): ?>
                    <h3 class="text-white text-xl font-semibold px-4 pt-4 pb-2">Guest Information</h3>
                    <div class="form-group px-4 py-3">
                        <label for="guest_name" class="block text-[#cdc28e] text-sm font-medium mb-1">Full Name</label>
                        <input type="text" name="guest_name" id="guest_name" placeholder="Your Full Name" required value="<?php echo htmlspecialchars($form_data['guest_name'] ?? ''); ?>"
                               class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['guest_name']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($form_errors['guest_name'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['guest_name']; ?></p><?php endif; ?>
                    </div>
                    <div class="form-group px-4 py-3">
                        <label for="guest_email" class="block text-[#cdc28e] text-sm font-medium mb-1">Email</label>
                        <input type="email" name="guest_email" id="guest_email" placeholder="Your Email" required value="<?php echo htmlspecialchars($form_data['guest_email'] ?? ''); ?>"
                               class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['guest_email']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($form_errors['guest_email'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['guest_email']; ?></p><?php endif; ?>
                    </div>
                    <div class="form-group px-4 py-3">
                        <label for="guest_phone" class="block text-[#cdc28e] text-sm font-medium mb-1">Phone Number</label>
                        <input type="tel" name="guest_phone" id="guest_phone" placeholder="Your Phone Number" required value="<?php echo htmlspecialchars($form_data['guest_phone'] ?? ''); ?>"
                               class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['guest_phone']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($form_errors['guest_phone'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['guest_phone']; ?></p><?php endif; ?>
                    </div>
                <?php endif; ?>

                <h3 class="text-white text-xl font-semibold px-4 pt-4 pb-2">Vehicle Information</h3>
                <?php if (is_logged_in() && !empty($user_vehicles)): ?>
                <div class="form-group px-4 py-3">
                    <label for="selected_vehicle_id" class="block text-[#cdc28e] text-sm font-medium mb-1">Select Your Vehicle</label>
                    <select name="selected_vehicle_id" id="selected_vehicle_id"
                            class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] text-base">
                        <option value="">-- Select a saved vehicle --</option>
                        <?php foreach($user_vehicles as $vehicle): ?>
                            <option value="<?php echo $vehicle['id']; ?>"
                                    data-make="<?php echo htmlspecialchars($vehicle['make']); ?>"
                                    data-model="<?php echo htmlspecialchars($vehicle['model']); ?>"
                                    data-year="<?php echo htmlspecialchars($vehicle['year']); ?>"
                                    data-plate="<?php echo htmlspecialchars($vehicle['license_plate']); ?>"
                                    <?php echo (isset($form_data['selected_vehicle_id']) && $form_data['selected_vehicle_id'] == $vehicle['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model'] . ' (' . $vehicle['license_plate'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="new" <?php echo (isset($form_data['selected_vehicle_id']) && $form_data['selected_vehicle_id'] == 'new') ? 'selected' : ''; ?>>-- Add New Vehicle --</option>
                    </select>
                </div>
                <?php endif; ?>

                <div id="new-vehicle-fields" class="<?php echo (is_logged_in() && !empty($user_vehicles) && (!isset($form_data['selected_vehicle_id']) || $form_data['selected_vehicle_id'] != 'new')) ? 'hidden' : ''; ?>">
                    <div class="form-group px-4 py-3">
                        <label for="vehicle_make" class="block text-[#cdc28e] text-sm font-medium mb-1">Vehicle Make</label>
                        <input type="text" name="vehicle_make" id="vehicle_make" placeholder="e.g., Toyota" value="<?php echo htmlspecialchars($form_data['vehicle_make'] ?? ''); ?>"
                               class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['vehicle_make']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($form_errors['vehicle_make'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['vehicle_make']; ?></p><?php endif; ?>
                    </div>
                    <div class="form-group px-4 py-3">
                        <label for="vehicle_model" class="block text-[#cdc28e] text-sm font-medium mb-1">Vehicle Model</label>
                        <input type="text" name="vehicle_model" id="vehicle_model" placeholder="e.g., Camry" value="<?php echo htmlspecialchars($form_data['vehicle_model'] ?? ''); ?>"
                               class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['vehicle_model']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($form_errors['vehicle_model'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['vehicle_model']; ?></p><?php endif; ?>
                    </div>
                    <div class="form-group px-4 py-3">
                        <label for="vehicle_year" class="block text-[#cdc28e] text-sm font-medium mb-1">Vehicle Year</label>
                        <input type="number" name="vehicle_year" id="vehicle_year" placeholder="e.g., 2020" value="<?php echo htmlspecialchars($form_data['vehicle_year'] ?? ''); ?>"
                               class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['vehicle_year']) ? 'border-red-500' : ''; ?>" min="1900" max="<?php echo date('Y') + 1; ?>">
                        <?php if(isset($form_errors['vehicle_year'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['vehicle_year']; ?></p><?php endif; ?>
                    </div>
                    <div class="form-group px-4 py-3">
                        <label for="license_plate" class="block text-[#cdc28e] text-sm font-medium mb-1">License Plate</label>
                        <input type="text" name="license_plate" id="license_plate" placeholder="e.g., ABC1234" value="<?php echo htmlspecialchars($form_data['license_plate'] ?? ''); ?>"
                               class="form-input w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['license_plate']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($form_errors['license_plate'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['license_plate']; ?></p><?php endif; ?>
                    </div>
                    <?php if (is_logged_in()): ?>
                    <div class="form-group px-4 py-2">
                        <label class="flex items-center text-[#cdc28e]">
                            <input type="checkbox" name="save_vehicle_details" value="1" class="form-checkbox h-5 w-5 text-[#fcdd53] bg-[#353017] border-[#6a5f2f] focus:ring-[#fcdd53]" checked>
                            <span class="ml-2 text-sm">Save these vehicle details to my account</span>
                        </label>
                    </div>
                    <?php endif; ?>
                </div>


                <h3 class="text-white text-xl font-semibold px-4 pt-4 pb-2">Appointment Details</h3>
                <div class="form-group px-4 py-3">
                    <label for="booking_date" class="block text-[#cdc28e] text-sm font-medium mb-1">Preferred Date</label>
                    <input type="text" name="booking_date" id="booking_date" placeholder="Select date" required value="<?php echo htmlspecialchars($form_data['booking_date'] ?? ''); ?>"
                           class="form-input datepicker w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['booking_date']) ? 'border-red-500' : ''; ?>">
                    <?php if(isset($form_errors['booking_date'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['booking_date']; ?></p><?php endif; ?>
                </div>
                <div class="form-group px-4 py-3">
                    <label for="booking_time" class="block text-[#cdc28e] text-sm font-medium mb-1">Preferred Time</label>
                    <input type="text" name="booking_time" id="booking_time" placeholder="Select time" required value="<?php echo htmlspecialchars($form_data['booking_time'] ?? ''); ?>"
                           class="form-input timepicker w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] h-14 p-[15px] placeholder:text-[#cdc28e] <?php echo isset($form_errors['booking_time']) ? 'border-red-500' : ''; ?>">
                    <?php if(isset($form_errors['booking_time'])): ?><p class="text-red-500 text-xs mt-1"><?php echo $form_errors['booking_time']; ?></p><?php endif; ?>
                </div>

                 <div class="form-group px-4 py-3">
                    <label for="notes" class="block text-[#cdc28e] text-sm font-medium mb-1">Additional Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Any specific requests or information..."
                              class="form-textarea w-full rounded-lg text-white border border-[#6a5f2f] bg-[#353017] focus:border-[#fcdd53] p-[15px] placeholder:text-[#cdc28e]"><?php echo htmlspecialchars($form_data['notes'] ?? ''); ?></textarea>
                </div>


                <div class="flex px-4 py-3 mt-4">
                  <button type="submit"
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 flex-1 bg-[#fcdd53] text-[#232010] text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]"
                  >
                    <span class="truncate">Book Appointment</span>
                  </button>
                </div>
            </form>
          </div>
        </main>
        <footer class="flex justify-center border-t border-solid border-t-[#4a4321] mt-auto py-5 bg-[#232010]">
            <div class="flex max-w-[960px] flex-1 flex-col px-4">
                 <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-3 @[480px]:flex-row @[480px]:justify-around mb-4">
                    <a class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#fcdd53]" href="#">Privacy Policy</a>
                    <a class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#fcdd53]" href="#">Terms of Service</a>
                    <a class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/contact.php'; ?>">Contact Us</a>
                </div>
                <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal text-center">© <?php echo date("Y"); ?> CN Auto. All rights reserved.</p>
            </div>
        </footer>
      </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr(".datepicker", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                minDate: "today"
            });
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                altInput: true,
                altFormat: "h:i K",
                minuteIncrement: 15,
                minTime: "08:00",
                maxTime: "17:00"
            });

            const vehicleSelect = document.getElementById('selected_vehicle_id');
            const newVehicleFields = document.getElementById('new-vehicle-fields');
            const makeInput = document.getElementById('vehicle_make');
            const modelInput = document.getElementById('vehicle_model');
            const yearInput = document.getElementById('vehicle_year');
            const plateInput = document.getElementById('license_plate');

            if (vehicleSelect) {
                vehicleSelect.addEventListener('change', function() {
                    if (this.value === 'new' || this.value === '') {
                        newVehicleFields.classList.remove('hidden');
                        makeInput.value = ''; makeInput.required = true;
                        modelInput.value = ''; modelInput.required = true;
                        yearInput.value = ''; yearInput.required = true;
                        plateInput.value = ''; plateInput.required = true;
                    } else {
                        newVehicleFields.classList.add('hidden');
                        const selectedOption = this.options[this.selectedIndex];
                        makeInput.value = selectedOption.dataset.make || ''; makeInput.required = false;
                        modelInput.value = selectedOption.dataset.model || ''; modelInput.required = false;
                        yearInput.value = selectedOption.dataset.year || ''; yearInput.required = false;
                        plateInput.value = selectedOption.dataset.plate || ''; plateInput.required = false;
                    }
                });
                // Trigger change on load if a vehicle is pre-selected to hide new fields
                if (vehicleSelect.value !== 'new' && vehicleSelect.value !== '') {
                    vehicleSelect.dispatchEvent(new Event('change'));
                }
                 // if form_data has selected_vehicle_id as new, show the fields
                <?php if(isset($form_data['selected_vehicle_id']) && $form_data['selected_vehicle_id'] == 'new'): ?>
                    newVehicleFields.classList.remove('hidden');
                    makeInput.required = true; modelInput.required = true; yearInput.required = true; plateInput.required = true;
                <?php elseif(is_logged_in() && !empty($user_vehicles) && (!isset($form_data['selected_vehicle_id']) || $form_data['selected_vehicle_id'] == '')): ?>
                    // If logged in, has vehicles, and nothing specific selected (or "select vehicle" is chosen), default to new vehicle hidden
                    // unless an error in new vehicle fields occurred
                     <?php if( !(isset($form_errors['vehicle_make']) || isset($form_errors['vehicle_model']) || isset($form_errors['vehicle_year']) || isset($form_errors['license_plate'])) ) : ?>
                        newVehicleFields.classList.add('hidden');
                        makeInput.required = false; modelInput.required = false; yearInput.required = false; plateInput.required = false;
                     <?php else: ?>
                        newVehicleFields.classList.remove('hidden');
                         makeInput.required = true; modelInput.required = true; yearInput.required = true; plateInput.required = true;
                     <?php endif; ?>
                <?php else: ?>
                     newVehicleFields.classList.remove('hidden');
                     makeInput.required = true; modelInput.required = true; yearInput.required = true; plateInput.required = true;
                <?php endif; ?>
            } else {
                 // If no vehicle select dropdown (e.g. guest user), new vehicle fields are always visible
                newVehicleFields.classList.remove('hidden');
                makeInput.required = true; modelInput.required = true; yearInput.required = true; plateInput.required = true;
            }
        });
    </script>
  </body>
</html>
