<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=blogme', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$search = $_GET['search'] ?? '';

if ($search) {
    //filter products
    $statement = $pdo->prepare('SELECT * FROM blogs WHERE title LIKE :title ORDER BY created_date DESC');
    $statement->bindValue(':title', "%$search%");
} else {

    $statement = $pdo->prepare('SELECT * FROM blogs ORDER BY created_date DESC');
}

$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<script async src="https://www.googletagmanager.com/gtag/js?id=G-KZ70F6EGLN"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-KZ70F6EGLN');
</script>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ME | Gimna Katugampala</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/spacelab/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
</head>

<body>
    <!--navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand">BlogME</a>
    </nav>

    <h1 class="text-center mt-5">Blog Posts</h1>
    <div class="text-center mt-3">
        <a href="create.php" class="btn btn-success">Create Blog Post</a>
    </div>

    <form>
        <div class="input-group mb-3 mt-4">
            <input type="text" class="form-control" placeholder="Search For Posts" name="search" value="<?php echo $search ?>">
            <button class="btn btn-secondary" type="submit">Search</button>
        </div>

    </form>

    <!--post-->
    <?php foreach ($posts as $i => $post) : ?>
        <div class="card mb-3 mt-5">
            <h3 class="card-header">#<?php echo $i + 1 . ' ' . $post['title'] ?></h3>
            <div class="card-body">
                <h5 class="card-title"><strong>Created at :</strong> <?php echo $post['created_date'] ?></h5>
            </div>

            <?php if (!$post['image']) : ?>
                <img src="no-image.png">
            <?php else : ?>
                <img src="<?php echo $post['image'] ?>">
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-text"><strong><?php echo $post['description'] ?></strong></h5>
            </div>
            <div class="utility-btn">
                <a href="update.php?id=<?php echo $post['id'] ?>" type="button" class="btn btn-info">Edit</a>
                <form method="POST" action="delete.php">
                    <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</body>

</html>