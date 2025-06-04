<?php
// SMTP Configuration for Forpsi
return [
    'smtp' => [
        'host' => 'smtp.forpsi.com',
        'port' => 587, // TLS port (recommended by Forpsi)
        'encryption' => 'tls', // or 'ssl' for port 465
        'username' => 'kry.pepa@gmailcom', // Your full email address
        'password' => '123456789k.K', // You need to set your email password here
        'from_email' => 'kry.pepa@gmailcom',
        'from_name' => 'Schránka Důvěry - ZŠ Kamenická',
    ],
    'admin' => [
        'email' => 'kry.pepa@gmailcom', // Change this to your admin email
        'name' => 'Administrátor'
    ],
    'site' => [
        'name' => 'Schránka Důvěry - ZŠ Kamenická',
        'url' => 'https://duvera.zskamenicka.cz' // Update with your actual domain
    ]
]; 
