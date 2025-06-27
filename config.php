<?php
// SMTP Configuration for Gmail
return [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587, // TLS port (recommended by Gmail)
        'encryption' => 'tls', // or 'ssl' for port 465
        'username' => 'webkamenicka@gmail.com', // Your full email address
        'password' => 'johk ifjj itgc sabu', // Your Gmail app password
        'from_email' => 'webkamenicka@gmail.com',
        'from_name' => 'Schránka Důvěry - ZŠ Kamenická',
    ],
    'admin' => [
        'email' => 'slaba@zskamenicka.cz', // ZMĚNĚNO - nyní zprávy půjdou na tento email
        'name' => 'Slaba - Administrátor'
    ],
    'site' => [
        'name' => 'Schránka Důvěry - ZŠ Kamenická',
        'url' => 'https://duvera.zskamenicka.cz' // Update with your actual domain
    ]
];
