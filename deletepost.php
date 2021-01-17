<?php

/**
 * Includes files
 *******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

startSession();

/**
 * Check if the admin user is logged in
 * if he's not redirected to the index page
 *******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    http_response_code(301);
    redirect('/');
}

/**
 * Delete the post and all its related comments
 *******************************************************************************/

$sql = 'DELETE posts FROM posts WHERE posts.id = :id';

$statement = getDatabase()->prepare($sql);
// dd($_GET);
// dd($statement);
// die;
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$statement->closeCursor();

$_SESSION['flashbox']['success'] = "L'article a bien été supprimé!";

redirect('dashboard.php');
