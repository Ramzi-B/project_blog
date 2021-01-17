<?php

function dd($var)
{
    echo '<pre class="debug">' . print_r($var, true) . '</pre>';
}

function isAuthenticated()
{
    return isset($_SESSION['auth']) && $_SESSION['auth'] == true;
}

function startSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start([ 
            'cookie_httponly' => true, 
            'cookie_secure' => true 
        ]);
    }
}

function validate(string $args)
{
    return htmlspecialchars($args, ENT_QUOTES, 'UTF-8');
}

function redirect($uri)
{
    if (substr($uri, 0, 1) !== '/') {
        $uri = "/{$uri}";
    }

    header("Location: {$uri}");
    exit();
}
