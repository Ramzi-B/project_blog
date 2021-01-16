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
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Get all categories
 ******************************************************************************/

$sql = 'SELECT id, categoryName FROM categories';
$statement = getDatabase()->query($sql);
$categories = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();


/**
 * Get all posts by category
 ******************************************************************************/

$sql = 'SELECT
            posts.id, title, content, author_id, category_id, created,
            authors.authorName,
            categories.categoryName
    FROM posts
    INNER JOIN authors ON posts.author_id = authors.id
    INNER JOIN categories ON posts.category_id = categories.id
    WHERE posts.category_id = :id ORDER BY created
';
$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

// dd($posts);
// dd($categories);
// dd($totalCategories);
// dd($postPerPage);
// dd($totalPages);
// dd($_GET);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
    <title>Categorie <?= htmlspecialchars($posts[0]->categoryName) ?></title>
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
                        <a href="/dashboard.php"><i class="fas fa-toolbox"></i>&nbsp;Dashboard</a>
                        <a href="/logout.php"><i class="fas fa-user"></i>&nbspLogout</a>
                    <?php else: ?>
                        <a href="/login.php"><i class="fas fa-user"></i>&nbspLogin</a>
                    <?php endif ?>
                </nav>
            </div>
        </section>
    </header>

    <!-- Main -->
    <main class="container">

        <h1>Categorie <?= htmlspecialchars($posts[0]->categoryName, ENT_QUOTES, 'UTF-8') ?></h1>


        <section class="post-content">

            <?php foreach ($posts as $post): ?>

                <article class="card">
                    <h2 class=""><?= htmlspecialchars(ucfirst($post->title), ENT_QUOTES, 'UTF-8') ?></h2>

                    <em>Categorie: <a href="/category.php?id=<?= intval($post->category_id) ?>"><?= htmlspecialchars($post->categoryName, ENT_QUOTES, 'UTF-8') ?></a></em></br>

                    <em>Posté par <?= htmlspecialchars($post->authorName, ENT_QUOTES, 'UTF-8') ?> le <?= $post->created ?></em>

                    <p><?= nl2br(substr(htmlspecialchars($post->content, ENT_QUOTES, 'UTF-8'), 0, 100)) ?>&nbsp...</p>

                    <p><a class="btn" href="/showpost.php?id=<?= intval($post->id) ?>">Voir plus</a></p>

                </article>

            <?php endforeach ?>

        </section>

        <aside class="categories">
            <h4>Categories</h4>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="/category.php?id=<?= intval($category->id) ?>">
                            <?= htmlspecialchars($category->categoryName, ENT_QUOTES, 'UTF-8')?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>

            <h4>les derniers articles</h4>
            <h4>les derniers commentaires</h4>
        </aside>

        <div class="clearfix"></div>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
