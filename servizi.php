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
        require_once 'utils/functions.php';
        $all_services = get_all_services($mysqli); // Fetch all services
        $page_is_active = basename($_SERVER['PHP_SELF']);
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
            <!-- Desktop Navigation -->
            <nav class="hidden sm:flex items-center gap-6">
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] <?php echo ($page_is_active == 'home.php') ? 'text-[#fcdd53] font-bold' : ''; ?>" href="<?php echo BASE_URL . '/home.php'; ?>">Home</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] <?php echo ($page_is_active == 'servizi.php') ? 'text-[#fcdd53] font-bold' : ''; ?>" href="<?php echo BASE_URL . '/servizi.php'; ?>">Services</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="#">About</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] <?php echo ($page_is_active == 'contact.php') ? 'text-[#fcdd53] font-bold' : ''; ?>" href="<?php echo BASE_URL . '/contact.php'; ?>">Contact</a>
            </nav>
            <div class="flex items-center gap-2">
                 <!-- Mobile Menu Button -->
                <button id="hamburger-button" class="sm:hidden text-white p-2 rounded-md hover:bg-[#4a4321] focus:outline-none focus:bg-[#4a4321]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
                <?php if (is_logged_in()): ?>
                    <a href="<?php echo is_admin() ? BASE_URL . '/dashboardAdmin.php' : BASE_URL . '/areacliente.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors hidden sm:block"><?php echo is_admin() ? 'Admin' : 'My Account'; ?></a>
                    <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a] hidden sm:flex">Logout</a>
                <?php else: ?>
                     <a href="<?php echo BASE_URL . '/login.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors hidden sm:block">Log In</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>"
                  class="flex min-w-[80px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#fcdd53] text-[#232010] text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]"
                >
                  <span class="truncate">Book Now</span>
                </a>
            </div>
          </div>
        </header>
         <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden sm:hidden bg-[#2c281a] border-b border-[#4a4321] absolute top-[60px] left-0 right-0 z-50">
            <nav class="flex flex-col items-center gap-2 px-2 pt-2 pb-3 space-y-1">
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md <?php echo ($page_is_active == 'home.php') ? 'text-[#fcdd53] font-bold bg-[#4a4321]' : ''; ?>" href="<?php echo BASE_URL . '/home.php'; ?>">Home</a>
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md <?php echo ($page_is_active == 'servizi.php') ? 'text-[#fcdd53] font-bold bg-[#4a4321]' : ''; ?>" href="<?php echo BASE_URL . '/servizi.php'; ?>">Services</a>
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md" href="#">About</a>
              <a class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md <?php echo ($page_is_active == 'contact.php') ? 'text-[#fcdd53] font-bold bg-[#4a4321]' : ''; ?>" href="<?php echo BASE_URL . '/contact.php'; ?>">Contact</a>

                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                         <a href="<?php echo BASE_URL . '/dashboardAdmin.php'; ?>" class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md">Admin Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL . '/areacliente.php'; ?>" class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md">My Account</a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL . '/login.php'; ?>" class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md">Log In</a>
                    <a href="<?php echo BASE_URL . '/createaccount.php'; ?>" class="block w-full text-center text-white text-base font-medium leading-normal hover:text-[#fcdd53] hover:bg-[#4a4321] p-2 rounded-md">Sign Up</a>
                <?php endif; ?>
                 <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>" class="block w-full text-center mt-2 bg-[#fcdd53] text-[#232010] hover:bg-[#fadc70] text-base font-bold p-2 rounded-md">Book Now</a>
            </nav>
        </div>

        <main class="px-4 sm:px-10 md:px-20 lg:px-40 flex flex-1 justify-center py-5 pt-20 sm:pt-5"> <!-- Aggiunto padding top per mobile per evitare sovrapposizione con menu -->
          <div class="layout-content-container flex flex-col w-full max-w-[960px] flex-1">
            <div class="flex flex-wrap justify-between gap-3 p-4">
              <div class="flex min-w-72 flex-col gap-3">
                <h1 class="text-white tracking-light text-3xl sm:text-[32px] font-bold leading-tight">Our Services</h1>
                <p class="text-[#cdc28e] text-sm font-normal leading-normal max-w-2xl">
                  Explore our comprehensive range of vehicle inspection and maintenance services, designed to keep your vehicle in optimal condition. Each service is performed by
                  our certified technicians using state-of-the-art equipment.
                </p>
              </div>
            </div>

            <?php
            // Example: Group services by type if you add a 'type' column to your services table
            // For now, we'll list them all under generic headings or just one list.
            // Let's assume two types for demonstration: 'inspection' and 'maintenance'
            // $inspection_services = array_filter($all_services, fn($s) => str_contains(strtolower($s['name']), 'inspection'));
            // $maintenance_services = array_filter($all_services, fn($s) => !str_contains(strtolower($s['name']), 'inspection'));
            // For simplicity, let's just loop through all services. You can categorize them later.

            $service_images = [ // Placeholder images - replace with actual relevant images
                "https://lh3.googleusercontent.com/aida-public/AB6AXuBd9htGa6cL_zpGjZzMbbQm_upMpOzepf_WnCB3mc9AhbS4JdSuGA49doR8rG-8jS3aPJNoKXetNtqm_UG2qFQJUao-5MrnG-BOyJnEHfPE3oqcY7reLqgrSX53BtSzsUUJzZAZXPv5TQNhEu7rP0budQUgkXoLMeN6H0DlB6bDLcuWINFaP2-fw9U0NfosSZLQoR-QynC18cKO5eKoRbNikwTFEfZutbLWp_iBi0iVUIic0D0uMKve6rApZ6M3wilJCVc2g7EVfOo",
                "https://lh3.googleusercontent.com/aida-public/AB6AXuDk2Mle0hNeFutRppkYZIo7MzZHCl_LoUmBquF8V91AQcMnG_bqw5YVzuykOWRpQ1eUJ3trMTGa0jaSL74V9mAGAG1-g2VuJ4ok1rzZg5uFGDVNl0RnT3AX8b9JnctwmjIXSUsx4pLA-dN4fgQH6SJQiA13sSX37Ji91O37kR915tLuNZdCqLwXH-HF5GiQUdf_qVJs2ELE6rhwUNrO6sT1S_Kr9D6XaV5DmsJlruXLqSS7mRu_k2DIMq6zYniz9qUBMef0PFpCLIw",
                "https://lh3.googleusercontent.com/aida-public/AB6AXuC0pfAiONi5Ffvpsayi6VvB3nTIDK3aBNIbYZ7K2blZQlsW2jdHlXolVvub47YnactQBhAXg3_kRGTOOKeyIBrSziAHisdarLi3VeZzis5vMLi_UHyASKFzkBbHlzDpmIzoSiQ8zkmVwgkuZZL7QpWxJfMt8SdVJ464SLt6nhFtHNWryEtSV5TO3tzkoJwXur8DJ6Bhz2h8op5nSKMjQVAdglbrvXU1NOg2HvSHaSE9O9j5moAzsdM-NFRZ3svltHAZDPv96CpPE-w",
                "https://lh3.googleusercontent.com/aida-public/AB6AXuD5KXIvYvGO7feRNZ-R7ojir3fKe0HqLhWkRnrGU4T62gBgvPpWxONJ1xz_isrNbvGCvi1DmJG6S71uIsWFo1J_FSQr_4IhJZt0LaGElfhYvF3Yxm2Ub7iMVzxua4Ad2xzmIxbXZlEe_A93bJ5vIgri27Crgm08Xe7jUP3H32ZozeKl8DNclL4AHBUoXl-EH6Uo-fthyMuSowId8C35bhtZj3Da1jABxdA5oiJjf6u0XPb8Qqpn_DFUwoASw9Igfo5s1XVPj21dA4A",
                "https://lh3.googleusercontent.com/aida-public/AB6AXuBj6iA7MgkI-VqAVHSfw6bG1yHB-RK65ngxAsb8UB1mkYMz0EB4-nt9SRZpADV82L8yXxprhoAKAmHSI7wzvlXi_tPZAgZnhRgmCxJVcuFteu9Zp4AQ-UfuTl29b6vEd1AqjfWAAY7IlhfQBgJ_Khi811UhyemG69ZDQjOI0TNa1_Sge8LpnmRMPYS9MlaQMIssULGeftZePMTRHeBRdoMbd_F9qg_lEGxJkIRbonNNYySwNT0s75Ci9bmHX7MARKJVLE8mJHH5-z8",
                "https://lh3.googleusercontent.com/aida-public/AB6AXuDivxdPonskQy7knJ4KETZKzaSuQxU37qyXSKR3Ruz0mQKfmX5-38f9zRhU_xTzUbGCxtKpjfz6whKhZKQRff8lcM9QO8ABJm-0Oh1Q5ARg94lBVPSh18Y8f7IAHV9B-hvFdNRKxIDq8MipSv6UbQ9ESV15QQ4QOrzi10TfwMPmfpzJ-VVZ3OVDtyrHGFDpqUJgUjAxRgRR2fgaLljNEMsBgWYfd-SGUdbdaOtOSOeY8a1XbLR-RPXcN0lKhqu0aHeFOfRceF32R8Y",
                "https://lh3.googleusercontent.com/aida-public/AB6AXuAWnywXwIsNbKiBcvKVvdobrDvchbM3zoG1aKCTc-20pWDo_cCHBtF-afz1mUPykrrZOVIN6Wjf7sp2I6BNGrsmatPDFlAqmP_RRZ3e--7fILmlNciHPNfxaU5XCgghAYpTvnFoSoaVo9betHL55WA4RxAKiELiefxO57JMYulgZHV4UOa8RW-_c13Z8OBZ89H--Etdu9sCwl-7iBbU5TYT_Acxi8Wwknhfuw1f3inhb5M7nIPvINlks5wjKBdoU7PTfc8-3gLvdRY",
                "https://lh3.googleusercontent.com/aida-public/AB6AXuA0h3IqvKSvyn33Z6dH_kSxOlS4rl0SA6p9tRQeTVLJ4q_yx23D0Sroi1rUyfwUry14V3HKdFdPIDKQDHQefhF4l4sxU5UlD3L0sFlbMBQjVIPdOZUWdOuvkLI4vV5Hcz_pVS8vn79mEH2Ffp-xM3v-HpV7gUgkVZmT66dFFTlYc9Z3W0XEr95Z04KrnhwGnr_Li_uM7fzvUnYeIuZIUHPauTm6EzdVSr4lvkLoZj7mBV3BYP9_L84HFChW_B0zgInGTdspucfqxds"
            ];
            $image_idx = 0;
            ?>

            <?php if (!empty($all_services)): ?>
                <?php
                // Example: Group services by type for display if a 'type' field existed
                // $services_by_type = [];
                // foreach ($all_services as $service) {
                //    $type = $service['type'] ?? 'General'; // Assuming a 'type' column
                //    $services_by_type[$type][] = $service;
                // }
                // foreach ($services_by_type as $type_name => $services_in_type):
                ?>
                    <!-- <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5"><?php echo htmlspecialchars(ucfirst($type_name)); ?> Services</h2> -->

                <div class="space-y-6">
                    <?php foreach ($all_services as $service): ?>
                        <div class="p-4">
                            <div class="flex flex-col sm:flex-row items-stretch justify-between gap-4 rounded-lg">
                                <div class="flex flex-col gap-3 flex-[2_2_0px]">
                                    <div class="flex flex-col gap-1">
                                        <h3 class="text-white text-lg sm:text-base font-bold leading-tight"><?php echo htmlspecialchars($service['name']); ?></h3>
                                        <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                                            <?php echo nl2br(htmlspecialchars($service['description'] ?? 'Details about this service.')); ?>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4 mt-auto">
                                        <span class="text-sm font-medium text-white bg-[#4a4321] px-3 py-1.5 rounded-md">
                                            $<?php echo htmlspecialchars(number_format($service['price'], 2)); ?> - <?php echo htmlspecialchars($service['duration']); ?> mins
                                        </span>
                                        <a href="<?php echo BASE_URL . '/bookinapp.php?service_id=' . $service['id']; ?>"
                                           class="text-sm font-medium text-[#232010] bg-[#fcdd53] px-3 py-1.5 rounded-md hover:bg-[#fadc70] transition-colors">
                                            Book This Service
                                        </a>
                                    </div>
                                </div>
                                <div class="w-full sm:w-1/3 lg:w-1/4 flex-1 min-h-[150px] sm:min-h-0">
                                    <div class="w-full h-full bg-center bg-no-repeat aspect-video sm:aspect-auto bg-cover rounded-lg"
                                        style='background-image: url("<?php echo $service_images[$image_idx % count($service_images)]; $image_idx++; ?>");'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php // endforeach; // End of services_by_type loop ?>
            <?php else: ?>
                <p class="text-[#cdc28e] p-4">No services currently available. Please check back later.</p>
            <?php endif; ?>


            <div class="flex px-4 py-6 justify-center">
              <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>"
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-[#fcdd53] text-[#232010] text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]"
              >
                <span class="truncate">Book an Appointment</span>
              </a>
            </div>
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
            const hamburgerButton = document.getElementById('hamburger-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (hamburgerButton && mobileMenu) {
                hamburgerButton.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
  </body>
</html>
