<?php

/**
 * Includes files
 ******************************************************************************/

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
 * Get the comment to edit
 ******************************************************************************/

$sql = 'SELECT comments.id, comments.name, comments.content, comments.created,
        comments.updated, comments.post_id, comments.email
    FROM comments WHERE comments.id = :id
';

$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$comment = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

// dd($_POST);
// dd($_GET);
// dd($comment);

/**
 * Check
 ******************************************************************************/

if (isset($_POST) && !empty($_POST)) {
    // dd($_POST);
    // dd($_GET);
    // dd($_SESSION);
    // die();
    $sql = 'UPDATE comments SET comments.content = :content, comments.updated = NOW() 
        WHERE comments.id = :id
    ';

    $statement = getDatabase()->prepare($sql);
    $statement->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    $statement->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION['flashbox']['success'] = "Le commmentaire a bien été modifié!";

    redirect('showPost.php?id=' . intval($_POST['postid']));
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- https://favicon.io/favicon-generator -->
        <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
        <!-- Title -->
        <title>Modifier le commentaire</title>
        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
        <!-- Normalize -->
        <link rel="stylesheet" href="/css/normalize.css">
        <!-- CSS -->
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>

        <header>
            <section class="container">
                <div class="header-top">
                    <a href="/">Mon blog</a>
                    <nav>
                        <a href="/"><i class="fas fa-home"></i>&nbsp;Home</a>
                        <a href="/contact.php"><i class="fas fa-envelope"></i>&nbsp;Contact</a>
                        <?php if (isAuthenticated()): ?>
                            <a href="/dashboard.php"><i class="fas fa-tachometer-alt"></i>&nbsp;Dashboard</a>
                            <a href="/logout.php"><i class="fas fa-user"></i>&nbsp;Logout</a>
                        <?php else: ?>
                            <a href="/login.php"><i class="fas fa-user"></i>&nbsp;Login</a>
                        <?php endif ?>
                    </nav>
                </div>
            </section>
        </header>

        <!-- Main -->
        <main class="container">
        
            <h1>Modifier le commentaire</h1>

            <!-- Session flash messages -->
            <?php if (isset($_SESSION['flashbox'])): ?>
                <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
                    <section class="flashbox flashbox-<?= $type; ?>">
                        <span class="close"></span>
        				<p><?= $message; ?></p>
        			</section>
                <?php endforeach ?>                
                <?php unset($_SESSION['flashbox']); ?>                
        	<?php endif ?>

            <form action="" method="post">

                <input type="hidden" name="postid" value="<?= intval($comment->post_id) ?>">
                <input type="hidden" name="id" value="<?= intval($comment->id) ?>">

                <label for="content">Commentaire</label>
                <textarea name="content" id="content" cols="30" rows="10"><?= validate($comment->content) ?></textarea>

                <input class="btn" type="submit" value="Mettre à jour">
                <a class="btn" href="/dashboard.php">Annuler</a>

            </form>

        </main>

        <footer>
            <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
        </footer>

        <!-- JS -->
        <script src="/js/main.js"></script>
    </body>
</html>
