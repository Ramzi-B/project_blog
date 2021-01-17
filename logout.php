<?php

/**
 * Includes files
 *******************************************************************************/

include_once 'inc/utils.php';

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

startSession();

unset($_SESSION['auth']);

$_SESSION['flashbox']['success'] = 'Vous êtes déconnecté!';

header('Location: /login.php');
exit();
