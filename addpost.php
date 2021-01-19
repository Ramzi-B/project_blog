<?php

/**
 * Includes files
 *******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 *******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    http_response_code(301);
    redirect('/');
}

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
 * Check for empty fields
 *******************************************************************************/

if (!empty($_POST)) {

    if (empty($_POST['title']) || empty($_POST['content'])) {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs *";
    }

    if (!empty($_POST['title']) && !empty($_POST['content'])) {
        $sql = 'INSERT INTO posts(title, content, author_id, category_id, created) VALUES (:title, :content, :author, :category, NOW())';
        $statement = getDatabase()->prepare($sql);
        $statement->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
        $statement->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
        $statement->bindParam(':author', $_POST['author'], PDO::PARAM_INT);
        $statement->bindParam(':category', $_POST['category'], PDO::PARAM_INT);
        $statement->execute();
        $statement->closeCursor();

        $_SESSION['flashbox']['success'] = "L'article a bien été ajouté!";

        redirect('dashboard.php');
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
    <title>Ajouter un nouvel article</title>
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

        <h1>Ajouter un article</h1>

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

        <p id="help-form-text"></p>

        <form action="" method="POST">

            <label for="title">Titre</label>
            <input type="text" id="title" name="title" placeholder="Votre titre *" data-help="Votre titre">

            <label for="content">Article</label>
            <textarea name="content" id="content" cols="30" rows="10" placeholder="Votre article *" data-help="Votre article"></textarea>

            <label for="author">Auteur</label>
            <select name="author" id="author" data-help="L'auteur">
                <?php foreach ($authors as $author): ?>
                    <option value="<?= intval($author->id) ?>">
                        <?= validate(ucfirst($author->authorName)) ?>
                    </option>
                <?php endforeach ?>
            </select>

            <label for="category">Catégorie</label>
            <select name="category" id="category" data-help="La catégorie">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= intval($category->id) ?>" data-help="Auteur">
                        <?= validate(ucfirst($category->categoryName)) ?>
                    </option>
                <?php endforeach ?>
            </select>

            <input class="btn" type="submit" value="Enregistrer">
            <a class="btn" href="/dashboard.php">Annuler</a>

        </form>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
