<?php

/**
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

startSession();

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
 * Get the post to edit
 ******************************************************************************/
$sql = 'SELECT posts.id, posts.title, posts.content, posts.author_id,
    posts.category_id, posts.created, posts.updated, posts.published
    FROM posts
    WHERE posts.id = :id
';
$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Check for empty fields
 ******************************************************************************/

if (!empty($_POST)) {
    // $sql = 'UPDATE posts SET title = ?, content = ?, modified_at = NOW() WHERE id = ?';
    $sql = 'UPDATE posts SET title = :title, content = :content, updated = NOW() WHERE id = :id';
    $statement = getDatabase()->prepare($sql);
    $statement->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
    $statement->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
    $statement->bindParam(':id', $_POST['postid'], PDO::PARAM_INT);
    // $statement->execute([$_POST['title'], $_POST['content'], $_POST['postid']]);
    $statement->execute();
    $statement->closeCursor();
    $_SESSION['flashbox']['success'] = "L'article a bien été modifié!";

    header('Location: /dashboard.php');
    exit();
}

// debug($_SESSION);
// dd($_GET);

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
        <title>Modifier l'article</title>
        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/normalize.css">
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

        <main class="container">

            <?php if (isset($_SESSION['flashbox'])): ?>
                <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
                    <section class="flashbox flashbox-<?= $type; ?>">
                        <span class="close"></span>
        				<p><?= $message; ?></p>
        			</section>
        		<?php endforeach ?>
        		<?php unset($_SESSION['flashbox']); ?>
        	<?php endif ?>

            <h1>Modifier l'article</h1>

            <form action="" method="POST">

                <input type="hidden" name="postid" value="<?= intval($post->id) ?>">

                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="<?= validate($post->title) ?>">

                <label for="content">Article</label>
                <textarea id="content" name="content" rows="15"><?= validate($post->content) ?></textarea>

                <input type="submit" value="Mettre à jour">
                <!-- <button type="submit" class="btn">Mettre à jour</button> -->
                <a class="btn" href="/dashboard.php">Annuler</a>

            </form>

        </main>

        <footer>
            <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
        </footer>

        <script src="js/main.js"></script>
    </body>
</html>
