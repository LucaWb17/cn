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
    <?php require_once 'config.php'; ?>
    <?php require_once 'utils/functions.php'; ?>
    <div class="relative flex size-full min-h-screen flex-col bg-[#232010] dark group/design-root overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
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
            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">CN</h2>
          </a>
          <div class="flex flex-1 justify-end items-center gap-2 sm:gap-6">
            <nav class="hidden sm:flex items-center gap-6">
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/servizi.php'; ?>">Services</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="#">About</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="#">Contact</a>
            </nav>
            <div class="flex items-center gap-2">
                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <a href="<?php echo BASE_URL . '/dashboardAdmin.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors">Admin</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL . '/areacliente.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors">My Account</a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a]">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL . '/login.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors">Log In</a>
                    <a href="<?php echo BASE_URL . '/createaccount.php'; ?>" class="flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a]">
                        Sign Up
                    </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>"
                  class="flex min-w-[80px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#fcdd53] text-[#232010] text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]"
                >
                  <span class="truncate">Book Now</span>
                </a>
            </div>
          </div>
        </header>
        <main class="px-4 sm:px-10 md:px-20 lg:px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col w-full max-w-[960px] flex-1">
            <?php echo display_flash_message(); ?>
            <div class="@container">
              <div class="@[480px]:p-4">
                <div
                  class="flex min-h-[380px] sm:min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 @[480px]:rounded-lg items-start justify-end px-4 pb-10 @[480px]:px-10"
                  style='background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.4) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuDrytzSVrrpYgKwXjWLU4tE4bUbpNQHr9VxbozXQtaNfinwYLGUq8EqoeHjn7x6JLx-N7UPX-P6dg8nDJrp-FgG92Z-hB8D1LhvQ4yihg0O8c3T4F0-IIG8qKuu3dH0sj5Iwpjd-aRdnTlDxBPeBYPsWv3KW_1T_gQzun4pue86Uixt_mckNHtLHtNu7nrP6cptghAlmihz3Rylov7KOH3P1HzBC-8IgoJ4didDTT_QsnCQeB2Yahqo0UyMtOtZKQ8qSnyNaoyKXcI");'
                >
                  <div class="flex flex-col gap-2 text-left">
                    <h1
                      class="text-white text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]"
                    >
                      Precision Vehicle Care
                    </h1>
                    <h2 class="text-white text-xs sm:text-sm font-normal leading-normal @[480px]:text-base @[480px]:font-normal @[480px]:leading-normal">
                      Experience the difference with our expert mechanics and state-of-the-art facility. Book your appointment today.
                    </h2>
                  </div>
                  <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>"
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 @[480px]:h-12 @[480px]:px-5 bg-[#fcdd53] text-[#232010] text-sm font-bold leading-normal tracking-[0.015em] @[480px]:text-base @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em] hover:bg-[#fadc70]"
                  >
                    <span class="truncate">Book Now</span>
                  </a>
                </div>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Meet Our Team</h2>
            <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
              <div class="flex items-stretch p-4 gap-3 overflow-x-auto">
                <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-[150px] sm:min-w-40">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD4TvX6Iy9TBktmrhnwe-9p1km5552Il8MPWqqJTMwiFaSNZdY9oBUGyROUArzR6Q_0XfibD_4jBtk1MDoogl79i3WYtVmEAMLs51x1f4NItVncnCebZm1OBabbtZ7Mt-w76SwJPpVpN85CGMPIiR3o5odthD48w7KUkhQ_kEiPzsEz79DBhpa9JhfBQ48egRRz5M3DdaLVfLLepHExPYlDK83MyyiyMl8lu9zzvJ7UzSk1tK8wi5Qgv6Z9r5LeDkOgeoUyeLIGHXo");'
                  ></div>
                  <div>
                    <p class="text-white text-sm sm:text-base font-medium leading-normal">Alex</p>
                    <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal">Lead Technician</p>
                  </div>
                </div>
                <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-[150px] sm:min-w-40">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBca7qXHRF_jZHGjZvL7P9uEcGW6nzbi-PAlTPSbXKvuAg-V_GyvnoXczdMckEgI7t9W15hVkmaOPOpwoQSuMa0ZBlK6dwBhC3IyOg6roXXD6hwlYeLkV4mFkwR0_xt-MnHmvxTsGFv-KtsV-9YtHG-dsp7y5naIt8kJiFoyywTz7A_9NWFAMd6ZE8mRCgzSVBsI6n3wPxqJx12PIbu3VOsU8gY_zmIIcYy8hgRRSfe_rDLTxKYiP8c5fG9cxvJRoH6XpZ_AMMMJZ8");'
                  ></div>
                  <div>
                    <p class="text-white text-sm sm:text-base font-medium leading-normal">Ethan</p>
                    <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal">Specialist Mechanic</p>
                  </div>
                </div>
                <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-[150px] sm:min-w-40">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCncWdrimGEqnYBMioU7dV4rlpiLLtK7uk2w2gPG4OOABXZLKKxZzlOpH_wNWCRbnlADYh3bHzECfVYDFOXayyAcQL0NT141wTvPXLb3nbDguhLH4N9xUK-GncPFzEQ-pXUL06TvJhaXMQ2k4RMIg0eF-Je2WbEOBlmeWDACmNtU9-Kgmx6kydJG6xq1R07c6Tacf6dlF1PITlZI0r9vbT29zP4GP-Zvuexwsn1bJm94DT1q-DdcamMpXjVNZyL8PP791uS1AiOLnI");'
                  ></div>
                  <div>
                    <p class="text-white text-sm sm:text-base font-medium leading-normal">Liam</p>
                    <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal">Diagnostic Expert</p>
                  </div>
                </div>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Featured Services</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBC22u31CRi66_EvYnXp_FfV3KflaxRv-TrJHTaYJn7HtzEPcksL_GdSNIopJ7-IgZgNHcJuNbZm8mzNoSud_6qhz1IZoXI4XJDoIHHcNJU2tJYLC6N_qoAkc9Cfj9IMEinYRa-6uwcaj9XEZvNtFLbDU-nobhvRW1soOQ9vBdgYaw3Us4VJsmaHnPh9BBPArb4pkgnIe5DCmWkzIrfA4e564LkG0yuT8RmlNcDQhzZkF9EJXCLGRq9nFEYj5Iyj0jic27NMrtVXsE");'
                ></div>
                <div>
                  <p class="text-white text-sm sm:text-base font-medium leading-normal">Vehicle Inspection</p>
                  <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal">Comprehensive checks for safety and performance.</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCX41KEras7VldIrvW8iRcTaDSJBS41O8PToIbidXARfwPee03OLsdfGtoz04F1FDG6UQBPB_aQH65gAF5RMM__EFdfYtn3R4o7IbSabpMALJZzlkQsNwGG-1HAbkxDx0ozsy5pwLzHVn24T9D63DEqc5Dyvf3Ym6QufUBJsvDf8kl3PzDjgo3d8fNLQbjP1IpWraIWUTNV3LSoGD7m5q-z1m9_H9q2osw6VGSjo46q1L4s6dKnai1br3ukIcP86SI3ZQ2XobSokr0");'
                ></div>
                <div>
                  <p class="text-white text-sm sm:text-base font-medium leading-normal">Maintenance</p>
                  <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal">Routine servicing to keep your vehicle in top condition.</p>
                </div>
              </div>
              <div class="flex flex-col gap-3 pb-3">
                <div
                  class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDuo3s_HzGJQh4poKDIxpoNLuesec1WPtH_PVYEbZrDJgdM5QZJ1xniL5_g_T93oxNokZ67UhseJuO4Fn3WnfSlxhEEWO1wd-KZWvh3JCKT1Q1T0LUWVTs44iLRnV4wEKg_gDqILRT0ia6JXP1j-Has4tNq5jXMA7F2CI-tYdyFGXtvntjamKxJK4y68d0cFriFXm8LgfohPajXAV8wppORHovy0MEFED4dTASJzpKgrYDS3KRJAplVQ1EabDGTzyHrNDhHttK0Npw");'
                ></div>
                <div>
                  <p class="text-white text-sm sm:text-base font-medium leading-normal">Repair</p>
                  <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal">Expert repairs using quality parts.</p>
                </div>
              </div>
            </div>
          </div>
        </main>
        <footer class="flex justify-center border-t border-solid border-t-[#4a4321]">
          <div class="flex max-w-[960px] flex-1 flex-col">
            <div class="flex flex-col gap-6 px-5 py-10 text-center @container">
              <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-3 @[480px]:flex-row @[480px]:justify-around">
                <a class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#fcdd53]" href="#">Privacy Policy</a>
                <a class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#fcdd53]" href="#">Terms of Service</a>
              </div>
              <p class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal">© <?php echo date("Y"); ?> CN Auto. All rights reserved.</p>
            </div>
          </div>
        </footer>
      </div>
    </div>
  </body>
</html>
