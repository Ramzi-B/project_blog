<?php

/**
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if the admin user is logged in
 * if he is not redirected to the index page
 *******************************************************************************/

if (!isAuthenticated()) {
    $_SESSION['flashbox']['danger'] = "Vous n'avez pas le droit d'accéder à cette page!";
    redirect('/', 301);
}

/**
 * Get the category to edit
 ******************************************************************************/

$sql = 'SELECT categories.id, categories.categoryName FROM categories WHERE categories.id = :id';

$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$category = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

// dd($_GET);
// die;

/**
 * Check
 ******************************************************************************/

if (isset($_POST) && !empty($_POST)) {
    // dd($_POST);
    // dd($_GET);
    // dd($_SESSION);
    // die();
    $sql = 'UPDATE categories SET categories.categoryName = :categoryName, categories.updated = NOW() 
        WHERE categories.id = :id
    ';

    $statement = getDatabase()->prepare($sql);
    $statement->bindParam(':categoryName', $_POST['categoryName'], PDO::PARAM_STR);
    $statement->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION['flashbox']['success'] = "La catégorie a bien été modifiée!";

    redirect('showCategories.php');
}
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
        <title>Modifier la catégorie</title>
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

        <main class="container">

            <h1>Modifier la catégorie</h1>
            
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


            <form action="" method="post">

                <input type="hidden" name="id" value="<?= intval($category->id) ?>">

                <label for="categoryName">Name</label>
                <input type="text" id="categoryName" name="categoryName" value="<?= validate($category->categoryName) ?>" data-help="Name">

                <input class="btn" type="submit" value="Mettre à jour">
                <a class="btn" href="/showCategories.php">Annuler</a>

            </form>

        </main>

        <footer>
            <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
        </footer>

        <!-- JS -->
        <script src="/js/main.js"></script>
    </body>
</html>
