<?php

/**
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Start session
 ******************************************************************************/

startSession();
// dd($_SESSION);

/**
 * Pagination
 ******************************************************************************/

$sql = 'SELECT COUNT(*) AS totalPosts FROM posts';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

$totalPosts = $result->totalPosts;
$postPerPage = 4;
$totalPages = ceil($totalPosts/$postPerPage);
$currentPage = 1;

// Check
if (isset($_GET['page']) && !empty($_GET['page']) && 
    $_GET['page'] > 0 && $_GET['page'] <= $totalPages) 
{
    $currentPage = (int) $_GET['page'];

    if ($currentPage == '1') {
        http_response_code(301);
        header('Location: /');
        exit();
    }

} else {
    $currentPage = 1;
}

$startCount = ($currentPage - 1) * $postPerPage;

/**
 * Get all posts
 ******************************************************************************/

$sql = 'SELECT posts.id, posts.title, posts.content, posts.author_id,
            posts.category_id, posts.created, posts.published,
            authors.authorName AS author,
            categories.categoryName AS category
        FROM posts
        JOIN authors
            ON posts.author_id = authors.id
        JOIN categories 
            ON posts.category_id = categories.id
        ORDER BY created DESC LIMIT
'. $startCount . ',' . $postPerPage;

$statement = getDatabase()->query($sql);
$posts = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

// dd($posts);

/**
 * Get all categories
 ******************************************************************************/

$sql = 'SELECT id, categoryName FROM categories';
$statement = getDatabase()->query($sql);
$categories = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Get last posts
 ******************************************************************************/

$sql = 'SELECT posts.title FROM posts 
    ORDER BY created DESC LIMIT 0, 3
';
$statement = getDatabase()->query($sql);
$result = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();
$lastPosts = $result;

/**
 * Get last comments
 ******************************************************************************/

$sql = 'SELECT comments.name, posts.title AS postTitle
    FROM comments
    INNER JOIN posts ON comments.post_id = posts.id 
    ORDER BY comments.created DESC LIMIT 0, 3
';
$statement = getDatabase()->query($sql);
$result = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();
$lastComments = $result;

// dd($_GET);
// dd($lastComments);
// dd($currentPage);
// dd($totalPosts);
// dd($totalPages);
// dd($_SERVER);
// dd($_SERVER['HTTP_USER_AGENT']);
// dd($_SERVER['REMOTE_ADDR']);
// dd($_SERVER['PHP_SELF']);
// dd($_SESSION);
// dd($posts);
// dd($categories);
// dd($_COOKIE);

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
    <title>Mon blog</title>
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

    <!-- Main -->
    <main class="container">

        <h1>Bienvenue sur mon blog</h1>

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

        <!-- Pagination -->
        <ul class="pagination">
            <?php if ($currentPage > 1): ?>
                <li class="prev">
                    <a href="/index.php?page=<?= $currentPage - 1 ?>"><i class="fas fa-chevron-left"></i></a>
                </li>
            <?php endif ?>

            <?php for ($index = 1; $index <= $totalPages; $index++): ?>
                <li>
                    <a href="/index.php?page=<?= $index ?>"><?= $index ?></a>
                </li>
            <?php endfor ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="next">
                    <a href="/index.php?page=<?= $currentPage + 1 ?>"><i class="fas fa-chevron-right"></i></a>
                </li>
            <?php endif ?>
        </ul>

        <!-- List of posts -->
        <section class="post-content">

            <?php foreach ($posts as $post): ?>

                <article class="card">

                    <section class="card__header">
                        <h2><?= validate(ucfirst($post->title)) ?></h2>
                        <em>Posté par <?= validate(ucfirst($post->author)) ?> le <?= validate($post->created) ?></em></br>
                        <em>
                            <span>Categorie:&nbsp;</span>
                            <a href="/category.php?id=<?= intval($post->category_id) ?>">
                                <?= validate(ucfirst($post->category)) ?>
                            </a>
                        </em>
                    </section>

                    <section class="card__body">
                        <p><?= nl2br(substr(validate($post->content), 0, 100)) ?>&nbsp;...</p>
                        <p><a class="btn" href="/showpost.php?id=<?= intval($post->id) ?>">Voir plus</a></p>
                    </section>

                </article>

            <?php endforeach ?>

        </section>

        <!-- List of categories -->
        <aside class="categories">

            <h4>Categories</h4>
        
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="/category.php?id=<?= intval($category->id) ?>">
                            <?= validate(ucfirst($category->categoryName)) ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>

            <h4>articles récents</h4>

            <?php foreach ($lastPosts as $lastPost): ?>

                <ul>
                    <li><?= validate($lastPost->title) ?></li>
                </ul>

            <?php endforeach ?>
        
            <h4>commentaires récents</h4>

            <?php foreach ($lastComments as $lastComment): ?>

                <ul>
                    <li><?= validate($lastComment->name). ' dans ' . validate($lastComment->postTitle)?></li>
                </ul>

            <?php endforeach ?>
        
        </aside>

        <div class="clearfix"></div>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
