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
    require_once 'config.php'; // Defines BASE_URL, $mysqli, session_start()
    require_once 'register.php'; // Contains the PHP logic for registration, defines error variables like $name_err
    require_once 'utils/functions.php'; // For display_flash_message()
    ?>
    <div class="relative flex size-full min-h-screen flex-col bg-[#221d11] dark group/design-root overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#483e23] px-4 sm:px-10 py-3">
          <a href="<?php echo BASE_URL . '/home.php'; ?>" class="flex items-center gap-4 text-white">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z"
                  fill="currentColor"
                ></path>
              </svg>
            </div>
            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">CN Auto</h2>
          </a>
          <div class="flex flex-1 justify-end items-center gap-2 sm:gap-8">
            <nav class="hidden sm:flex items-center gap-9">
              <a class="text-white text-sm font-medium leading-normal hover:text-[#f4c653]" href="<?php echo BASE_URL . '/home.php'; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'page' : ''; ?>">Home</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#f4c653]" href="<?php echo BASE_URL . '/servizi.php'; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'servizi.php') ? 'page' : ''; ?>">Services</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#f4c653]" href="#">About</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#f4c653]" href="<?php echo BASE_URL . '/contact.php'; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'page' : ''; ?>">Contact</a>
            </nav>
            <a href="<?php echo BASE_URL . '/login.php'; ?>"
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 bg-[#483e23] text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5a4e2e]"
            >
              <span class="truncate">Log In</span>
            </a>
          </div>
        </header>
        <main class="px-4 sm:px-10 md:px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col w-full sm:w-[512px] max-w-[512px] py-5 flex-1">
            <h2 class="text-white tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-3 pt-5">Create your account</h2>

            <?php
            // Display general error from register.php if it exists
            if(!empty($general_err)){
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . $general_err . '</div>';
            }
            // Display flash messages from session (e.g., from other redirects)
            echo display_flash_message();
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                    <input
                      type="text"
                      name="name"
                      placeholder="Full Name"
                      class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border-none bg-[#483e23] focus:border-none h-14 placeholder:text-[#caba91] p-4 text-base font-normal leading-normal <?php echo (!empty($name_err)) ? 'border-red-500' : ''; ?>"
                      value="<?php echo isset($name) ? $name : ''; ?>"
                    />
                    <span class="text-red-500 text-xs italic pl-1 pt-1"><?php echo $name_err; ?></span>
                  </label>
                </div>
                <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                    <input
                      type="email"
                      name="email"
                      placeholder="Email"
                      class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border-none bg-[#483e23] focus:border-none h-14 placeholder:text-[#caba91] p-4 text-base font-normal leading-normal <?php echo (!empty($email_err)) ? 'border-red-500' : ''; ?>"
                      value="<?php echo isset($email) ? $email : ''; ?>"
                    />
                     <span class="text-red-500 text-xs italic pl-1 pt-1"><?php echo $email_err; ?></span>
                  </label>
                </div>
                <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                    <input
                      type="password"
                      name="password"
                      placeholder="Password"
                      class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border-none bg-[#483e23] focus:border-none h-14 placeholder:text-[#caba91] p-4 text-base font-normal leading-normal <?php echo (!empty($password_err)) ? 'border-red-500' : ''; ?>"
                    />
                    <span class="text-red-500 text-xs italic pl-1 pt-1"><?php echo $password_err; ?></span>
                  </label>
                </div>
                <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                    <input
                      type="password"
                      name="confirm_password"
                      placeholder="Confirm Password"
                      class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border-none bg-[#483e23] focus:border-none h-14 placeholder:text-[#caba91] p-4 text-base font-normal leading-normal <?php echo (!empty($confirm_password_err)) ? 'border-red-500' : ''; ?>"
                    />
                    <span class="text-red-500 text-xs italic pl-1 pt-1"><?php echo $confirm_password_err; ?></span>
                  </label>
                </div>
                <div class="flex px-4 py-3">
                  <button
                    type="submit"
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-10 px-4 flex-1 bg-[#f4c653] text-[#221d11] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#e5b843]"
                  >
                    <span class="truncate">Create Account</span>
                  </button>
                </div>
            </form>
            <p class="text-[#caba91] text-sm font-normal leading-normal pb-3 pt-1 px-4 text-center">
                Already have an account? <a href="<?php echo BASE_URL . '/login.php'; ?>" class="underline hover:text-[#f4c653]">Log in</a>
            </p>
          </div>
        </main>
        <footer class="flex justify-center border-t border-solid border-t-[#483e23] mt-auto py-5 bg-[#221d11]">
            <div class="flex max-w-[960px] flex-1 flex-col px-4">
                 <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-3 @[480px]:flex-row @[480px]:justify-around mb-4">
                    <a class="text-[#caba91] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#f4c653]" href="#">Privacy Policy</a>
                    <a class="text-[#caba91] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#f4c653]" href="#">Terms of Service</a>
                    <a class="text-[#caba91] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#f4c653]" href="<?php echo BASE_URL . '/contact.php'; ?>">Contact Us</a>
                </div>
                <p class="text-[#caba91] text-xs sm:text-sm font-normal leading-normal text-center">© <?php echo date("Y"); ?> CN Auto. All rights reserved.</p>
            </div>
        </footer>
      </div>
    </div>
  </body>
</html>
