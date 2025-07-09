```markdown
# CN Auto - Applicazione Gestione Prenotazioni Autofficina

CN Auto è un'applicazione web progettata per facilitare la gestione delle prenotazioni, dei clienti, dei servizi e dei veicoli per un'autofficina. Offre un pannello di amministrazione completo e un'area clienti dedicata.

## Funzionalità Principali

### Per Tutti gli Utenti (Pubblico)
*   **Homepage:** Pagina di benvenuto con informazioni generali e call to action per la prenotazione.
*   **Visualizzazione Servizi:** Lista dei servizi offerti dall'autofficina con dettagli e prezzi.
*   **Modulo di Contatto:** Permette agli utenti di inviare messaggi all'amministrazione.
*   **Prenotazione Appuntamento (come Ospite):** Gli utenti non registrati possono prenotare appuntamenti fornendo i loro dettagli.

### Area Clienti (Utenti Registrati)
*   **Registrazione e Login:** Creazione di un account e accesso sicuro.
*   **Dashboard Cliente:** Panoramica personale con riepilogo prenotazioni e veicoli.
*   **Gestione Prenotazioni:**
    *   Visualizzazione delle prenotazioni imminenti e passate.
    *   Visualizzazione dettagli singola prenotazione.
*   **Gestione Veicoli:**
    *   Aggiunta, modifica ed eliminazione dei propri veicoli.
*   **Impostazioni Account:**
    *   Aggiornamento dei dettagli del profilo (nome, email).
    *   Modifica della password.

### Pannello di Amministrazione (Admin)
*   **Dashboard Admin:** Panoramica generale con statistiche chiave (es. numero di appuntamenti per stato, utenti totali, servizi totali).
*   **Gestione Appuntamenti Completa:**
    *   Visualizzazione di tutti gli appuntamenti con filtri e dettagli.
    *   Modifica dello stato di un appuntamento (pending, confirmed, completed, cancelled, no-show).
    *   Eliminazione appuntamenti.
    *   **Aggiunta Prenotazioni Manuale:** L'admin può creare appuntamenti per conto di utenti esistenti o nuovi ospiti, specificando tutti i dettagli.
*   **Gestione Utenti:**
    *   Visualizzazione di tutti gli utenti registrati.
    *   Modifica del ruolo di un utente (user/admin).
    *   **Comunicazione Diretta:** Invio di messaggi email a utenti specifici (con possibilità di includere codici sconto).
*   **Gestione Servizi:**
    *   Aggiunta, modifica ed eliminazione dei servizi offerti dall'officina (descrizione, prezzo, durata).
    *   Controllo delle dipendenze prima dell'eliminazione di un servizio (non si può eliminare se collegato a prenotazioni).
*   **Impostazioni Admin:**
    *   Modifica della propria password.

### Funzionalità Comuni
*   **Notifiche Email:**
    *   Conferma di prenotazione (cliente e admin).
    *   Notifiche di aggiornamento stato prenotazione (gestite da admin).
    *   Comunicazioni dirette da admin a utente.
*   **Ricerca e Filtri:** (Implementati parzialmente, più evidenti nel backend, frontend admin li usa per popolare le viste).

## Tecnologie Utilizzate

*   **Backend:**
    *   PHP (approccio procedurale, versione non specificata ma compatibile con `password_hash`, `mysqli`)
    *   MySQL (tramite estensione `mysqli`) per il database.
*   **Frontend:**
    *   HTML5
    *   Tailwind CSS (v3, caricato via CDN con plugin `forms` e `container-queries`) per lo styling.
    *   JavaScript (Vanilla ES6+) per interattività dinamica, chiamate AJAX (`fetch`), manipolazione del DOM.
    *   Flatpickr.js (via CDN) per i selettori di data e ora.
*   **Altro:**
    *   Google Fonts (Noto Sans, Space Grotesk)
    *   UI Avatars (per placeholder avatar cliente)

## Prerequisiti per l'Installazione

*   Web server con supporto PHP (es. Apache, Nginx).
*   PHP (versione >= 7.0 raccomandata per `password_hash` e altre funzionalità moderne).
*   Database MySQL o MariaDB.
*   Accesso a Internet per il caricamento delle risorse CDN (Tailwind CSS, Flatpickr).

## Installazione

1.  **Clonare la Repository:**
    ```bash
    git clone <URL_DELLA_REPOSITORY>
    cd <NOME_CARTELLA_PROGETTO>
    ```
2.  **Configurazione Database:**
    *   Crea un database MySQL (es. `cn_booking_app`).
    *   Importa la struttura del database. Se non è fornito un file `.sql`, dovrai creare manualmente le tabelle basandoti sulla struttura inferita (vedi sezione Struttura Database Inferita).
3.  **Configurazione Applicazione:**
    *   Copia `config.php` (se non esiste già un file di esempio da rinominare) e modificalo con le tue credenziali del database:
        ```php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'tuo_utente_db');
        define('DB_PASSWORD', 'tua_password_db');
        define('DB_NAME', 'cn_booking_app'); // O il nome che hai scelto

        // Aggiorna anche le costanti email se necessario
        define('ADMIN_EMAIL', 'tua_admin_email@example.com');
        define('FROM_EMAIL', 'noreply@tuodominio.com');
        define('FROM_NAME', 'Nome Tua Autofficina');
        ```
    *   Assicurati che `BASE_URL` in `config.php` sia rilevato correttamente o impostalo manualmente se necessario, specialmente se l'applicazione è in una sottocartella.
4.  **Permessi:**
    *   Assicurati che il web server abbia i permessi di scrittura per eventuali cartelle di upload o log, se implementate. (Non evidenti dall'analisi attuale).
5.  **Accesso:**
    *   Apri l'applicazione nel tuo browser.
    *   Per accedere al pannello admin, dovrai prima creare un utente e poi modificare il suo ruolo a 'admin' direttamente nel database, oppure registrare un primo utente e poi assegnargli il ruolo admin tramite un'interfaccia se disponibile (non evidente come primo admin setup).

## Struttura del Database Inferita

Basandosi sull'analisi del codice, le tabelle principali dovrebbero essere:

*   **`users`**:
    *   `id` (INT, PK, AI)
    *   `name` (VARCHAR)
    *   `email` (VARCHAR, UNIQUE)
    *   `password` (VARCHAR - hashed)
    *   `role` (VARCHAR, es. 'user', 'admin', DEFAULT 'user')
    *   `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
