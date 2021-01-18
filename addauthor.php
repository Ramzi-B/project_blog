<?php

/**
 * Includes files
 *******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if a session is already started if it is not started
 *******************************************************************************/

// startSession();

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
 * Check for empty fields
 *******************************************************************************/

if (!empty($_POST)) {

    if (empty($_POST['name']) || empty($_POST['email'])) {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs *";
    }

    if (!empty($_POST['name']) && !empty($_POST['email'])) {
        $sql = 'INSERT INTO authors(authorName, authorEmail) VALUES (:authorName, :authorEmail)';
        $statement = getDatabase()->prepare($sql);
        $statement->bindParam(':authorName', $_POST['name'], PDO::PARAM_STR);
        $statement->bindParam(':authorEmail', $_POST['email'], PDO::PARAM_STR);
        $statement->execute();
        // debug($pdo->lastInsertId());
        // die();
        $statement->closeCursor();

        $_SESSION['flashbox']['success'] = "L'auteur a bien été ajouté!";

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
    <title>Ajouter un auteur</title>
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

        <h1>Ajouter un auteur</h1>

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

        <form action="" method="POST">

            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Name *">

            <label for="content">Email</label>
            <input type="text" id="email" name="email" placeholder="Email *">

            <input type="submit" value="Enregistrer">
            <a class="btn" href="/dashboard.php">Annuler</a>

        </form>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
