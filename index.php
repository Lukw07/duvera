<?php
session_start();

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load configuration
$config = require_once __DIR__ . '/config.php';

// Import EmailService
use App\EmailService;

// Initialize EmailService
try {
    $emailService = new EmailService($config);
} catch (Exception $e) {
    error_log("Failed to initialize EmailService: " . $e->getMessage());
    $_SESSION['error'] = true;
}

// Zpracování formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize Name (optional)
    $name_raw = isset($_POST['name']) ? trim($_POST['name']) : '';
    if ($name_raw === '') {
        $name = 'Anonymní';
    } else {
        $name = htmlspecialchars($name_raw, ENT_QUOTES, 'UTF-8');
    }

    // Sanitize and Validate Email (optional)
    $email_input = isset($_POST['email']) ? trim($_POST['email']) : '';
    if ($email_input === '') {
        $email = 'Neudáno';
    } else {
        $sanitized_email = filter_var($email_input, FILTER_SANITIZE_EMAIL);
        if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
            $email = $sanitized_email;
        } else {
            $email = 'Neudáno (neplatný formát)';
        }
    }

    // Sanitize Message (required)
    $message_raw = isset($_POST['message']) ? trim($_POST['message']) : '';
    $message = htmlspecialchars($message_raw, ENT_QUOTES, 'UTF-8');
    
    // Check if message is not empty after sanitization
    if (!empty($message_raw)) {
        // Send email using EmailService
        if (isset($emailService)) {
            try {
                if ($emailService->sendMessage($name, $email, $message)) {
                    $_SESSION['success'] = true;
                } else {
                    $_SESSION['error'] = true;
                }
            } catch (Exception $e) {
                error_log("Email sending failed: " . $e->getMessage());
                $_SESSION['error'] = true;
            }
        } else {
            $_SESSION['error'] = true;
        }
        
        // Přesměrování pro prevenci opětovného odeslání
        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
        exit();
    } else {
        $_SESSION['error_message_empty'] = "Zpráva nesmí být prázdná.";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($config['site']['name'], ENT_QUOTES, 'UTF-8'); ?></title>
  <style>
  /* ... (your CSS remains the same) ... */
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

  input,
  textarea {
    width: 100%;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    font-family: inherit;
  }

  input:focus,
  textarea:focus {
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
    background: linear-gradient(135deg, #f44336, #d32f2f);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    margin-top: 20px;
    animation: slideIn 0.5s ease-out;
  }

  .message-feedback {
    /* For general feedback like empty message */
    background-color: #fff3cd;
    color: #856404;
    padding: 10px;
    border: 1px solid #ffeeba;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
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

    input,
    textarea {
      padding: 12px;
    }
  }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>💬 <?php echo htmlspecialchars($config['site']['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
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
      <p>Omlouváme se, při odesílání zprávy došlo k chybě. Prosím zkuste to později nebo kontaktujte administrátora.</p>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message_empty'])): ?>
    <div class="message-feedback">
      <p><?php echo $_SESSION['error_message_empty']; ?></p>
    </div>
    <?php unset($_SESSION['error_message_empty']); ?>
    <?php endif; ?>

    <form id="trustForm" method="POST"
      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>">
      <div class="form-group">
        <label for="name">Jméno <span class="optional">(volitelné)</span></label>
        <input type="text" id="name" name="name" placeholder="Můžete zůstat anonymní">
      </div>

      <div class="form-group">
        <label for="email">Email <span class="optional">(volitelné - pro odpověď)</span></label>
        <input type="email" id="email" name="email" placeholder="váš@email.cz">
      </div>

      <div class="form-group">
        <label for="message">Vaše zpráva *</label>
        <textarea id="message" name="message"
          placeholder="Napište zde svou zprávu, myšlenku, obavu nebo cokoliv, co potřebujete sdílet..." required
          maxlength="2000"></textarea>
        <div class="character-count">
          <span id="charCount">0</span>/2000 znaků
        </div>
      </div>

      <button type="submit" class="submit-btn">Odeslat zprávu</button>
    </form>

    <div class="privacy-note">
      <h3>Ochrana soukromí</h3>
      <p>• Vaše zpráva je odesílána bezpečně a anonymně</p>
      <p>• Nevyžadujeme žádné povinné osobní údaje</p>
      <p>• IP adresy jsou zaznamenávány pouze pro bezpečnostní účely</p>
      <p>• Všechny zprávy jsou zpracovávány s maximální diskrétností</p>
      <p>• Komunikace probíhá přes šifrované SMTP spojení</p>
    </div>
  </div>

  <script>
  // Počítadlo znaků
  const messageTextarea = document.getElementById('message');
  const charCount = document.getElementById('charCount');

  if (messageTextarea) {
    messageTextarea.addEventListener('input', function() {
      const count = this.value.length;
      charCount.textContent = count;

      if (count > 1800) {
        charCount.style.color = '#ff6b6b';
      } else if (count > 1500) {
        charCount.style.color = '#ffa726';
      } else {
        charCount.style.color = '#888';
      }
    });
  }

  // Auto-hide success/error messages after 5 seconds
  const successMessage = document.getElementById('successMessage');
  if (successMessage && successMessage.style.display === 'block') {
    setTimeout(() => {
      successMessage.style.opacity = '0';
      setTimeout(() => successMessage.style.display = 'none', 500);
    }, 5000);
  }

  const errorMessage = document.querySelector('.error-message');
  if (errorMessage && getComputedStyle(errorMessage).display !== 'none') {
    setTimeout(() => {
      errorMessage.style.opacity = '0';
      setTimeout(() => errorMessage.style.display = 'none', 500);
    }, 5000);
  }
  </script>
</body>

</html>