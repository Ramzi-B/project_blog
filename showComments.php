<?php

/**
 * Includes files
 ******************************************************************************/

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

/**
 * Get all comments
 *******************************************************************************/

$sql = 'SELECT comments.* FROM comments';
$statement = getDatabase()->prepare($sql);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

// dd($comments);


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
    <title>Les commetaires</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
    <!-- Normalize -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
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

        <h1>Les derniers commentaires</h1>

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

        <?php foreach ($comments as $comment): ?>

            <article class="card">
                <p><?= validate($comment->content) ?></p>
                <em><?= validate(ucfirst($comment->name)) ?> le <?= validate($comment->created) ?></em>                

                <div class="card_button">

                    <a class="btn" href="/editComment.php?id=<?= intval($comment->id) ?>">Modifier</a>

                    <form action="/deleteComment.php?id=<?= intval($comment->id) ?>" method="POST"
                        onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display:inline;">
                        <input type="hidden" name="post_id" value="<?= intval($comment->post_id) ?>">
                        <input type="hidden" name="id" value="<?= intval($comment->id) ?>">

                        <!-- <input class="btn" type="submit" value="Supprimer"> -->                            
                        <button class="btn" type="submit">Supprimer</button>
                    </form>

                </div>

            </article>

        <?php endforeach ?>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <!-- JS -->
    <script src="js/main.js"></script>
</body>
</html>
