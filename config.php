<?php
// SMTP Configuration for Forpsi
return [
    'smtp' => [
        'host' => 'smtp.seznam.cz',
        'port' => 465, // TLS port (recommended by Forpsi)
        'encryption' => 'ssl', // or 'ssl' for port 465
        'username' => 'test@webforte.cz', // Your full email address
        'password' => 'nL6!MxpU4AOpRZ', // You need to set your email password here
        'from_email' => 'test@webforte.cz',
        'from_name' => 'Schránka Důvěry - ZŠ Kamenická',
    ],
    'admin' => [
        'email' => 'test@webforte.cz', // Change this to your admin email
        'name' => 'Administrátor'
    ],
    'site' => [
        'name' => 'Schránka Důvěry - ZŠ Kamenická',
        'url' => 'https://duvera.zskamenicka.cz' // Update with your actual domain
    ]
]; 