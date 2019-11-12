<?php

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['auth']);

$_SESSION['flashbox']['success'] = 'Vous êtes déconnecté!';

header('Location: /login.php');
exit();
