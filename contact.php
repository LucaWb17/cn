<?php
require_once 'config.php';
require_once 'utils/functions.php';
$page_title = "Contattaci - CN Auto";
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Space+Grotesk%3Awght%40400%3B500%3B700"
    />
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        /* Stile per il campo honeypot, lo rende veramente invisibile */
        .hidden-honeypot {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            height: 0;
            width: 0;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="relative flex size-full min-h-screen flex-col bg-[#232010] dark group/design-root overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <!-- Header -->
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#4a4321] px-4 sm:px-10 py-3">
          <a href="<?php echo BASE_URL . '/home.php'; ?>" class="flex items-center gap-4 text-white">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor"></path>
              </svg>
            </div>
            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">CN Auto</h2>
          </a>
          <div class="flex flex-1 justify-end items-center gap-2 sm:gap-6">
            <nav class="hidden sm:flex items-center gap-6">
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/home.php'; ?>">Home</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="<?php echo BASE_URL . '/servizi.php'; ?>">Services</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53]" href="#">About</a>
              <a class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] aria-[current=page]:text-[#fcdd53] aria-[current=page]:font-bold" href="<?php echo BASE_URL . '/contact.php'; ?>" aria-current="page">Contact</a>
            </nav>
            <div class="flex items-center gap-2">
                <?php if (is_logged_in()): ?>
                    <a href="<?php echo is_admin() ? BASE_URL . '/dashboardAdmin.php' : BASE_URL . '/areacliente.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors"><?php echo is_admin() ? 'Admin' : 'My Account'; ?></a>
                    <a href="<?php echo BASE_URL . '/logout.php'; ?>" class="flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a]">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL . '/login.php'; ?>" class="text-white text-sm font-medium leading-normal hover:text-[#fcdd53] px-3 py-2 rounded-lg bg-opacity-50 hover:bg-opacity-75 transition-colors">Log In</a>
                    <a href="<?php echo BASE_URL . '/createaccount.php'; ?>" class="flex min-w-[80px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#4a4321] text-white text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#5f552a]">Sign Up</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL . '/bookinapp.php'; ?>"
                  class="flex min-w-[80px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-3 bg-[#fcdd53] text-[#232010] text-xs sm:text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70]"
                >
                  <span class="truncate">Book Now</span>
                </a>
            </div>
          </div>
        </header>

        <!-- Main Content -->
        <main class="px-4 sm:px-10 md:px-20 lg:px-40 flex flex-1 justify-center py-10">
          <div class="layout-content-container flex flex-col w-full max-w-2xl">
            <h1 class="text-white text-3xl sm:text-4xl font-bold leading-tight tracking-tight text-center mb-8">Contattaci</h1>
            <p class="text-[#cdc28e] text-center mb-10">
                Hai domande o hai bisogno di assistenza? Compila il modulo sottostante e ti risponderemo il prima possibile.
            </p>

            <div id="contactFormFeedback" class="hidden p-4 mb-6 rounded-md text-sm"></div>

            <form id="contactForm" class="space-y-6 bg-[#2c281a] p-6 sm:p-8 rounded-lg shadow-lg">
                <div>
                    <label for="contact_name" class="block text-sm font-medium text-[#cdc28e] mb-1">Nome Completo</label>
                    <input type="text" name="contact_name" id="contact_name" required
                           class="w-full p-3 rounded-md bg-[#353017] border border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53] placeholder:text-gray-500"
                           placeholder="Il tuo nome completo">
                    <p id="error_contact_name" class="text-red-400 text-xs mt-1"></p>
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-[#cdc28e] mb-1">La Tua Email</label>
                    <input type="email" name="contact_email" id="contact_email" required
                           class="w-full p-3 rounded-md bg-[#353017] border border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53] placeholder:text-gray-500"
                           placeholder="latuaemail@example.com">
                    <p id="error_contact_email" class="text-red-400 text-xs mt-1"></p>
                </div>

                <div>
                    <label for="contact_subject" class="block text-sm font-medium text-[#cdc28e] mb-1">Oggetto</label>
                    <input type="text" name="contact_subject" id="contact_subject" required
                           class="w-full p-3 rounded-md bg-[#353017] border border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53] placeholder:text-gray-500"
                           placeholder="Oggetto del tuo messaggio">
                    <p id="error_contact_subject" class="text-red-400 text-xs mt-1"></p>
                </div>

                <div>
                    <label for="contact_message" class="block text-sm font-medium text-[#cdc28e] mb-1">Messaggio</label>
                    <textarea name="contact_message" id="contact_message" rows="5" required
                              class="w-full p-3 rounded-md bg-[#353017] border border-[#4a4321] text-white focus:border-[#fcdd53] focus:ring-[#fcdd53] placeholder:text-gray-500"
                              placeholder="Scrivi qui il tuo messaggio..."></textarea>
                    <p id="error_contact_message" class="text-red-400 text-xs mt-1"></p>
                </div>

                <!-- Honeypot field for basic spam protection -->
                <div class="hidden-honeypot">
                    <label for="website_url">Website (non compilare)</label>
                    <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                </div>

                <div class="text-center">
                    <button type="submit" id="submitContactForm"
                            class="min-w-[150px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-8 bg-[#fcdd53] text-[#232010] text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#fadc70] transition-colors">
                        Invia Messaggio
                    </button>
                </div>
            </form>
          </div>
        </main>

        <!-- Footer -->
        <footer class="flex justify-center border-t border-solid border-t-[#4a4321] mt-auto py-5 bg-[#232010]">
            <div class="flex max-w-[960px] flex-1 flex-col px-4">
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-3 @[480px]:flex-row @[480px]:justify-around mb-4">
                    <a class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#fcdd53]" href="#">Privacy Policy</a>
                    <a class="text-[#cdc28e] text-sm sm:text-base font-normal leading-normal min-w-32 sm:min-w-40 hover:text-[#fcdd53]" href="#">Terms of Service</a>
                </div>
                <p class="text-[#cdc28e] text-xs sm:text-sm font-normal leading-normal text-center">© <?php echo date("Y"); ?> CN Auto. All rights reserved.</p>
            </div>
        </footer>
      </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const feedbackDiv = document.getElementById('contactFormFeedback');
    const submitButton = document.getElementById('submitContactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitButton.disabled = true;
            submitButton.textContent = 'Invio...';

            // Clear previous errors
            document.querySelectorAll('p[id^="error_contact_"]').forEach(el => el.textContent = '');
            feedbackDiv.className = 'hidden p-4 mb-6 rounded-md text-sm';
            feedbackDiv.textContent = '';

            const formData = new FormData(this);

            fetch('<?php echo BASE_URL . "/process_contact_form.php"; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    feedbackDiv.textContent = data.message || 'Messaggio inviato con successo!';
                    feedbackDiv.className = 'p-4 mb-6 rounded-md text-sm bg-green-600/30 border border-green-500 text-green-300';
                    contactForm.reset();
                } else {
                    feedbackDiv.textContent = data.message || 'Si è verificato un errore. Riprova.';
                    feedbackDiv.className = 'p-4 mb-6 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
                    if (data.errors) {
                        for (const key in data.errors) {
                            const errorField = document.getElementById(`error_${key}`); // Assumes error keys match input names
                            if (errorField) {
                                errorField.textContent = data.errors[key];
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                feedbackDiv.textContent = 'Errore di rete. Riprova più tardi.';
                feedbackDiv.className = 'p-4 mb-6 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Invia Messaggio';
                feedbackDiv.classList.remove('hidden');
            });
        });
    }
});
</script>
</body>
</html>
