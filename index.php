<?php
    require_once 'inc/utils.php';

    // Connect to the database
    try {
        $db = new PDO('mysql:host=localhost;dbname=tp_blog;charset=utf8', 'root', 'rboxer',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    } catch (Exception $e) {
        die('Error: '.$e->getMessage());
    }
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Mon blog</h1>
    </header>

        <main class="container">

            <section class="container">

                <?php

                    $sql = 'SELECT id, title, content, created_at FROM posts';
                    $statement = $db->query($sql);

                    while ($posts = $statement->fetch()) {
                        ?>

                        <div class="card">
                            <h1>
                                <?php echo htmlspecialchars($posts['title']); ?>
                                <em><?php echo htmlspecialchars($posts['created_at']); ?></em>
                            </h1>

                            <p>
                                <?php echo htmlspecialchars($posts['content']) ?>
                            </p>

                            <a href="show.php?post=<?php echo intval($posts['id']); ?>">Voir plus</a>
                        </div>

                        <?php
                    }
                ?>

        </section>
    </main>

</body>
</html>