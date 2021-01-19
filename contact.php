<?php

/**
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';

/**
 * Check if a session is already started if it is not started
 ******************************************************************************/

startSession();

/** 
 * Check for empty fields
 *******************************************************************************/

if (isset($_POST) && !empty($_POST)) {
    if (empty($_POST['name']) || empty($_POST['email']) ||
        empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs requis *";
    } else {
        $name = validate($_POST['name']);
        $email_address = validate($_POST['email']);
        $website = validate($_POST['website']);
        $message = validate($_POST['message']);
        
        // Create the email
        $to = 'admin@monblog.com';
        $email_subject = "Formulaire de contact du site web:\n{$name}";
        $email_body = "Vous avez reçu un nouveau message.\n\n".
            "Name: {$name}\n\nEmail: {$email_address}\n\nSite web: {$website}\n\nMessage:\n{$message}"
        ;    
        $headers = "From: noreply@monblog.com\n";
        $headers .= "Reply-To: $email_address";   

        // Send the message
        mail($to, $email_subject, $email_body, $headers);

        $_SESSION['flashbox']['success'] = 'Votre message a bien été envoyé!';
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
    <title>Contact</title>
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
                        <a href="/logout.php"><i class="fas fa-user"></i>&nbspLogout</a>
                    <?php else: ?>
                        <a href="/login.php"><i class="fas fa-user"></i>&nbspLogin</a>
                    <?php endif ?>
                </nav>
            </div>
        </section>
    </header>

    <!-- Main -->
    <main class="container">

        <?php if (isset($_SESSION['flashbox'])): ?>
            <?php foreach ($_SESSION['flashbox'] as $type => $message): ?>
                <section class="flashbox flashbox-<?= $type; ?>">
                    <span class="close"></span>
                    <p><?= $message; ?></p>
                </section>
            <?php endforeach ?>
            <?php unset($_SESSION['flashbox']); ?>
        <?php endif ?>

        <h1>Contact</h1>

        <section class="">

            <p id="help-form-text"></p>

            <form action="" method="POST">

                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Nom *" data-help="Votre Nom">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email *" data-help="Votre Email">

                <label for="website">Site web</label>
                <input type="url" id="website" name="website" placeholder="Site Web" data-help="Votre Site Web">

                <label for="message">Message</label>
                <textarea name="message" id="message" cols="30" rows="10" placeholder="Votre message *" data-help="Votre Commentaire"></textarea>

                <input class="btn" type="submit" value="Envoyer">

            </form>

        </section>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
