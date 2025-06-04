<?php
// SMTP Configuration for Forpsi
return [
    'smtp' => [
        'host' => 'smtp.forpsi.com',
        'port' => 587, // TLS port (recommended by Forpsi)
        'encryption' => 'tls', // or 'ssl' for port 465
        'username' => 'noreply@zskamenicka.cz', // Your full email address
        'password' => '', // You need to set your email password here
        'from_email' => 'noreply@zskamenicka.cz',
        'from_name' => 'Schránka Důvěry - ZŠ Kamenická',
    ],
    'admin' => [
        'email' => 'kry.tuma@gmail.com', // Change this to your admin email
        'name' => 'Administrátor'
    ],
    'site' => [
        'name' => 'Schránka Důvěry - ZŠ Kamenická',
        'url' => 'https://duvera.zskamenicka.cz' // Update with your actual domain
    ]
]; 
