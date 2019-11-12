<?php

/**
 * Check if a session is already started if it is not started
 *******************************************************************************/

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
 *******************************************************************************/

include 'inc/utils.php';
include 'inc/DatabaseConnection.php';

// debug($_SESSION);

/**
 * Get all authors
 *******************************************************************************/

$sql = 'SELECT id, authorName FROM authors';
$statement = getDatabase()->query($sql);
$authors = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Get all categories
 *******************************************************************************/
$sql = 'SELECT id, categoryName FROM categories';
$statement = getDatabase()->query($sql);
$categories = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Check
 *******************************************************************************/

if (!empty($_POST)) {

    if (empty($_POST['title']) || empty($_POST['content'])) {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs *";
    }

    if (!empty($_POST['title']) && !empty($_POST['content'])) {
        // $sql = 'INSERT INTO posts(title, content, author_id, category_id, created_at) VALUES (?, ?, ?, ?, NOW())';
        $sql = 'INSERT INTO posts(title, content, author_id, category_id, created_at) VALUES (:title, :content, :author, :category, NOW())';
        $statement = getDatabase()->prepare($sql);
        // $statement->execute([ucfirst($_POST['title']), $_POST['content'], $_POST['author'], $_POST['category']]);
        $statement->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
        $statement->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
        $statement->bindParam(':author', $_POST['author'], PDO::PARAM_INT);
        $statement->bindParam(':category', $_POST['category'], PDO::PARAM_INT);
        $statement->execute();
        // debug($pdo->lastInsertId());
        // die();
        $statement->closeCursor();

        $_SESSION['flashbox']['success'] = "L'article a bien été ajouté!";

        header('Location: /dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
    <title>Ajouter un nouvel articlecode</title>
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

        <h1>Ajouter un article <em id="help-form-text"></em></h1>

        <?php if (isset($_SESSION['flashbox'])): ?>
            <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
    			<section class="flashbox flashbox-<?= $type; ?>">
    				<p><?= $message; ?></p>
    			</section>
    		<?php endforeach ?>
    		<?php unset($_SESSION['flashbox']); ?>
    	<?php endif ?>

        <!-- <p id="help-form-text"></p> -->

        <form action="" method="post">

            <label for="title">Titre</label>
            <input type="text" id="title" name="title" placeholder="Votre titre *" data-help="Votre titre">

            <label for="content">Article</label>
            <textarea name="content" id="content" cols="30" rows="10" placeholder="Votre article *" data-help="Votre article"></textarea>

            <label for="author">Auteur</label>
            <select name="author" id="author" data-help="L'auteur">
                <?php foreach ($authors as $author): ?>
                    <option value="<?= intval($author->id) ?>">
                        <?= htmlspecialchars($author->authorName) ?>
                    </option>
                <?php endforeach ?>
            </select>

            <label for="category">Catégorie</label>
            <select name="category" id="category" data-help="La catégorie">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= intval($category->id) ?>" data-help="Auteur">
                        <?= htmlspecialchars($category->categoryName) ?>
                    </option>
                <?php endforeach ?>
            </select>

            <input type="submit" value="Enregistrer">
            <a class="btn" href="dashboard.php">Annuler</a>

        </form>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