*   **`services`**:
    *   `id` (INT, PK, AI)
    *   `name` (VARCHAR)
    *   `description` (TEXT, NULLABLE)
    *   `price` (DECIMAL)
    *   `duration` (INT - in minuti)
*   **`bookings`**:
    *   `id` (INT, PK, AI)
    *   `user_id` (INT, FK to users.id, NULLABLE - per ospiti)
    *   `guest_name` (VARCHAR, NULLABLE)
    *   `guest_email` (VARCHAR, NULLABLE)
    *   `guest_phone` (VARCHAR, NULLABLE)
    *   `service_id` (INT, FK to services.id)
    *   `vehicle_id` (INT, FK to vehicles.id, NULLABLE - se il veicolo non è salvato o è di un ospite)
    *   `vehicle_make` (VARCHAR - snapshot)
    *   `vehicle_model` (VARCHAR - snapshot)
    *   `vehicle_year` (INT - snapshot)
    *   `vehicle_license_plate` (VARCHAR - snapshot)
    *   `booking_date` (DATE)
    *   `booking_time` (TIME)
    *   `status` (VARCHAR, es. 'pending', 'confirmed', 'completed', 'cancelled', 'no-show')
    *   `notes` (TEXT, NULLABLE)
    *   `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
*   **`vehicles`**:
    *   `id` (INT, PK, AI)
    *   `user_id` (INT, FK to users.id)
    *   `make` (VARCHAR)
    *   `model` (VARCHAR)
    *   `year` (INT)
    *   `license_plate` (VARCHAR)
    *   `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)


## Struttura del Progetto

