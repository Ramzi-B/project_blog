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
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 ******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    http_response_code(301);
    header('Location: /');
    exit();
}

/**
 * Pagination
 ******************************************************************************/

// Count all posts
$sql = 'SELECT COUNT(*) AS totalPosts FROM posts';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

$totalPosts = $result->totalPosts;
$postPerPage = 6; // number of posts wanted per page
$currentPage = 1; // The current page set to 1

// divide all posts by postsPerPages to get the number of posts per page
$totalPages = ceil($totalPosts/$postPerPage);

// Check
if (isset($_GET['page']) && !empty($_GET['page']) &&
    $_GET['page'] > 0 && $_GET['page'] <= $totalPages)
{
    // $currentPage = intval($_GET['page']);

    $currentPage = $_GET['page'] ?? '1';
    // dd($currentPage);

    if (!filter_var($currentPage, FILTER_VALIDATE_INT)) {
        throw new \Exception("Le numéro de page est invalide", 1);
    }

    if ($currentPage <= 0) {
        throw new \Exception("Le numéro de page est invalide", 1);
    }

    if ($currentPage == '1') {
        http_response_code(301);
        header('Location: /dashboard.php');
        exit();
    }
    // dd($currentPage);
}

// $currentPage = intval($_GET['page']);

$startCount = ($currentPage - 1) * $postPerPage;

/**
 * Get all posts
 ******************************************************************************/

$sql = 'SELECT
            posts.id, title, content, author_id, category_id, created_at,
            authors.authorName,
            categories.categoryName
        FROM posts
        INNER JOIN authors ON posts.author_id = authors.id
        INNER JOIN categories ON posts.category_id = categories.id
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
 * Count all authors
 ******************************************************************************/

$sql = 'SELECT COUNT(*) AS totalAuthors FROM authors';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();
$totalAuthors = $result->totalAuthors;

/**
 * Count all categories
 ******************************************************************************/

$sql = 'SELECT COUNT(*) AS totalCategories FROM categories';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();
$totalCategories = $result->totalCategories;

/**
 * Count all comments
 ******************************************************************************/

$sql = 'SELECT COUNT(*) AS totalComments FROM comments';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();
$totalComments = $result->totalComments;

// dd($totalAuthors);
// dd($totalCategories);
// dd($totalComments);
// dd($totalPosts);
// dd($currentPage);
// dd($posts);
// dd($currentPage);
// dd($_GET);
// dd($_SERVER);
// dd($_SERVER['PHP_SELF']);
// dd($_SESSION);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
    <title>Dashboard</title>
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
                    <a href="/index.php"><i class="fas fa-home"></i>&nbsp;Home</a>
                    <a href="/contact.php"><i class="fas fa-envelope"></i>&nbsp;Contact</a>                    
                    <a href="/dashboard.php"><i class="fas fa-toolbox"></i>&nbsp;Dashboard</a>                    
                    <a href="/logout.php"><i class="fas fa-user"></i>&nbsp;Logout</a>
                </nav>
            </div>
        </section>
    </header>

    <!-- Main -->
    <main class="container">

        <h1>Dashboard</h1>
        <!-- <em>Bienvenue <?= $_SESSION['auth']['name']; ?></em> -->

        <?php if (isset($_SESSION['flashbox'])): ?>
            <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
    			<section class="flashbox flashbox-<?= $type; ?>">
                    <span class="close"></span>
    				<p><?= $message; ?></p>
    			</section>
    		<?php endforeach ?>
    		<?php unset($_SESSION['flashbox']); ?>
    	<?php endif ?>


        <section class="dashboard">
            <table>
                <thead>
                    <tr>
                        <th>Articles</th>
                        <th>Commentaires</th>
                        <th>Auteurs</th>
                        <th>Catégories</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= (int)$totalPosts ?></td>
                        <td><?= (int)$totalComments ?></td>
                        <td><?= (int)$totalAuthors ?></td>
                        <td><?= (int)$totalCategories ?></td>
                    </tr>
                </tbody>
            </table>

            <nav>
                <a class="btn" href="/addpost.php">Ajouter un article</a>
                <a class="btn" href="/addauthor.php">Ajouter un auteur</a>
                <a class="btn" href="/addcategory.php">Ajouter une catégorie</a>
            </nav>
        </section>

        <!-- Pagination -->
        <ul class="pagination">

            <?php if ($currentPage > 1): ?>
                <li class="prev">
                    <a href="/dashboard.php?page=<?= $currentPage - 1 ?>">&laquo;</a>
                </li>
            <?php endif ?>

            <?php for ($index = 1; $index <= $totalPages ; $index++): ?>
                <li>
                    <a href="/dashboard.php?page=<?= $index ?>"><?= $index ?></a>
                </li>
            <?php endfor ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="next">
                    <a href="/dashboard.php?page=<?= $currentPage + 1 ?>">&raquo;</a>
                </li>
            <?php endif ?>

        </ul>

        <section class="post-content">

            <?php foreach ($posts as $post): ?>

                <article class="card">

                    <h2 class="">
                        <a href="/showpost.php?id=<?= intval($post->id) ?>"><?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?></a>
                    </h2>

                    <em>Posté par <?= htmlspecialchars($post->authorName, ENT_QUOTES, 'UTF-8') ?> le <?= $post->created_at ?></em></br>

                    <em>
                        Categorie&nbsp;:
                        <a href="/category.php?id=<?= intval($post->category_id) ?>">
                            &nbsp<?= htmlspecialchars($post->categoryName, ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </em>

                    <p><?= nl2br(substr(htmlspecialchars($post->content, ENT_QUOTES, 'UTF-8'), 0, 100)) ?>&nbsp;...</p>

                    <a class="btn" href="/editpost.php?id=<?= intval($post->id) ?>">Modifier</a>
                    <a class="btn" href="/deletepost.php?id=<?= intval($post->id) ?>">Supprimer</a>

                </article>

            <?php endforeach ?>

        </section>

        <!-- List of categories -->
        <aside class="categories">
            <h4>Catégories</h4>
            <ul class="navbar">
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="/category.php?id=<?= intval($category->id) ?>">
                            <?= htmlspecialchars($category->categoryName, ENT_QUOTES, 'UTF-8')?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </aside>

        <div class="clearfix"></div>

        <section>
            <table>

                <thead>
                    <tr>
                        <th>Titres</th>
                        <th>Crée le</th>
                        <th>Autheurs</th>
                        <th>&nbsp</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><a href="/showpost.php?id=<?= intval($post->id) ?>"><?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?></a></td>
                        <td><?= $post->created_at ?></td>
                        <td><?= htmlspecialchars($post->authorName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a class="btn" href="/editpost.php?id=<?= intval($post->id) ?>">Modifier</a>
                            <a class="btn" href="/deletepost.php?id=<?= intval($post->id) ?>">Supprimer</a>
                        </td>
                    </tr>
                </tbody>

            </table>
        </section>
    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>
