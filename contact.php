<?php

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

// debug($_SERVER);
// debug($_SESSION);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="image/x-icon" rel="shortcut icon" href="/img/icon/favicon.ico">
    <title>Contact</title>
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
                        <a href="login.php">Login</a>
                    <?php endif ?>
                </nav>
            </div>
        </section>
    </header>

    <!-- Main -->
    <main class="container">

        <h1>Contact</h1>

        <section class="">

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
                <textarea name="content" id="content" cols="30" rows="10" placeholder="Votre message *" data-help="Votre Commentaire"></textarea>

                <input type="submit" value="Envoyer">

            </form>

        </section>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