```
/
├── admin_add_booking.php        # API: Admin aggiunge prenotazione
├── admin_send_communication.php # API: Admin invia email a utente
├── areacliente.php              # Pagina: Area Riservata Cliente
├── auth_check.php               # Logica: Controllo autenticazione e ruolo admin
├── book_appointment.php         # API: Logica per creare prenotazione (da utente/guest)
├── bookinapp.php                # Pagina: Form principale di prenotazione
├── config.php                   # Configurazione (DB, URL base, email, funzioni base)
├── contact.php                  # Pagina: Contatti con form
├── createaccount.php            # Pagina: Form di registrazione (include register.php)
├── dashboardAdmin.php           # Pagina: Pannello Amministrazione
├── get_all_bookings.php         # API: Admin recupera tutte le prenotazioni (JSON)
├── get_bookings.php             # API: Cliente recupera le proprie prenotazioni (JSON)
├── home.php                     # Pagina: Homepage pubblica
├── login.php                    # Pagina: Form di login
├── logout.php                   # Logica: Effettua il logout
├── manage_bookings.php          # API: Admin gestisce prenotazioni (update stato, delete) (JSON)
├── manage_services.php          # API: Admin gestisce servizi (CRUD) (JSON)
├── manage_users.php             # API: Admin gestisce utenti (ruoli, cambio pass admin) (JSON)
├── manage_vehicles.php          # API: Cliente/Admin gestisce veicoli (CRUD per cliente, GET per admin) (JSON)
├── partials/                    # Frammenti di UI e logica inclusi nelle pagine principali
│   ├── admin_add_booking_form.php # Form per admin per aggiungere prenotazioni
│   ├── admin_appointments.php   # Tabella e logica JS per gestione appuntamenti admin
│   ├── admin_overview.php       # Contenuto tab overview admin (con JS per stats)
│   ├── admin_services.php       # Tabella e modale JS per gestione servizi admin
│   ├── admin_settings.php       # Form per admin per cambiare password (con JS)
│   ├── admin_users.php          # Tabella e modale JS per gestione utenti e comunicazioni
│   ├── cliente_bookings.php     # Visualizzazione prenotazioni cliente (con modale JS)
│   ├── cliente_overview.php     # Contenuto tab overview cliente
│   ├── cliente_settings.php     # Form per cliente per aggiornare profilo/password
│   └── cliente_vehicles.php     # Visualizzazione e gestione veicoli cliente (con modale JS)
├── process_contact_form.php     # API: Processa l'invio del modulo di contatto (presunto, da contact.php JS)
├── register.php                 # Logica: Registrazione nuovo utente (incluso da createaccount.php)
├── servizi.php                  # Pagina: Visualizzazione lista servizi
├── utils/                       # Utility e funzioni helper
│   ├── db_connect.php           # Script per connessione DB (principalmente re-include config)
│   └── functions.php            # Funzioni di utilità generali (formattazione date, email, etc.)
└── README.md                    # Questo file
```

## Aree di Possibile Miglioramento
*   **Sicurezza:**
    *   Implementare token CSRF per tutti i form che modificano stato.
    *   Assicurare l'uso consistente di prepared statements per tutte le query al database.
    *   Controlli di autorizzazione più granulari se necessario.
*   **Frontend:**
    *   Estrarre header/footer comuni in partial per ridurre duplicazione.
    *   Considerare un micro-framework JS o una libreria per la gestione dello stato se l'interattività del pannello admin dovesse crescere molto.
    *   Migliorare la gestione degli errori e del feedback utente nelle chiamate `fetch`.
*   **Backend:**
    *   Considerare un autoloader (es. Composer) per una migliore organizzazione del codice.
    *   Strutturare il codice in classi/oggetti per una maggiore manutenibilità (approccio OOP).
    *   Implementare un sistema di routing più robusto invece di file PHP per ogni URL.
*   **Setup Iniziale Admin:** Prevedere un modo più semplice per creare il primo utente admin.
*   **Test:** Aggiungere test unitari e di integrazione.

---

Questo README fornisce una panoramica del progetto CN Auto. Per dettagli specifici, fare riferimento al codice sorgente.
```
