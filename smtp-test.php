<?php
// SMTP Test Script
// This file should be deleted after testing

require_once __DIR__ . '/vendor/autoload.php';

// Load configuration
$config = require_once __DIR__ . '/config.php';

use App\EmailService;

echo "<h1>SMTP Configuration Test</h1>";
echo "<pre>";

// Display configuration (without password)
echo "SMTP Configuration:\n";
echo "Host: " . $config['smtp']['host'] . "\n";
echo "Port: " . $config['smtp']['port'] . "\n";
echo "Encryption: " . $config['smtp']['encryption'] . "\n";
echo "Username: " . $config['smtp']['username'] . "\n";
echo "Password: " . (empty($config['smtp']['password']) ? "NOT SET" : "SET") . "\n";
echo "\n";

if (empty($config['smtp']['password'])) {
    echo "❌ ERROR: SMTP password is not set in config.php\n";
    echo "Please edit config.php and add your email password.\n";
    exit;
}

try {
    echo "Initializing EmailService...\n";
    $emailService = new EmailService($config);
    echo "✅ EmailService initialized successfully\n\n";
    
    echo "Testing SMTP connection...\n";
    if ($emailService->testConnection()) {
        echo "✅ SMTP connection test successful\n\n";
        
        echo "Sending test email...\n";
        $testResult = $emailService->sendMessage(
            "Test User",
            "test@example.com",
            "This is a test message from the SMTP configuration test script."
        );
        
        if ($testResult) {
            echo "✅ Test email sent successfully!\n";
            echo "Check your admin email: " . $config['admin']['email'] . "\n";
        } else {
            echo "❌ Failed to send test email\n";
        }
    } else {
        echo "❌ SMTP connection test failed\n";
        echo "Please check your SMTP credentials and Forpsi server settings.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";

echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>If the test was successful, your SMTP is configured correctly</li>";
echo "<li>Update the admin email in config.php to your actual email address</li>";
echo "<li>Set a strong password for the noreply@zskamenicka.cz email account</li>";
echo "<li>Delete this test file (smtp-test.php) for security</li>";
echo "<li>Test the main application form</li>";
echo "</ol>";

echo "<p><strong>Security Note:</strong> Delete this file after testing!</p>";
?> 