<?php

/**
 * Connect to the database
 ******************************************************************************/

function getDatabase() {
    $pdo = new PDO('mysql:host=localhost;dbname=project_blog;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}


// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=project_blog;charset=utf8', 'root', 'rboxer');
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (\PDOException $e){
//     die('Error: '.$e->getMessage());
//     // die(json_encode(['outcome' => false, 'message' => 'Unable to connect']));
// }

// $pdo = new PDO('mysql:host=localhost;dbname=project_blog;charset=utf8', 'root', 'rboxer');
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $pdo->exec('SET NAMES "utf8"');