<?php

/**
 * Check if a session is already started if it is not started
 *******************************************************************************/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 *******************************************************************************/

if (!isset($_SESSION['auth'])) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    http_response_code(301);
    header('Location: /');
    exit();
}

/**
 * Includes files :
 * _ Database connection
 * _ Utils
 *******************************************************************************/

include 'inc/utils.php';
include 'inc/DatabaseConnection.php';

// debug($_SESSION);

/**
 * Check
 *******************************************************************************/

if (!empty($_POST)) {

    if (empty($_POST['category'])) {
        $_SESSION['flashbox']['danger'] = "Le champ est vide *";
    }

    if (!empty($_POST['category'])) {
        $sql = 'INSERT INTO categories(categoryName) VALUES (:categoryName)';
        $statement = getDatabase()->prepare($sql);
        $statement->bindParam(':categoryName', $_POST['category'], PDO::PARAM_STR);
        $statement->execute();
        // debug($pdo->lastInsertId());
        // die();
        $statement->closeCursor();

        $_SESSION['flashbox']['success'] = "La categorie a bien été ajoutée!";

        header('Location: /dashboard.php');
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
    <title>Ajouter une catégorie</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <section class="container">
            <div class="header-top">
                <a href="/">Mon blog</a>
                <nav>
                    <a href="dashboard.php">Dashboard</a>
                </nav>
            </div>
        </section>
    </header>

    <!-- Main -->
    <main class="container">

        <h1>Ajouter une categorie</h1>

        <!--  -->
        <?php if (isset($_SESSION['flashbox'])): ?>
            <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
    			<section class="flashbox flashbox-<?= $type; ?>">
    				<p><?= $message; ?></p>
    			</section>
    		<?php endforeach ?>
    		<?php unset($_SESSION['flashbox']); ?>
    	<?php endif ?>

        <form action="" method="post">

            <label for="category">Catégorie</label>
            <input type="text" id="category" name="category" placeholder="Categorie *">

            <input type="submit" value="Enregistrer">
            <a class="btn" href="dashboard.php">Annuler</a>

        </form>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
