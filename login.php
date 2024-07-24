<?php
    include 'config.php';

    // URL per l'autenticazione di Discord
    $authorizeURL = 'https://discord.com/api/oauth2/authorize';
    $params = [
        'client_id' => OAUTH2_CLIENT_ID,
        'redirect_uri' => REDIRECT_URI,
        'response_type' => 'code',
        'scope' => 'identify email'
    ];

    header('Location: ' . $authorizeURL . '?' . http_build_query($params));
    exit();
?>