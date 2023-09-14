<?php

use Collection\Models\BookModel;

require_once './db_connect.php';

require_once 'src/Views/display_books_list.php';
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
    <div class="collection-display">
        <?php
        $bookModel = new BookModel($db);
        $books = $bookModel->getAllBooks();

        try {
            echo display_books_list($books);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        ?>
    </div>
</body>

</html>