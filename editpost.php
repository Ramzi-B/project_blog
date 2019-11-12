<?php

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

if (!isset($_SESSION['auth'])) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    http_response_code(301);
    header('Location: /');
    exit();
}

/**
 * Includes files :
 * _ Database connection
 * _ Utils
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Get the post to edit
 ******************************************************************************/
$sql = 'SELECT * FROM posts WHERE posts.id = :id';
$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Check
 ******************************************************************************/

if (!empty($_POST)) {
    // $sql = 'UPDATE posts SET title = ?, content = ?, modified_at = NOW() WHERE id = ?';
    $sql = 'UPDATE posts SET title = :title, content = :content, modified_at = NOW() WHERE id = :id';
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
// debug($_GET);

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
        <title>Modifier l'article</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>

        <header>
            <section class="container">
                <div class="header-top">
                    <a href="/">Mon blog</a>
                    <nav>
                        <a href="dashboard.php">Dashboard</a>
                    </nav>
                </div>
            </section>
        </header>

        <main class="container">

            <?php if (isset($_SESSION['flashbox'])): ?>
                <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
        			<section class="flashbox flashbox-<?= $type; ?>">
        				<p><?= $message; ?></p>
        			</section>
        		<?php endforeach ?>
        		<?php unset($_SESSION['flashbox']); ?>
        	<?php endif ?>

            <h1>Modifier l'article</h1>

            <form action="" method="post">

                <input type="hidden" name="postid" value="<?= intval($post->id) ?>">

                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post->title) ?>">

                <label for="content">Article</label>
                <textarea id="content" name="content" rows="15"><?= htmlspecialchars($post->content) ?></textarea>

                <input type="submit" value="Mettre à jour">
                <a class="btn" href="/dashboard.php">Annuler</a>

            </form>

        </main>

        <footer>
            <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
        </footer>

        <script src="js/main.js"></script>
    </body>
</html>
