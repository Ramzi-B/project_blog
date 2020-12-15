<?php

function dd($var)
{
    echo '<pre class="debug">' . print_r($var, true) . '</pre>';
}

function isAuthenticated()
{
    return isset($_SESSION['auth']) && $_SESSION['auth'] == true;
}

function checkIntegerRange($int, $min, $max)
{
    if (is_string($int) && !ctype_digit($int)) {
        // contains non digit characters
        return false;
    }

    if (!is_int((int) $int)) {
        // other non integer value or exceeds PHP_MAX_INT
        return false;
    }

    return ($int >= $min && $int <= $max);
}


function getConfig($fileName)
{
    return include ROOT_PATH . "/../config/{$fileName}.php";
}
