<?php

use Collection\Models\BookModel;

require_once 'src/display_books_list.php';
require_once 'vendor/autoload.php';

$db = new PDO('mysql:host=db; dbname=collection', 'root', 'password');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$form_submitted = false;

$bookModel = new BookModel($db);

if (isset($_POST['title']) && isset($_POST['author']) && isset($_POST['isbn'])) {

    $title = $_POST["title"];
    $author = $_POST["author"];
    $isbn = $_POST["isbn"];

    // Optional params 
    // Convert $pub_year to an integer or set it to null if not provided
    $pub_year = isset($_POST["pub_year"]) ? (int)$_POST["pub_year"] : null;
    $cover_img_url = $_POST["cover_img_url"] ?? null;
    $summary = $_POST["summary"] ?? null;
    $lang = $_POST["lang"] ?? null;
    $gr_url = $_POST["gr_url"] ?? null;
    $genres = $_POST["genres"] ?? null;

    try {
        $bookAddedOK = $bookModel->addNewBook($title, $author, $isbn, $pub_year, $cover_img_url, $summary, $lang, $gr_url, $genres);
        if ($bookAddedOK) {
            $form_submitted = true;
            header("refresh:5;url=index.php");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Add New Book</title>

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
    <?php if (!$form_submitted) : ?>
        <h1>Add a New Book</h1>
        <form method="post" action="add_new_book.php" class="add-book-form">

            <div class="left">
                <label for="title">Book Title</label>
                <input type="text" id="title" name="title" required><br><br>

                <label for="author">Author</label>
                <select name="author" id="author" required>
                    <option value="" selected disabled hidden>Select an Option</option>

                    <?php
                    $authors = $bookModel->getAllAuthors();

                    foreach ($authors as $author) {
                        echo ("<option value=$author[id]>$author[author_name]</option>");
                    }
                    ?>

                </select>
                <br><br>

                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" maxlength="13"><br><br>

                <label for="summary">Summary</label>
                <textarea id="summary" name="summary" rows="13" cols="50" required></textarea><br><br>
            </div>
            <div class="right">

                <label for="pub_year">Publication Year</label>
                <input type="number" id="pub_year" name="pub_year" min="1000" max="<?php echo date('Y') ?>"><br><br>

                <label for="lang">Language</label>


                <select name="lang" id="lang">
                    <option value="" selected disabled hidden>Select an Option</option>

                    <?php
                    $languages = $bookModel->getAllLanguages();

                    foreach ($languages as $lang) {
                        echo ("<option value=$lang[id]>$lang[lang_name]</option>");
                    }
                    ?>

                </select>
                <br><br>
                <label>Genres</label><br>
                <div class="checkbox-options-container flex-center">

                    <?php
                    $genres = $bookModel->getAllGenres();

                    foreach ($genres as $genre) {
                        echo ("  <input type='checkbox' id='$genre[genre_name]' name='genres[$genre[id]]' value=$genre[id]>
    <label for='$genre[genre_name]' class='checkbox-button' tabindex='0'>$genre[genre_name]</label><br>");
                    }
                    ?>
                </div>
                <br><br>
                <label for="cover_img_url">Cover Image URL</label>
                <input type="url" id="cover_img_url" name="cover_img_url"><br><br>

                <label for="gr_url">Goodreads URL</label>
                <input type="url" id="gr_url" name="gr_url"><br><br>

                <input type="submit" name="submit" value="Submit">
            </div>
        </form>
        <div class="flex-center">
            <a href='#'>New Author</a>&emsp;
            <a href='#'>New Language</a>&emsp;
            <a href='#'>New Genre</a>
        </div>
    <?php else : ?>
        <!-- Display a success message and hide the form -->
        <div class="success-message flex-center">
            <p><?php echo $title; ?> added successfully!<br>You'll be sent back to your Library in 5 seconds.</p>
            <a href='add_new_book.php'>Add a new one</a>
        </div>
    <?php endif; ?>
</body>

</html>