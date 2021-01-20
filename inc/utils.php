<?php

function dd($var)
{
    echo '<pre class="debug">' . print_r($var, true) . '</pre>';
}

function isAuthenticated()
{
    startSession();
    return isset($_SESSION['auth']) && $_SESSION['auth'] == true;
}

function startSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        // When you clear the cache (in development mode) the redirection
        // doesn't work anymore because the session takes the two parameters 
        // "cookie_httponly, cookie_secure".
        session_start();
        // session_start([ 
        //     'cookie_httponly' => true, 
        //     'cookie_secure' => true 
        // ]);
    }
}

function validate(string $args)
{
    return htmlspecialchars($args, ENT_QUOTES, 'UTF-8');
}

function redirect($uri, $code = null)
{
    if (substr($uri, 0, 1) !== '/') {
        $uri = "/{$uri}";
    }

    if ($code == true) {
        http_response_code($code);
    }

    header("Location: {$uri}");
    exit();
}
