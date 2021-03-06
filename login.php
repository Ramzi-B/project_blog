<?php

/**
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if the admin user is connected redirect to index
 ******************************************************************************/

if (isAuthenticated()) {
    redirect('/', 301);
}

/**
 * Check for empty fields
 *****************************************************************************/

if (isset($_POST) && !empty($_POST)) {

    if (empty($_POST['name']) || empty($_POST['password'])) {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs requis *";
    } else {
        $sql = 'SELECT * FROM admins WHERE admins.name = :name OR admins.email = :name';
        $statement = getDatabase()->prepare($sql);
        $statement->execute(['name' => $_POST['name']]);
        $user = $statement->fetch();
        $statement->closeCursor();

        // dd($user);
        // dd($_POST);
        // dd($_SESSION);

        // check password
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['auth'] = $user;
            $_SESSION['flashbox']['success'] = 'Vous êtes maintenant connecté!';
            redirect('dashboard.php');
        } else {
            $_SESSION['flashbox']['danger'] = 'Identifiant ou mot de passe incorrecte!';
        }
    }
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
    <title>Se connecter</title>
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
                        <a href="/dashboard.php"><i class="fas fa-toolbox"></i>&nbsp;Dashboard</a>
                        <a href="/logout.php"><i class="fas fa-user"></i>&nbsp;Logout</a>
                    <?php else: ?>
                        <a href="/login.php"><i class="fas fa-user"></i>&nbsp;Login</a>
                    <?php endif ?>
                </nav>
            </div>
        </section>
    </header>

    <main class="container">

        <h2>Se connecter</h2>

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

        <section class="">

            <form action="" method="POST">

                <a href="#">Mot de passe oublié</a>

                <p id="help-form-text"></p>

                <label for="name">Nom ou email</label>
                <input type="text" name="name" id="name" placeholder="Votre Nom ou email *" data-help="Votre Nom ou email" autocomplete="off" required>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Votre mot de passe *" data-help="Votre mot de passe" required>

                <input class="btn" type="submit" value="Se connecter">

            </form>

        </section>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <!-- JS -->
    <script src="js/main.js"></script>
</body>
</html>
