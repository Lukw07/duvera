<?php
// SMTP Configuration for Forpsi
return [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587, // TLS port (recommended by Forpsi)
        'encryption' => 'tls', // or 'ssl' for port 465
        'username' => 'kry.pepa@gmail.com', // Your full email address
        'password' => 'uysm ogqr keke pecw', // You need to set your email password here
        'from_email' => 'kry.pepa@gmail.com',
        'from_name' => 'Schránka Důvěry - ZŠ Kamenická',
    ],
    'admin' => [
        'email' => 'kry.pepa@gmail.com', // Change this to your admin email
        'name' => 'Administrátor'
    ],
    'site' => [
        'name' => 'Schránka Důvěry - ZŠ Kamenická',
        'url' => 'https://duvera.zskamenicka.cz' // Update with your actual domain
    ]
]; 
