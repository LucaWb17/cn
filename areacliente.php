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
  </head>
  <body>
    <?php
        require_once 'config.php';
        require_once 'auth_check.php';
        require_once 'utils/functions.php';

        if (is_admin()) {
            redirect(BASE_URL . '/dashboardAdmin.php');
        }
        $user_name = $_SESSION['user_name'] ?? 'Customer';
        $page_is_active = basename($_SERVER['PHP_SELF']);
        $current_client_tab = $_GET['tab'] ?? 'overview';
    ?>
    <div class="relative flex size-full min-h-screen flex-col bg-[#232010] dark group/design-root overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#4a4321] px-4 sm:px-10 py-3 relative">
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
            <!-- Desktop Navigation (Hidden on sm, shown on md and up) -->
            <nav class="hidden md:flex items-center gap-6">
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] <?php echo ($page_is_active == 'home.php') ? 'text-[#fcdd53] font-bold' : ''; ?>" href="<?php echo BASE_URL . '/home.php'; ?>">Home</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/bookinapp.php'; ?>">Book</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] <?php echo ($page_is_active == 'servizi.php') ? 'text-[#fcdd53] font-bold' : ''; ?>" href="<?php echo BASE_URL . '/servizi.php'; ?>">Services</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="#">About</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] <?php echo ($page_is_active == 'contact.php') ? 'text-[#fcdd53] font-bold' : ''; ?>" href="<?php echo BASE_URL . '/contact.php'; ?>">Contact</a>
            </nav>
            <!-- User avatar and logout -->
            <div class="flex items-center gap-2">
                 <!-- Mobile Menu Button (Visible on sm and down) -->
                <button id="hamburger-button-cliente" class="md:hidden text-white p-2 rounded-md hover:bg-[#4a4321] focus:outline-none focus:bg-[#4a4321]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
                <a href="<?php echo BASE_URL . '/logout.php'; ?>"
                    class="hidden md:flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a]">
                    Logout
                </a>
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                    title="<?php echo htmlspecialchars($user_name); ?>"
                    style='background-image: url("https://ui-avatars.com/api/?name=<?php echo urlencode($user_name); ?>&background=4a4321&color=fff&size=128");'>
                </div>
            </div>
          </div>
        </header>
         <!-- Mobile Menu for Area Cliente -->
        <div id="mobile-menu-cliente" class="hidden md:hidden bg-[#2c281a] border-b border-[#4a4321] absolute top-[60px] left-0 right-0 z-50">
            <nav class="flex flex-col items-center gap-2 px-2 pt-2 pb-3 space-y-1">
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md <?php echo ($page_is_active == 'home.php') ? 'text-[#fcdd53] font-bold bg-[#4a4321]' : ''; ?>" href="<?php echo BASE_URL . '/home.php'; ?>">Home</a>
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md" href="<?php echo BASE_URL . '/bookinapp.php'; ?>">Book Appointment</a>
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md <?php echo ($page_is_active == 'servizi.php') ? 'text-[#fcdd53] font-bold bg-[#4a4321]' : ''; ?>" href="<?php echo BASE_URL . '/servizi.php'; ?>">Services</a>
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md" href="#">About</a>
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md <?php echo ($page_is_active == 'contact.php') ? 'text-[#fcdd53] font-bold bg-[#4a4321]' : ''; ?>" href="<?php echo BASE_URL . '/contact.php'; ?>">Contact</a>
              <hr class="w-full border-t border-[#4a4321] my-2">
              <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md">Logout</a>
            </nav>
        </div>

        <div class="flex flex-col sm:flex-row gap-1 px-2 sm:px-6 flex-1 justify-center py-5 pt-20 md:pt-5"> <!-- Adjusted padding top for mobile -->
          <!-- Sidebar -->
          <div class="layout-content-container flex flex-col w-full sm:w-64 md:w-80 bg-[#232010] p-4 rounded-lg sm:min-h-[700px] mb-4 sm:mb-0">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col mb-4">
                    <h1 class="text-white text-lg font-medium leading-normal"><?php echo htmlspecialchars($user_name); ?></h1>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">Customer Area</p>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="<?php echo BASE_URL . '/areacliente.php?tab=overview'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#4a4321] text-white aria-[current=page]:bg-[#4a4321]" aria-current="<?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'overview') ? 'page' : ''; ?>">
                        <div class="text-white" data-icon="House" data-size="24px" data-weight="regular">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256"><path d="M218.83,103.77l-80-75.48a1.14,1.14,0,0,1-.11-.11,16,16,0,0,0-21.53,0l-.11.11L37.17,103.77A16,16,0,0,0,32,115.55V208a16,16,0,0,0,16,16H96a16,16,0,0,0,16-16V160h32v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V115.55A16,16,0,0,0,218.83,103.77ZM208,208H160V160a16,16,0,0,0-16-16H112a16,16,0,0,0-16,16v48H48V115.55l.11-.1L128,40l79.9,75.43.11.1Z"></path></svg>
                        </div>
                        <p class="text-sm font-medium leading-normal">Overview</p>
                    </a>
                    <a href="<?php echo BASE_URL . '/areacliente.php?tab=bookings'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#4a4321] text-white aria-[current=page]:bg-[#4a4321]" aria-current="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'bookings') ? 'page' : ''; ?>">
                    <div class="text-white" data-icon="Calendar" data-size="24px" data-weight="fill">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                        <path
                          d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM112,184a8,8,0,0,1-16,0V132.94l-4.42,2.22a8,8,0,0,1-7.16-14.32l16-8A8,8,0,0,1,112,120Zm56-8a8,8,0,0,1,0,16H136a8,8,0,0,1-6.4-12.8l28.78-38.37A8,8,0,1,0,145.07,132a8,8,0,1,1-13.85-8A24,24,0,0,1,176,136a23.76,23.76,0,0,1-4.84,14.45L152,176ZM48,80V48H72v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80Z"
                        ></path>
                      </svg>
                    </div>
                        <div class="text-white" data-icon="Calendar" data-size="24px" data-weight="fill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM112,184a8,8,0,0,1-16,0V132.94l-4.42,2.22a8,8,0,0,1-7.16-14.32l16-8A8,8,0,0,1,112,120Zm56-8a8,8,0,0,1,0,16H136a8,8,0,0,1-6.4-12.8l28.78-38.37A8,8,0,1,0,145.07,132a8,8,0,1,1-13.85-8A24,24,0,0,1,176,136a23.76,23.76,0,0,1-4.84,14.45L152,176ZM48,80V48H72v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80Z"></path></svg>
                        </div>
                        <p class="text-sm font-medium leading-normal">Bookings</p>
                    </a>
                    <a href="<?php echo BASE_URL . '/areacliente.php?tab=vehicles'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#4a4321] text-white aria-[current=page]:bg-[#4a4321]" aria-current="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'vehicles') ? 'page' : ''; ?>">
                        <div class="text-white" data-icon="Car" data-size="24px" data-weight="regular">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256"><path d="M240,112H229.2L201.42,49.5A16,16,0,0,0,186.8,40H69.2a16,16,0,0,0-14.62,9.5L26.8,112H16a8,8,0,0,0,0,16h8v80a16,16,0,0,0,16,16H64a16,16,0,0,0,16-16V192h96v16a16,16,0,0,0,16,16h24a16,16,0,0,0,16-16V128h8a8,8,0,0,0,0-16ZM69.2,56H186.8l24.89,56H44.31ZM64,208H40V192H64Zm128,0V192h24v16Zm24-32H40V128H216ZM56,152a8,8,0,0,1,8-8H80a8,8,0,0,1,0,16H64A8,8,0,0,1,56,152Zm112,0a8,8,0,0,1,8-8h16a8,8,0,0,1,0,16H176A8,8,0,0,1,168,152Z"></path></svg>
                        </div>
                        <p class="text-sm font-medium leading-normal">My Vehicles</p>
                    </a>
                    <a href="<?php echo BASE_URL . '/areacliente.php?tab=settings'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#4a4321] text-white aria-[current=page]:bg-[#4a4321]" aria-current="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'settings') ? 'page' : ''; ?>">
                        <div class="text-white" data-icon="Gear" data-size="24px" data-weight="regular">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256"><path d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Zm88-29.84q.06-2.16,0-4.32l14.92-18.64a8,8,0,0,0,1.48-7.06,107.21,107.21,0,0,0-10.88-26.25,8,8,0,0,0-6-3.93l-23.72-2.64q-1.48-1.56-3-3L186,40.54a8,8,0,0,0-3.94-6,107.71,107.71,0,0,0-26.25-10.87,8,8,0,0,0-7.06,1.49L130.16,40Q128,40,125.84,40L107.2,25.11a8,8,0,0,0-7.06-1.48A107.6,107.6,0,0,0,73.89,34.51a8,8,0,0,0-3.93,6L67.32,64.27q-1.56,1.49-3,3L40.54,70a8,8,0,0,0-6,3.94,107.71,107.71,0,0,0-10.87,26.25,8,8,0,0,0,1.49,7.06L40,125.84Q40,128,40,130.16L25.11,148.8a8,8,0,0,0-1.48,7.06,107.21,107.21,0,0,0,10.88,26.25,8,8,0,0,0,6,3.93l23.72,2.64q1.49,1.56,3,3L70,215.46a8,8,0,0,0,3.94,6,107.71,107.71,0,0,0,26.25,10.87,8,8,0,0,0,7.06-1.49L125.84,216q2.16.06,4.32,0l18.64,14.92a8,8,0,0,0,7.06,1.48,107.21,107.21,0,0,0,26.25-10.88,8,8,0,0,0,3.93-6l2.64-23.72q1.56-1.48,3-3L215.46,186a8,8,0,0,0,6-3.94,107.71,107.71,0,0,0,10.87-26.25,8,8,0,0,0-1.49-7.06Zm-16.1-6.5a73.93,73.93,0,0,1,0,8.68,8,8,0,0,0,1.74,5.48l14.19,17.73a91.57,91.57,0,0,1-6.23,15L187,173.11a8,8,0,0,0-5.1,2.64,74.11,74.11,0,0,1-6.14,6.14,8,8,0,0,0-2.64,5.1l-2.51,22.58a91.32,91.32,0,0,1-15,6.23l-17.74-14.19a8,8,0,0,0-5-1.75h-.48a73.93,73.93,0,0,1-8.68,0,8,8,0,0,0-5.48,1.74L100.45,215.8a91.57,91.57,0,0,1-15-6.23L82.89,187a8,8,0,0,0-2.64-5.1,74.11,74.11,0,0,1-6.14-6.14,8,8,0,0,0-5.1-2.64L46.43,170.6a91.32,91.32,0,0,1-6.23-15l14.19-17.74a8,8,0,0,0,1.74-5.48,73.93,73.93,0,0,1,0-8.68,8,8,0,0,0-1.74-5.48L40.2,100.45a91.57,91.57,0,0,1,6.23-15L69,82.89a8,8,0,0,0,5.1-2.64,74.11,74.11,0,0,1,6.14-6.14A8,8,0,0,0,82.89,69L85.4,46.43a91.32,91.32,0,0,1,15-6.23l17.74,14.19a8,8,0,0,0,5.48,1.74,73.93,73.93,0,0,1,8.68,0,8,8,0,0,0,5.48-1.74L155.55,40.2a91.57,91.57,0,0,1,15,6.23L173.11,69a8,8,0,0,0,2.64,5.1,74.11,74.11,0,0,1,6.14,6.14,8,8,0,0,0,5.1,2.64l22.58,2.51a91.32,91.32,0,0,1,6.23,15l-14.19,17.74A8,8,0,0,0,199.87,123.66Z"></path></svg>
                        </div>
                        <p class="text-sm font-medium leading-normal">Settings</p>
                    </a>
                </div>
            </div>
            <div class="mt-auto"> <!-- Pushes logout to the bottom -->
                 <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[#4a4321] text-white">
                    <div class="text-white" data-icon="SignOut" data-size="24px" data-weight="regular">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256"><path d="M112,216a8,8,0,0,1-8,8H48a16,16,0,0,1-16-16V48A16,16,0,0,1,48,32h56a8,8,0,0,1,0,16H48V208h56A8,8,0,0,1,112,216Zm109.66-93.66-48-48a8,8,0,0,0-11.32,11.32L196.69,120H104a8,8,0,0,0,0,16h92.69l-34.35,34.34a8,8,0,0,0,11.32,11.32l48-48A8,8,0,0,0,221.66,122.34Z"></path></svg>
                    </div>
                    <p class="text-sm font-medium leading-normal">Logout</p>
                </a>
            </div>
          </div>

          <!-- Main Content Area -->
          <div class="layout-content-container flex flex-col flex-1 max-w-full sm:max-w-[calc(100%-16rem-0.25rem)] md:max-w-[calc(100%-20rem-0.25rem)]">
            <?php echo display_flash_message(); ?>
            <?php
                $tab = $_GET['tab'] ?? 'overview';
                switch ($tab) {
                    case 'bookings':
                        include 'partials/cliente_bookings.php';
                        break;
                    case 'vehicles':
                        include 'partials/cliente_vehicles.php';
                        break;
                    case 'settings':
                        include 'partials/cliente_settings.php';
                        break;
                    case 'overview':
                    default:
                        include 'partials/cliente_overview.php';
                        break;
                }
            ?>
          </div>
        </div>
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
            const hamburgerButton = document.getElementById('hamburger-button-cliente'); // ID Unico
            const mobileMenu = document.getElementById('mobile-menu-cliente'); // ID Unico

            if (hamburgerButton && mobileMenu) {
                hamburgerButton.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Gestione tab per sidebar (se necessario, ma lo switch PHP gestisce il contenuto)
            // Potresti aggiungere JS per cambiare l'URL con ?tab= e ricaricare, o usare AJAX qui
            // Ma per ora, i link della sidebar ricaricano la pagina con il parametro GET corretto
        });
    </script>
  </body>
</html>
