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
 * Get all categories
 ******************************************************************************/

$sql = 'SELECT categories.id, categories.categoryName FROM categories';
$statement = getDatabase()->query($sql);
$categories = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Get all posts by category
 ******************************************************************************/

$sql = 'SELECT
            posts.id, posts.title, posts.content, posts.author_id, posts.category_id, posts.created,
            authors.authorName,
            categories.categoryName
    FROM posts
    INNER JOIN authors ON posts.author_id = authors.id
    INNER JOIN categories ON posts.category_id = categories.id
    WHERE posts.category_id = :id ORDER BY posts.created
';
$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Count all comments per post
 *******************************************************************************/

function countComments(int $id)
{
    $sql = 'SELECT COUNT("id") AS totalComments FROM comments WHERE comments.post_id = :id';

    $statement = getDatabase()->prepare($sql);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);
    $statement->closeCursor();

    return $result->totalComments;
}

// dd($posts);
// dd($categories);
// dd($totalCategories);
// dd($postPerPage);
// dd($totalPages);
// dd($_GET);

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
    <title>Categorie <?= validate($posts[0]->categoryName ?? 'Categories') ?></title>
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

        <?php if (empty($posts)): ?>
            <p>Il n'y aucun articles</p>
        <?php else: ?>
            <h1>Categorie <?= validate($posts[0]->categoryName) ?></h1>
        <?php endif ?>

        <section class="post-content">

            <?php foreach ($posts as $post): ?>

                <article class="card">
                    <h2 class=""><?= validate(ucfirst($post->title)) ?></h2>

                    <small>
                        <i class="far fa-user"></i>&nbsp;<?= validate(ucfirst($post->authorName)) ?> 
                        <i class="far fa-comment"></i>&nbsp;<?= intval(countComments($post->id)) ?>
                        <br>
                        <i class="far fa-calendar-alt"></i>&nbsp;<?= validate($post->created) ?>
                        Categorie:&nbsp;
                        <a href="/category.php?id=<?= intval($post->category_id) ?>">
                            <?= validate(ucfirst($post->categoryName)) ?>
                        </a>
                        </span>
                    </small>

                    <p><?= nl2br(substr(validate($post->content), 0, 100)) ?>&nbsp...</p>

                    <p><a class="btn" href="/showPost.php?id=<?= intval($post->id) ?>">Voir plus</a></p>

                </article>

            <?php endforeach ?>

        </section>

        <aside class="categories">

            <h4>Categories</h4>
            
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="/category.php?id=<?= intval($category->id) ?>">
                            <?= validate($category->categoryName) ?>
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

    <!-- JS -->
    <script src="js/main.js"></script>
</body>
</html>
