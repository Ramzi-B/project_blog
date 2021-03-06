<?php

/**
 * Includes files
 ******************************************************************************/

include_once 'inc/utils.php';
include_once 'inc/DatabaseConnection.php';

/**
 * Check if a session is already started if it is not started
 *******************************************************************************/

startSession();

/**
 * Get all posts
 *******************************************************************************/

$sql = 'SELECT posts.id, posts.title, posts.content,
        posts.author_id, posts.category_id, posts.created,
        authors.authorName,
        categories.categoryName
    FROM posts
    INNER JOIN authors
        ON posts.author_id = authors.id
    INNER JOIN categories 
        ON posts.category_id = categories.id
    WHERE posts.id = :id
';
$statement = getDatabase()->prepare($sql);
$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_OBJ);
$statement->closeCursor();

// debug($post);

/**
 * Get all comments that corespond to the post
 *******************************************************************************/

$sql = 'SELECT comments.id, comments.name, comments.content,
        comments.created, comments.updated, comments.post_id, comments.email,
        comments.ip, comments.url, comments.agent, comments.website
    FROM comments
    WHERE comments.post_id = :id
';
$statement = getDatabase()->prepare($sql);
$statement->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_OBJ);
$statement->closeCursor();

// dd($comments);

/**
 * Count all comments per post
 *******************************************************************************/

function countComments(int $id)
{
    $sql = 'SELECT COUNT("id") AS totalComments FROM comments WHERE comments.post_id = :id';

    $statement = getDatabase()->prepare($sql);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);
    $statement->closeCursor();

    return $result->totalComments;
}

// debug($totalComments);

/**
 * Check for empty fields
 *******************************************************************************/

if (isset($_POST) && !empty($_POST)) {

    if (empty($_POST['name']) || empty($_POST['content']) || empty($_POST['email'])) {
        $_SESSION['flashbox']['danger'] = "Vous devez remplir tout les champs requis *";
    } else {
        $sql = 'INSERT INTO comments(comments.name, comments.content, comments.email,
                comments.website, comments.post_id, comments.created)
            VALUES(:name, :content, :email, :website, :post_id, NOW())
        ';
        $statement = getDatabase()->prepare($sql);
        // Binds parameters to variables
        $statement->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $statement->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
        $statement->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $statement->bindParam(':website', $_POST['website'], PDO::PARAM_STR);
        $statement->bindParam(':post_id', $_POST['post_id'], PDO::PARAM_INT);
        $statement->execute();
        
        $_SESSION['flashbox']['success'] = 'Votre commentaire a bien été ajouté!';

        redirect('showPost.php?id=' . intval($_POST['post_id']));
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
    <title><?= validate($post->title) ?></title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
    <!-- Normalize -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
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

    <!-- Main -->
    <main class="container">

        <h1><?= validate(ucfirst($post->title)) ?></h1>

        <small>
            <i class="far fa-user">&nbsp;</i><?= validate(ucfirst($post->authorName)) ?>&nbsp;
            <i class="far fa-calendar-alt"></i>&nbsp;<?= validate($post->created) ?>

     
            <i class="fas fa-tag"></i>&nbsp;
            <a href="category.php?id=<?= intval($post->category_id) ?>">
                <?= validate(ucfirst($post->categoryName)) ?>
            </a>

            &nbsp;<i class="far fa-comment"></i><?= intval(countComments($post->id)) ?>

        </small>

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

        <!-- if admin connected show buttons -->
        <?php if (isset($_SESSION['auth'])): ?>
            <a class="btn" href="/editPost.php?id=<?= intval($post->id) ?>">Modifier</a>
            <form action="/deletePost.php?id=<?= intval($post->id) ?>" method="POST"
                onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display:inline;">
                <input type="hidden" name="id" value="<?= intval($post->id) ?>">
                <input class="btn" type="submit" value="Supprimer">
            </form>
        <?php endif ?>

        <p><?= nl2br(validate($post->content)) ?></p>

        <h3>Les derniers commentaires</h3>

        <?php foreach ($comments as $comment): ?>
            <article class="card">
                <p><?= validate($comment->content) ?></p>
                <em>
                    <i class="far fa-user"></i><?= validate(ucfirst($comment->name)) ?> 
                    <i class="far fa-calendar-alt"></i><?= validate($comment->created) ?>
                </em>
                
                <!-- if admin connected show buttons -->
                <?php if (isAuthenticated()): ?>

                    <div class="card_button">

                        <a class="btn" href="/editComment.php?id=<?= intval($comment->id) ?>">Modifier</a>
    
                        <form action="/deleteComment.php?id=<?= intval($comment->id) ?>" method="POST"
                            onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?= intval($comment->post_id) ?>">
                            <input type="hidden" name="id" value="<?= intval($comment->id) ?>">

                            <!-- <input class="btn" type="submit" value="Supprimer"> -->                            
                            <button class="btn" type="submit">Supprimer</button>
                        </form>

                    </div>

                <?php endif ?>
            </article>
        <?php endforeach ?>

        <h3>Laisser votre commentaire</h3>

        <small>Votre adresse email ne sera pas publiée. Les champs obligatoires sont marqués d'un astérisque *.</small>

        <p id="help-form-text"></p>

        <form action="" method="post">

            <input type="hidden" name="post_id" value="<?= intval($_GET['id']) ?>">

            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Nom *" data-help="Votre Nom">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email *" data-help="Votre Email">

            <label for="website">Site web</label>
            <input type="url" id="website" name="website" placeholder="http://votreSite.com" data-help="Votre Site Web">

            <label for="content">Commentaire</label>
            <textarea name="content" id="content" cols="30" rows="10" placeholder="Commentaire *" data-help="Votre Commentaire"></textarea>

            <input class="btn" type="submit" value="Envoyer">

        </form>

    </main>

    <footer>
        <p>Mon blog &copy; <?= Date('Y') ?> All rights reserved</p>
    </footer>

    <!-- JS -->
    <script src="js/main.js"></script>
</body>
</html>
