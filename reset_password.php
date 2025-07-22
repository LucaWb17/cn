<?php
require_once 'config.php';

$token = $_GET['token'] ?? '';
$password = $confirm_password = "";
$password_err = $confirm_password_err = $token_err = "";

if (empty($token)) {
    $token_err = "No token provided.";
} else {
    $sql = "SELECT id, reset_token_expires_at FROM users WHERE reset_token = ? AND reset_token_expires_at > NOW()";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $expires);
            $stmt->fetch();
        } else {
            $token_err = "Invalid or expired token.";
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($token_err)) {

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Passwords did not match.";
        }
    }

    if (empty($password_err) && empty($confirm_password_err)) {
        $hashed_password = hash_password($password);
        $update_sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?";

        if ($update_stmt = $mysqli->prepare($update_sql)) {
            $update_stmt->bind_param("si", $hashed_password, $id);

            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = "Your password has been reset successfully. Please log in.";
                redirect(BASE_URL . '/login.php');
            } else {
                $token_err = "Oops! Something went wrong. Please try again later.";
            }
            $update_stmt->close();
        }
    }
}
?>
<html>
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Space+Grotesk%3Awght%40400%3B500%3B700"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CN Auto</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  </head>
  <body>
    <div class="relative flex size-full min-h-screen flex-col bg-[#221d11] dark group/design-root overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#483e23] px-4 sm:px-10 py-3">
          <div class="flex items-center gap-4 text-white">
            <a href="<?php echo BASE_URL . '/home.php'; ?>" class="flex items-center gap-4">
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
          </div>
        </header>
        <div class="px-4 sm:px-10 md:px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col w-full sm:w-[512px] max-w-[512px] py-5 flex-1">
            <h2 class="text-white tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-3 pt-5">Reset Password</h2>

            <?php
            if(!empty($token_err)){
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . $token_err . '</div>';
            }
            ?>
            <?php if(empty($token_err)): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?token=<?php echo htmlspecialchars($token); ?>" method="post">
                <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                    <input
                      type="password"
                      name="password"
                      placeholder="New Password"
                      class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border-none bg-[#483e23] focus:border-none h-14 placeholder:text-[#caba91] p-4 text-base font-normal leading-normal <?php echo (!empty($password_err)) ? 'border-red-500' : ''; ?>"
                    />
                    <span class="text-red-500 text-xs italic"><?php echo $password_err; ?></span>
                  </label>
                </div>
                <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                  <label class="flex flex-col min-w-40 flex-1">
                    <input
                      type="password"
                      name="confirm_password"
                      placeholder="Confirm New Password"
                      class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-white focus:outline-0 focus:ring-0 border-none bg-[#483e23] focus:border-none h-14 placeholder:text-[#caba91] p-4 text-base font-normal leading-normal <?php echo (!empty($confirm_password_err)) ? 'border-red-500' : ''; ?>"
                    />
                    <span class="text-red-500 text-xs italic"><?php echo $confirm_password_err; ?></span>
                  </label>
                </div>
                <div class="flex px-4 py-3">
                  <button
                    type="submit"
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-5 flex-1 bg-[#f4c653] text-[#221d11] text-base font-bold leading-normal tracking-[0.015em]"
                  >
                    <span class="truncate">Reset Password</span>
                  </button>
                </div>
            </form>
            <?php endif; ?>
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
