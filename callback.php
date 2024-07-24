<?php
include 'config.php';

// Verifica che il parametro "code" sia presente
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Scambia il codice per un token di accesso
    $tokenURL = 'https://discord.com/api/oauth2/token';
    $data = [
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => REDIRECT_URI
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];
    $context  = stream_context_create($options);
    $response = file_get_contents($tokenURL, false, $context);
    $token = json_decode($response, true)['access_token'];

    // Usa il token di accesso per ottenere informazioni sull'utente
    $userURL = 'https://discord.com/api/users/@me';
    $options = [
        'http' => [
            'header' => 'Authorization: Bearer ' . $token
        ]
    ];
    $context  = stream_context_create($options);
    $user = json_decode(file_get_contents($userURL, false, $context), true);

    // Qui puoi gestire l'utente (es. salvarlo nel database, avviare una sessione, ecc.)
    session_start();
    $_SESSION['user'] = $user;
    echo "Login effettuato con successo, benvenuto " . htmlspecialchars($user['username']);
    // Puoi reindirizzare l'utente ad una pagina protetta
    // header('Location: protected.php');
} else {
    echo 'Errore durante l\'autenticazione.';
}
?>