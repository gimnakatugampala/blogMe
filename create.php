<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=blogme', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$title = '';
$description = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = date('Y-m-d H:i:s');

    $errors = [];

    if (!$title) {
        $errors[] = 'Title is required!';
    }

    if (!$description) {
        $errors[] = 'Description is required!';
    }


    if (!is_dir('images')) {
        mkdir('images');
    }

    if (empty($errors)) {
        $image  = $_FILES['image'] ?? null;
        //if the image path does not exist

        $imagePath = '';
        if ($image && $image['tmp_name']) {
            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }


        $statement = $pdo->prepare("INSERT INTO blogs (title,image,description,created_date)
        VALUES (:title,:image,:description,:date)");

        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':date', $date);
        $statement->execute();

        header('Location:index.php');
    }
}

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}


?>

<!DOCTYPE html>
<html lang="en">
<!-- Global site tag (gtag.js) - Google Analytics -->
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
    <title>Blog ME | Create Blog | Gimna Katugampala</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/spacelab/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
</head>

<body>
    <!--navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand">BlogME</a>
    </nav>

    <h3 class="text-center">Create a Blog Post</h3>
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-secondary">Back To Posts</a>
    </div>


    <?php if (!empty($errors)) : ?>
        <div class="alert alert-dismissible alert-danger">
            <?php foreach ($errors as  $error) : ?>
                <strong><?php echo $error ?></strong><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!--contact form-->
    <form method="POST" action="create.php" enctype="multipart/form-data">
        <div class="form-group">
            <label>Choose Image</label>
            <input type="file" class="form-control-file" name="image">
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="description"><?php echo $description ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</body>

</html>