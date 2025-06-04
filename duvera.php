<!DOCTYPE html>
<?php
session_start();

// Konfigurace
$admin_email = "kry.tuma@gmail.com"; // Zde zadejte váš email
$site_name = "Schránka Důvěry";
$max_message_length = 2000;
$min_message_length = 10;

// Konfigurace SMTP
ini_set("SMTP", "smtp.forpsi.com");
ini_set("smtp_port", "587");
ini_set("sendmail_from", "noreply@zskamenicka.cz");

// Generování CSRF tokenu
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Zpracování formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kontrola CSRF tokenu
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Neplatný požadavek. Prosím zkuste to znovu.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Sanitizace a validace vstupů
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?: 'Anonymní';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?: 'Neudáno';
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    
    // Validace zprávy
    if (empty($message)) {
        $_SESSION['error'] = "Zpráva nemůže být prázdná.";
    } elseif (strlen($message) < $min_message_length) {
        $_SESSION['error'] = "Zpráva musí mít alespoň {$min_message_length} znaků.";
    } elseif (strlen($message) > $max_message_length) {
        $_SESSION['error'] = "Zpráva nesmí být delší než {$max_message_length} znaků.";
    } else {
        // Příprava emailu
        $subject = "Nová zpráva ze {$site_name}";
        $headers = "From: {$site_name} <noreply@zskamenicka.cz>\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        $email_content = "Jméno: {$name}\n";
        $email_content .= "Email: {$email}\n";
        $email_content .= "Čas: " . date('d.m.Y H:i:s') . "\n\n";
        $email_content .= "Zpráva:\n{$message}";
        
        // Odeslání emailu
        $mail_sent = @mail($admin_email, $subject, $email_content, $headers);
        if ($mail_sent) {
            $_SESSION['success'] = true;
            // Vygenerování nového CSRF tokenu po úspěšném odeslání
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } else {
            $error = error_get_last();
            $error_message = "Nepodařilo se odeslat zprávu. ";
            
            if ($error !== null) {
                switch ($error['type']) {
                    case E_WARNING:
                        $error_message .= "Chyba SMTP serveru: " . $error['message'];
                        break;
                    case E_ERROR:
                        $error_message .= "Kritická chyba: " . $error['message'];
                        break;
                    default:
                        $error_message .= "Technická chyba: " . $error['message'];
                }
            } else {
                $error_message .= "Zkontrolujte prosím nastavení SMTP serveru a zkuste to znovu.";
            }
            
            $_SESSION['error'] = $error_message;
        }
    }
    
    // Přesměrování pro prevenci opětovného odeslání
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="description" content="Soukromá schránka důvěry">
    <meta name="author" content="Administrátor">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="referrer" content="no-referrer">
    <title><?php echo htmlspecialchars($site_name); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header p {
            color: #666;
            font-size: 1.1em;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        
        .optional {
            color: #888;
            font-weight: normal;
            font-size: 0.9em;
        }
        
        input, textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        textarea {
            resize: vertical;
            min-height: 150px;
            line-height: 1.5;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .privacy-note {
            background: rgba(102, 126, 234, 0.1);
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
        }
        
        .privacy-note h3 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .privacy-note h3::before {
            content: "🔒";
            margin-right: 10px;
            font-size: 1.2em;
        }
        
        .privacy-note p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        
        .success-message {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
            display: none;
            animation: slideIn 0.5s ease-out;
        }
        
        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ff4757);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .character-count {
            text-align: right;
            color: #888;
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            input, textarea {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 <?php echo htmlspecialchars($site_name); ?></h1>
            <p>Bezpečné místo pro sdílení vašich myšlenek, obav nebo zpovědi. Vaše zpráva bude zaslána anonymně.</p>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div id="successMessage" class="success-message" style="display: block;">
                <h3>✅ Zpráva byla úspěšně odeslána!</h3>
                <p>Děkujeme za důvěru. Vaše zpráva byla předána a bude zpracována s respektem a diskrétností.</p>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <h3>❌ Chyba při odesílání</h3>
                <p><?php echo htmlspecialchars($_SESSION['error']); ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form id="trustForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="name">Jméno <span class="optional">(volitelné)</span></label>
                <input type="text" id="name" name="name" placeholder="Můžete zůstat anonymní" maxlength="100">
            </div>
            
            <div class="form-group">
                <label for="email">Email <span class="optional">(volitelné - pro odpověď)</span></label>
                <input type="email" id="email" name="email" placeholder="váš@email.cz" maxlength="100">
            </div>
            
            <div class="form-group">
                <label for="message">Vaše zpráva *</label>
                <textarea id="message" name="message" placeholder="Napište zde svou zprávu, myšlenku, obavu nebo cokoliv, co potřebujete sdílet..." required maxlength="<?php echo $max_message_length; ?>" minlength="<?php echo $min_message_length; ?>"></textarea>
                <div class="character-count">
                    <span id="charCount">0</span>/<?php echo $max_message_length; ?> znaků
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Odeslat zprávu</button>
        </form>
        
        <div class="privacy-note">
            <h3>Ochrana soukromí</h3>
            <p>• Vaše zpráva je odesílána bezpečně a anonymně</p>
            <p>• Nevyžadujeme žádné povinné osobní údaje</p>
            <p>• IP adresy ani další technické informace neukládáme</p>
            <p>• Všechny zprávy jsou zpracovávány s maximální diskrétností</p>
        </div>
    </div>

    <script>
        // Počítadlo znaků
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        const maxLength = <?php echo $max_message_length; ?>;
        
        messageTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > maxLength * 0.9) {
                charCount.style.color = '#ff6b6b';
            } else if (count > maxLength * 0.75) {
                charCount.style.color = '#ffa726';
            } else {
                charCount.style.color = '#888';
            }
        });
        
        // Animace při načítání stránky
        window.addEventListener('load', function() {
            document.querySelector('.container').style.animation = 'fadeIn 0.8s ease-out';
        });
        
        // Validace formuláře
        document.getElementById('trustForm').addEventListener('submit', function(e) {
            const message = messageTextarea.value.trim();
            if (message.length < <?php echo $min_message_length; ?>) {
                e.preventDefault();
                alert('Zpráva musí mít alespoň <?php echo $min_message_length; ?> znaků.');
            }
        });
    </script>
</body>
</html>