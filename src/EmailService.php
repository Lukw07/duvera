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
            $this->mail->isHTML(false); // Plain text email
            $this->mail->Subject = "Nová zpráva ze " . $this->config['site']['name'];
            
            $emailContent = "Nová zpráva byla odeslána prostřednictvím Schránky Důvěry\n\n";
            $emailContent .= "Jméno: " . $name . "\n";
            $emailContent .= "Email: " . $email . "\n";
            $emailContent .= "Čas: " . date('d.m.Y H:i:s') . "\n";
            $emailContent .= "IP adresa: " . $this->getClientIP() . "\n\n";
            $emailContent .= "Zpráva:\n" . str_repeat('-', 50) . "\n";
            $emailContent .= $message . "\n";
            $emailContent .= str_repeat('-', 50) . "\n\n";
            $emailContent .= "Tato zpráva byla odeslána z: " . $this->config['site']['url'] . "\n";
            
            $this->mail->Body = $emailContent;
            
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
    
    private function getClientIP()
    {
        // Get client IP address (considering proxies)
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
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