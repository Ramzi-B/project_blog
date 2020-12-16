<?php

/**
 * Includes files
 *******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 *******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    http_response_code(301);
    header('Location: /');
    exit();
}

/**
 * Delete the post and all its related comments
 *******************************************************************************/

$sql = 'DELETE posts, comments FROM posts
    JOIN comments ON posts.id = comments.post_id WHERE posts.id = :id';

// $sql = 'DELETE FROM posts WHERE id = :id';
$statement = getDatabase()->prepare($sql);
// dd($_GET);
// dd($statement);
// die;
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$statement->closeCursor();

$_SESSION['flashbox']['success'] = "L'article a bien été supprimé!";

header('Location: /dashboard.php');
exit();
