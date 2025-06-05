<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $config;
    private $mail;

    public function __construct($config)
    {
        $this->config = $config;
        $this->initializeMailer();
    }

    private function initializeMailer()
    {
        $this->mail = new PHPMailer(true);

        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = $this->config['smtp']['host'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->config['smtp']['username'];
            $this->mail->Password = $this->config['smtp']['password'];
            $this->mail->SMTPSecure = $this->config['smtp']['encryption'];
            $this->mail->Port = $this->config['smtp']['port'];
            
            // Character encoding
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';
            
            // Default sender
            $this->mail->setFrom(
                $this->config['smtp']['from_email'], 
                $this->config['smtp']['from_name']
            );
            
            // Enable verbose debug output (disable in production)
            // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            
        } catch (Exception $e) {
            error_log("EmailService initialization failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function sendMessage($name, $email, $message)
    {
        try {
            // Reset recipients for each email
            $this->mail->clearAddresses();
            $this->mail->clearReplyTos();
            
            // Recipients
            $this->mail->addAddress(
                $this->config['admin']['email'], 
                $this->config['admin']['name']
            );
            
            // Reply-To (if email provided and valid)
            if ($email !== 'Neudáno' && $email !== 'Neudáno (neplatný formát)' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->mail->addReplyTo($email, $name);
            }
            
            // Content
            $this->mail->isHTML(true); // HTML email for better formatting
            $this->mail->Subject = "Nová zpráva ze " . $this->config['site']['name'];
            
            // HTML email template
            $htmlContent = $this->generateHtmlTemplate($name, $email, $message);
            $this->mail->Body = $htmlContent;
            
            // Plain text alternative
            $plainContent = $this->generatePlainTemplate($name, $email, $message);
            $this->mail->AltBody = $plainContent;
            
            // Send email
            $result = $this->mail->send();
            
            if ($result) {
                error_log("Email sent successfully to: " . $this->config['admin']['email']);
                return true;
            } else {
                error_log("Email sending failed: " . $this->mail->ErrorInfo);
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Email sending exception: " . $e->getMessage());
            return false;
        }
    }
    
    private function generateHtmlTemplate($name, $email, $message)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Nová zpráva</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f5f5f5;
                }
                .email-container {
                    background-color: white;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    overflow: hidden;
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 300;
                }
                .content {
                    padding: 30px;
                }
                .info-row {
                    display: flex;
                    margin-bottom: 15px;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 10px;
                }
                .info-label {
                    font-weight: bold;
                    color: #555;
                    width: 80px;
                    flex-shrink: 0;
                }
                .info-value {
                    color: #333;
                    flex: 1;
                }
                .message-section {
                    margin-top: 30px;
                }
                .message-label {
                    font-weight: bold;
                    color: #555;
                    margin-bottom: 10px;
                    display: block;
                }
                .message-content {
                    background-color: #f8f9fa;
                    border-left: 4px solid #667eea;
                    padding: 20px;
                    border-radius: 4px;
                    white-space: pre-wrap;
                    font-family: inherit;
                }
                .footer {
                    background-color: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    color: #666;
                    font-size: 14px;
                }
                .footer a {
                    color: #667eea;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>📬 Nová zpráva</h1>
                    <p>ze Schránky Důvěry</p>
                </div>
                
                <div class="content">
                    <div class="info-row">
                        <span class="info-label">Jméno:</span>
                        <span class="info-value">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Čas:</span>
                        <span class="info-value">' . date('d.m.Y H:i:s') . '</span>
                    </div>
                    
                    <div class="message-section">
                        <span class="message-label">Zpráva:</span>
                        <div class="message-content">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>
                    </div>
                </div>
                
                <div class="footer">
                    <p>Tato zpráva byla odeslána z: <a href="' . htmlspecialchars($this->config['site']['url'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($this->config['site']['name'], ENT_QUOTES, 'UTF-8') . '</a></p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function generatePlainTemplate($name, $email, $message)
    {
        $emailContent = "Nová zpráva byla odeslána prostřednictvím Schránky Důvěry\n\n";
        $emailContent .= "Jméno: " . $name . "\n";
        $emailContent .= "Email: " . $email . "\n";
        $emailContent .= "Čas: " . date('d.m.Y H:i:s') . "\n\n";
        $emailContent .= "Zpráva:\n" . str_repeat('-', 50) . "\n";
        $emailContent .= $message . "\n";
        $emailContent .= str_repeat('-', 50) . "\n\n";
        $emailContent .= "Tato zpráva byla odeslána z: " . $this->config['site']['url'] . "\n";
        
        return $emailContent;
    }
    
    public function testConnection()
    {
        try {
            $this->mail->smtpConnect();
            $this->mail->smtpClose();
            return true;
        } catch (Exception $e) {
            error_log("SMTP connection test failed: " . $e->getMessage());
            return false;
        }
    }
}
