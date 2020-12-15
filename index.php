<?php

/**
 * Start session
 ******************************************************************************/

session_start();

/**
 * Includes files
 ******************************************************************************/
// define('ROOT_PATH', __DIR__);

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

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
if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $totalPages) {
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

$sql = 'SELECT
            posts.id, title, content, author_id, category_id, created_at,
            authors.authorName AS author,
            categories.categoryName AS category
        FROM posts
        JOIN authors ON posts.author_id = authors.id
        JOIN categories ON posts.category_id = categories.id
        ORDER BY created_at DESC LIMIT
'. $startCount . ',' . $postPerPage;

$statement = getDatabase()->query($sql);
$posts = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Get all categories
 ******************************************************************************/

$sql = 'SELECT id, categoryName FROM categories';
$statement = getDatabase()->query($sql);
$categories = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Get last comments
 ******************************************************************************/

$sql = 'SELECT comments.content, name, created_at FROM comments ORDER BY created_at DESC LIMIT 5';
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
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <header>
        <section class="container">
            <div class="header-top">
                <a href="/">Mon blog</a>
                <nav>
                    <a href="/">Home</a>
                    <a href="contact.php">Contact</a>
                    <?php if (isset($_SESSION['auth'])): ?>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
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
                    <a href="index.php?page=<?= $currentPage - 1 ?>">&laquo;</a>
                </li>
            <?php endif ?>

            <?php for ($index = 1; $index <= $totalPages; $index++): ?>
                <li>
                    <a href="index.php?page=<?= $index ?>"><?= $index ?></a>
                </li>
            <?php endfor ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="next">
                    <a href="index.php?page=<?= $currentPage + 1 ?>">&raquo;</a>
                </li>
            <?php endif ?>
        </ul>

        <?php

        // $config = getConfig('databaseConfig')['database'];
        // dd($config);
        // dd($config['dsn'] .';dbname='. $config['name'] .','. $config['username'] .','. $config['password']);

         ?>

        <section class="post-content">

            <?php foreach ($posts as $post): ?>

                <article class="card">

                    <section class="card__header">
                        <h2><?= htmlspecialchars(ucfirst($post->title), ENT_QUOTES, 'UTF-8') ?></h2>
                        <em>Posté par <?= htmlspecialchars($post->author, ENT_QUOTES, 'UTF-8') ?> le <?= $post->created_at ?></em></br>
                        <em>
                            <span>Categorie:&nbsp;</span>
                            <a href="category.php?id=<?= intval($post->category_id) ?>">
                                <?= htmlspecialchars($post->category, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </em>
                    </section>

                    <section class="card__body">
                        <p><?= nl2br(substr(htmlspecialchars($post->content, ENT_QUOTES, 'UTF-8'), 0, 100)) ?>&nbsp...</p>
                        <p><a class="btn" href="showpost.php?id=<?= intval($post->id) ?>">Voir plus</a></p>
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
                        <a href="category.php?id=<?= intval($category->id) ?>">
                            <?= htmlspecialchars($category->categoryName, ENT_QUOTES, 'UTF-8')?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>

            <h4>les derniers articles</h4>
        
            <ul>
                <li></li>
            </ul>
        
            <h4>les derniers commentaires</h4>
        
            <ul>
                <li></li>
            </ul>
        
        </aside>

        <div class="clearfix"></div>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
