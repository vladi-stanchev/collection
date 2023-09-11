<?php

use Collection\Models\BookModel;

$db = new PDO('mysql:host=db; dbname=collection', 'root', 'password');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

require_once 'vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>My Library</title>

    <meta name="description" content="Template HTML file">
    <meta name="author" content="iO Academy">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@10..48,200;10..48,400&display=swap" rel="stylesheet">
    <link rel="icon" href="images/favicon.png" sizes="192x192">
    <link rel="shortcut icon" href="images/favicon.png">
    <link rel="apple-touch-icon" href="images/favicon.png">
    <script defer src="js/index.js"></script>
</head>

<body>
    <h1>My Library</h1>
    <?php
    $bookModel = new BookModel($db);
    $books = $bookModel->getAllBooks();

    foreach ($books as $book) {
        echo "
        <h3>$book->title</h3>
        <a href='$book->wiki_link' class='author'>$book->author</a> <br><br>
        <img src='$book->cover_img_url' alt='$book->title cover' class='book-cover' >
        <p>$book->summary</p>
        <p><span class='bold'>Published: </span>$book->pub_year</p>
        <p><span class='bold'>ISBN: </span><a href='$book->gr_url'>$book->isbn</a></p>
        <p><span class='bold'>Language: </span>$book->lang_name</p>
        <hr>        
        ";
    }
    ?>
</body>

</html>