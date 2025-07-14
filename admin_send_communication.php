<?php
require_once 'config.php';
require_once 'auth_check.php';
require_admin(); // Solo gli admin possono inviare comunicazioni
require_once 'utils/functions.php'; // Per send_email e sanitize_input

$response = ['success' => false, 'message' => '', 'errors' => []];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    $subject = isset($_POST['communication_subject']) ? sanitize_input($_POST['communication_subject']) : '';
    $message_body = isset($_POST['communication_message']) ? sanitize_input($_POST['communication_message']) : ''; // Sanitize, nl2br will be used for HTML email
    $discount_code = isset($_POST['communication_discount_code']) ? sanitize_input($_POST['communication_discount_code']) : '';

    // Validazione Backend
    if (empty($user_id)) {
        $response['message'] = 'User ID mancante.';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    if (empty($subject)) {
        $response['errors']['communication_subject'] = 'L\'oggetto è obbligatorio.';
    }
    if (empty($message_body)) {
        $response['errors']['communication_message'] = 'Il messaggio è obbligatorio.';
    }

    if (empty($response['errors'])) {
        // Recupera l'email dell'utente dal database
        $user_email = '';
        $user_name = 'Cliente';
        $stmt_user = $mysqli->prepare("SELECT name, email FROM users WHERE id = ?");
        if ($stmt_user) {
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            if ($user_data = $result_user->fetch_assoc()) {
                $user_email = $user_data['email'];
                $user_name = $user_data['name'];
            } else {
                $response['message'] = 'Utente non trovato.';
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            $stmt_user->close();
        } else {
            $response['message'] = 'Errore nel recuperare i dati utente.';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        if (empty($user_email)) {
             $response['message'] = 'Indirizzo email dell\'utente non trovato.';
             header('Content-Type: application/json');
             echo json_encode($response);
             exit;
        }

        // Componi il corpo dell'email in HTML
        $email_html_content = "<h2>Messaggio da " . htmlspecialchars(FROM_NAME) . "</h2>";
        $email_html_content .= "<p>Ciao " . htmlspecialchars($user_name) . ",</p>";
        $email_html_content .= "<p>Hai ricevuto il seguente messaggio dall'amministrazione:</p>";
        $email_html_content .= "<div>" . nl2br(htmlspecialchars($message_body)) . "</div>";

        if (!empty($discount_code)) {
            $email_html_content .= "<hr style='margin: 20px 0;'>";
            $email_html_content .= "<p><strong>Codice Sconto Speciale per Te:</strong> <strong style='color: #E0A800; font-size: 1.1em;'>" . htmlspecialchars($discount_code) . "</strong></p>";
            $email_html_content .= "<p>Puoi utilizzare questo codice per la tua prossima prenotazione!</p>";
        }

        $email_html_content .= "<hr style='margin: 20px 0;'>";
        $email_html_content .= "<p>Cordiali saluti,<br>Il Team di " . htmlspecialchars(FROM_NAME) . "</p>";

        // Invia l'email
        // FROM_EMAIL e FROM_NAME sono definiti in config.php
        if (send_email($user_email, $subject, $email_html_content, FROM_EMAIL, FROM_NAME)) {
            $response['success'] = true;
            $response['message'] = 'Messaggio inviato con successo a ' . htmlspecialchars($user_name) . '.';
            // Qui si potrebbe aggiungere la logica per salvare la comunicazione nel database, se avessimo la tabella communications_log
        } else {
            $response['message'] = "Siamo spiacenti, c'è stato un problema nell'invio del messaggio a " . htmlspecialchars($user_name) . ". Riprova più tardi.";
            error_log("Admin communication email failed to send. To: $user_email, Subject: $subject");
        }
    } else {
        $response['message'] = 'Per favore, correggi gli errori nel modulo.';
    }
} else {
    $response['message'] = 'Metodo di richiesta non valido.';
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
