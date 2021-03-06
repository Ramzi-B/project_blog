<?php

/**
 * Includes files
 *******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 ******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    redirect('/', 301);
}

// dd($_SESSION);

/**
 * Pagination
 ******************************************************************************/

// Count all posts
$sql = 'SELECT COUNT("id") AS totalPosts FROM posts';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

$totalPosts = $result->totalPosts;
$postPerPage = 6; // number of posts wanted per page
$currentPage = 1; // The current page set to 1

// divide all posts by postsPerPages to get the number of posts per page
$totalPages = (int)ceil($totalPosts/$postPerPage);

// Check
if (isset($_GET['page']) && !empty($_GET['page']) &&
    $_GET['page'] > 0 && $_GET['page'] <= $totalPages)
{
    // $currentPage = intval($_GET['page']);
    $currentPage = $_GET['page'] ?? '1';
    // dd($currentPage);

    if (!filter_var($currentPage, FILTER_VALIDATE_INT)) {
        throw new \Exception("Le numéro de page est invalide");
    }

    if ($currentPage <= 0) {
        throw new \Exception("Le numéro de page est invalide");
    }

    if ($currentPage == '1') {
        redirect('dashboard.php', 301);
    }
    // dd($currentPage);
}

// $currentPage = intval($_GET['page']);

$startCount = ($currentPage - 1) * $postPerPage;

/**
 * Get all posts
 ******************************************************************************/

$sql = 'SELECT
            posts.id, posts.title, posts.content, posts.author_id, posts.category_id, posts.created,
            authors.authorName,
            categories.categoryName
        FROM posts
        INNER JOIN authors ON posts.author_id = authors.id
        INNER JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.created DESC LIMIT
'. $startCount . ',' . $postPerPage;

$statement = getDatabase()->query($sql);
$statement->bindParam('startCount', $startCount, PDO::PARAM_INT);
$statement->bindParam('postPerPage', $postPerPage, PDO::PARAM_INT);
$posts = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Get all categories
 ******************************************************************************/

$sql = 'SELECT categories.id, categories.categoryName FROM categories';
$statement = getDatabase()->query($sql);
$categories = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

/**
 * Count all authors
 ******************************************************************************/

$sql = 'SELECT COUNT("id") AS totalAuthors FROM authors';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();
$totalAuthors = $result->totalAuthors;

/**
 * Count all categories
 ******************************************************************************/

$sql = 'SELECT COUNT("id") AS totalCategories FROM categories';
$statement = getDatabase()->query($sql);
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();
$totalCategories = $result->totalCategories;

/**
 * Count all comments
 ******************************************************************************/

$sql = 'SELECT COUNT("id") AS totalComments FROM comments';
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
<html lang="fr" dir="ltr">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- https://favicon.io/favicon-generator -->
    <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
    <!-- Title -->
    <title>Dashboard</title>
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
                    <a href="/index.php"><i class="fas fa-home"></i>&nbsp;Home</a>
                    <a href="/contact.php"><i class="fas fa-envelope"></i>&nbsp;Contact</a>                    
                    <a href="/dashboard.php"><i class="fas fa-tachometer-alt"></i>&nbsp;Dashboard</a>                
                    <a href="/logout.php"><i class="fas fa-user"></i>&nbsp;Logout</a>
                </nav>
            </div>
        </section>
    </header>

    <!-- Main -->
    <main class="container">

        <h1>Dashboard</h1>
        <!-- <em>Bienvenue <?= $_SESSION['auth']['name']; ?></em> -->

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
                <a class="btn" href="/addPost.php">Ajouter un article</a>
                <a class="btn" href="/addAuthor.php">Ajouter un auteur</a>
                <a class="btn" href="/addCategory.php">Ajouter une catégorie</a>
                <a class="btn" href="/showAuthors.php">Les auteurs</a>
                <a class="btn" href="/showCategories.php">Les catégorie</a>
                <a class="btn" href="/showComments.php">Les commentaires</a>
            </nav>
        </section>

        <!-- Pagination -->
        <ul class="pagination">

            <?php if ($currentPage > 1): ?>
                <li class="prev">
                    <a href="/dashboard.php?page=<?= $currentPage - 1 ?>"><i class="fas fa-chevron-left"></i></a>
                </li>
            <?php endif ?>

            <?php for ($index = 1; $index <= $totalPages ; $index++): ?>
                <li>
                    <a href="/dashboard.php?page=<?= $index ?>"><?= $index ?></a>
                </li>
            <?php endfor ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="next">
                    <a href="/dashboard.php?page=<?= $currentPage + 1 ?>"><i class="fas fa-chevron-right"></i></a>
                </li>
            <?php endif ?>

        </ul>

        <!-- List of posts -->
        <section class="post-content">

            <?php foreach ($posts as $post): ?>

                <article class="card">

                    <h2 class="">
                        <a href="/showPost.php?id=<?= intval($post->id) ?>"><?= validate(ucfirst($post->title)) ?></a>
                    </h2>

                    <em><?= validate(ucfirst($post->authorName)) ?> le <?= validate($post->created) ?></em>
                    </br>

                    <em>
                        Categorie:
                        <a href="/category.php?id=<?= intval($post->category_id) ?>">
                            <?= validate(ucfirst($post->categoryName)) ?>
                        </a>
                    </em>

                    <p><?= nl2br(substr(validate($post->content), 0, 100)) ?>&nbsp;...</p>

                    <a class="btn" href="/editPost.php?id=<?= intval($post->id) ?>">Modifier</a>

                    <form action="/deletePost.php?id=<?= intval($post->id) ?>" method="POST"
                        onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display:inline;">
                        <input type="hidden" name="id" value="<?= intval($post->id) ?>">
                        <input class="btn" type="submit" value="Supprimer">
                    </form>

                </article>

            <?php endforeach ?>

        </section>

        <!-- List of categories -->
        <aside class="categories">

            <h4>Catégories</h4>

            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="/category.php?id=<?= intval($category->id) ?>">
                            <?= validate(ucfirst($category->categoryName)) ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>

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
