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
// Get the post
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

                <div class="card-comment">
                    <h2>Les commentaires</h2>

                    <?php
                    // Get comments
                    $sql = 'SELECT author, comment, comment_date FROM comments WHERE post_id = ? ORDER BY comment_date';
                    $statement = $db->prepare($sql);
                    $statement->execute([$_GET['post']]);

                    while ($comments = $statement->fetch()) {
                        ?>
                        
                        <p><?php echo htmlspecialchars($comments['author']); ?></p>
                        <em><?php echo htmlspecialchars($comments['comment_date']); ?></em>
                        <p><?php echo nl2br(htmlspecialchars($comments['comment'])); ?></p>
                        <?php
                    }
                    ?>

                </div>

                <h3>Laisser un commentaire</h3>

                <form action="" method="POST">

                    <label for="author">Pseudo</label>
                    <input type="text" name="author" placeholder="Pseudo*">

                    <label for="comment">Commetaire</label>
                    <textarea name="comment" placeholder="Commentaire*"></textarea>

                    <input type="submit" value="Envoyer">
                </form>

            </div>

        </section>
    </main>

</body>
</html>