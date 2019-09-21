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

<?php

    $sql = 'SELECT id, title, content, created_at FROM posts WHERE id = ?';
    $statement = $db->prepare($sql);
    $statement->execute([$_GET['post']]);

    $post = $statement->fetch();

?>

    <header>
        <h1><?php echo $post['title']; ?></h1>
    </header>

    <main class="container">

        <section class="container">

            <div class="card">
                <h1>
                    <?php echo htmlspecialchars($post['title']); ?>
                    <em><?php echo htmlspecialchars($post['created_at']); ?></em>
                </h1>

                <p>
                    <?php echo htmlspecialchars($post['content']) ?>
                </p>
            </div>

        </section>
    </main>

</body>
</html>