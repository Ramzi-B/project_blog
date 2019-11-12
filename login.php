<?php

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the admin user is connected redirect to index
 * Determine if "$_SESSION['auth']" declared and is different than NULL
 ******************************************************************************/

if (isset($_SESSION['auth'])) {
    http_response_code(301);
    header('Location: /');
    exit();
}

/**
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

 /**
  * Check
  *****************************************************************************/

if (isset($_POST) && !empty($_POST)) {

    if (empty($_POST['name']) || empty($_POST['password'])) {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs requis *";
    } else {

    // if (!empty($_POST) && !empty($_POST['name']) && !empty($_POST['password'])) {
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

            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['flashbox']['danger'] = 'Identifiant ou mot de passe incorrecte!';
        }
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
    <title>Se connecter</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <section class="container">
            <div class="header-top">
                <a href="/">Mon blog</a>
                <nav>
                    <a href="index.php">Home</a>
                    <a href="contact.php">Contact</a>
                </nav>
            </div>
        </section>
    </header>

    <main class="container">

        <h2>Se connecter</h2>

        <?php if (isset($_SESSION['flashbox'])): ?>
            <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
    			<section class="flashbox flashbox-<?= $type; ?>">
    				<p><?= $message; ?></p>
    			</section>
    		<?php endforeach ?>
    		<?php unset($_SESSION['flashbox']); ?>
    	<?php endif ?>

        <section class="login">

            <form action="" method="post">

                <a href="#">Mot de passe oublié</a>

                <p id="help-form-text"></p>

                <label for="name">Nom ou email</label>
                <input type="text" name="name" id="name" placeholder="Votre Nom ou email *" data-help="Votre Nom ou email">

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Votre mot de passe *" data-help="Votre mot de passe">

                <input type="submit" value="Se connecter">

            </form>

        </section>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>