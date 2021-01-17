<?php

/**
 * Includes files
 *******************************************************************************/

include_once 'inc/utils.php';

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

startSession();

/**
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 ******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    http_response_code(301);
    redirect('/');
}

unset($_SESSION['auth']);

$_SESSION['flashbox']['success'] = 'Vous êtes déconnecté!';

redirect('login.php');
