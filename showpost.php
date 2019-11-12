<?php

/**
 * Check if a session is already started if it is not started
 *******************************************************************************/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Includes files :
 *     _ Database connection
 *     _ Debug
 *******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

// debug($_SERVER);
// debug($_SESSION);

/**
 * Get all posts
 *******************************************************************************/
$sql = 'SELECT posts.id, title, content, author_id, category_id, created_at, authors.authorName, categories.categoryName FROM posts
    INNER JOIN authors ON posts.author_id = authors.id
    INNER JOIN categories ON posts.category_id = categories.id
    WHERE posts.id = :id
';
$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id',  $_GET['id'], PDO::PARAM_INT);
// $statement->execute([intval($_GET['id'])]);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

// debug($post);

/**
 * Get all comments that corespond to the post
 *******************************************************************************/

$sql = 'SELECT * FROM comments WHERE post_id = :id';
$statement = getDatabase()->prepare($sql);
$statement->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

// debug($comments);

/**
 * Count all comments per post
 *******************************************************************************/

$sql = 'SELECT COUNT(*) AS totalComments FROM comments WHERE post_id = :id';
$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id',  $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();
$totalPostComments = $result->totalComments;

// debug($totalComments);

/**
 * Check
 *******************************************************************************/

if (isset($_POST) && !empty($_POST)) {

    if (empty($_POST['name']) || empty($_POST['content']) || empty($_POST['email'])) {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs requis *";

    } else {

// if (!empty($_POST['name']) && !empty($_POST['content']) && !empty($_POST['email']) && !empty($_POST['website'])) {

        // $sql = 'INSERT INTO comments(comments.name, content, email, website, post_id, created_at) VALUES(?, ?, ?, ?, ?, NOW())';
        $sql = 'INSERT INTO comments(comments.name, content, email, website, post_id, created_at)
                VALUES(:name, :content, :email, :website, :post_id, NOW())
        ';
        $statement = getDatabase()->prepare($sql);
        // Binds parameters to variables
        $statement->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $statement->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
        $statement->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $statement->bindParam(':website', $_POST['website'], PDO::PARAM_STR);
        $statement->bindParam(':post_id', $_POST['post_id'], PDO::PARAM_INT);
        // $statement->execute([$_POST['name'], $_POST['content'], $_POST['email'], $_POST['website'], $_POST['post_id']]);
        $statement->execute();
        $_SESSION['flashbox']['success'] = 'Votre commentaire a bien été ajouté!';

        header('Location: showpost.php?id=' . intval($_POST['post_id']));
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
    <title><?= $post->title ?></title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <section class="container">
            <div class="header-top">
                <a href="/">Mon blog</a>
                <nav>
                    <?php if (isset($_SESSION['auth'])): ?>
                        <a href="index.php">Home</a>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="index.php">Home</a>
                        <a href="contact.php">Contact</a>
                        <a href="login.php">Login</a>
                    <?php endif ?>
                </nav>
            </div>
        </section>
    </header>

    <!-- Main -->
    <main class="container">

        <h1><?= htmlspecialchars(ucfirst($post->title)) ?></h1>

        <em>
            posté par <?= htmlspecialchars($post->authorName) ?> le <?= $post->created_at ?>
            Categorie&nbsp:
            <a href="category.php?id=<?= intval($post->category_id) ?>">
                &nbsp<?= htmlspecialchars($post->categoryName, ENT_QUOTES, 'UTF-8') ?>
            </a>
        </em>

        <?php if (isset($_SESSION['flashbox'])): ?>
            <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
    			<section class="flashbox flashbox-<?= $type; ?>">
    				<p><?= $message; ?></p>
    			</section>
    		<?php endforeach ?>
    		<?php unset($_SESSION['flashbox']); ?>
    	<?php endif ?>

        <nav>
            <?php if (isset($_SESSION['auth'])): ?>
                <a class="btn" href="editpost.php?id=<?= intval($post->id) ?>">Modifier</a>
                <a class="btn" href="deletepost.php?id=<?= intval($post->id) ?>">Supprimer</a>
            <?php endif ?>
        </nav>
        <p><?= nl2br(htmlspecialchars($post->content)) ?></p>


        <h3>Les derniers commentaires</h3>

        <p>Il y a <?= $totalPostComments ?> commentaire(s)</p>

        <?php foreach ($comments as $comment): ?>
            <article class="card">
                <p><?= htmlspecialchars($comment->content) ?></p>
                <em>posté par <?= htmlspecialchars(ucfirst($comment->name)) ?> le <?= $comment->created_at ?></em>
            </article>
        <?php endforeach ?>

        <h3>Laisser un commentaire</h3>

        <p>Votre adresse email ne sera pas publiée. Les champs obligatoires sont marqués d'un astérisque *.</p>

        <p id="help-form-text"></p>

        <form action="" method="post">

            <input type="hidden" name="post_id" value="<?= intval($_GET['id']) ?>">

            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Nom *" data-help="Votre Nom">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email *" data-help="Votre Email">

            <label for="website">Site web</label>
            <input type="url" id="website" name="website" placeholder="Site Web" data-help="Votre Site Web">

            <label for="content">Commentaire</label>
            <textarea name="content" id="content" cols="30" rows="10" placeholder="Commentaire *" data-help="Votre Commentaire"></textarea>

            <input type="submit" value="Envoyer">

        </form>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
