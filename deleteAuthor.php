<?php

/**
 * Includes files :
 *******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 *******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    redirect('/', 301);
}

/**
 * Delete the post and all its related comments
 *******************************************************************************/

// dd($_POST);
// dd($_GET);
// die();

$sql = 'DELETE FROM authors WHERE id = :id';

$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$statement->closeCursor();

$_SESSION['flashbox']['success'] = "L'auteur a bien été supprimé!";

redirect('showAuthors.php');