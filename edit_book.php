<?php

use Collection\Entities\Book;
use Collection\Models\AuthorModel;
use Collection\Models\BookModel;
use Collection\Models\GenreModel;
use Collection\Models\LanguageModel;

require_once './db_connect.php';
require_once './Utilities/utils.php';
require_once 'src/Views/display_genres_checkbox.php';
require_once 'src/Views/display_authors_select.php';
require_once 'src/Views/display_languages_select.php';
require_once 'vendor/autoload.php';

$form_submitted = false;
$form_valid = false;

$bookModel = new BookModel($db);
$genreModel = new GenreModel($db);
$authorModel = new AuthorModel($db);
$languageModel = new LanguageModel($db);

$editableBook = $bookModel->getBookDetails(isset($_GET['book_id']));
// var_dump($editableBook);
if ($editableBook) {
    // echo 'hi';
    // Validate form                                                    VALIDATE GENRES HERE!!
    $form_errors = validation_add_new_book_form($editableBook->title, $editableBook->author_id, $editableBook->isbn, $editableBook->pub_year, $editableBook->cover_img_url, $editableBook->lang_id, $editableBook->gr_url, $editableBook->genres_array);
} else {
    // CANT DO 404
    // 1. show msgfmt_parse_message
    // 2. return to home
    echo 'NO BOOK ID FOUND IN DB';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST["title"] ?? null;
    $author = $_POST["author"] ?? null;
    $isbn = $_POST["isbn"] ?? null;
    // Optional params 
    $pub_year = intval($_POST["pub_year"]) ?? null;
    $cover_img_url = $_POST["cover_img_url"] ?? null;
    $summary = $_POST["summary"] ?? null;
    $lang = $_POST["lang"] ?? null;
    $gr_url = $_POST["gr_url"] ?? null;
    $genres = $_POST["genres"] ?? null;




    // var_dump($form_errors);

    // If no errors:
    // if (!$form_errors) {
    if (!$form_errors) {
        try {

            // CREATE NEW BOOK OBJECT TO INSERT IN DB
            $updatedBook = new Book(
                title: $title,
                author_id: $author,
                isbn: $isbn,
                pub_year: $pub_year,
                cover_img_url: $cover_img_url,
                summary: $summary,
                lang_id: $lang,
                gr_url: $gr_url,
                genres_array: $genres,
                id: isset($_GET['book_id'])
            );


            // echo $editableBook;


            // Add book to db
            $bookEditedOK = $bookModel->editBook($updatedBook);
            // var_dump($bookAddedOK);
            if ($bookEditedOK) {
                $form_submitted = true;
                header("refresh:3.14;url=index.php");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
        <h1>Edit Book</h1>
        <form method="post" action="edit_book.php?book_id=<?php echo $_GET['book_id'] ?>" class="add-book-form fade-in">

            <div class="left">
                <label for="title">Book Title<span class='red-star'>*</span></label>
                <input type="text" id="title" name="title" value="<?php echo $_POST["title"] ?? $editableBook->title; ?>">
                <span class="error"><?php echo isset($form_errors['title']) ? $form_errors['title'] : ''; ?></span>
                <br><br>

                <?php
                $allAuthors = $authorModel->getAllAuthors();
                echo display_authors_select($allAuthors, $editableBook->author_id);
                ?>

                <span class="error"><?php echo isset($form_errors['author']) ? $form_errors['author'] : ''; ?></span>
                <br><br>

                <label for="isbn">ISBN<span class='red-star'>*</span></label>
                <input type="text" id="isbn" name="isbn" maxlength="13" value="<?php echo $_POST["isbn"] ?? $editableBook->isbn; ?>">
                <span class="error"><?php echo isset($form_errors['isbn']) ? $form_errors['isbn'] : ''; ?></span>
                <br><br>

                <label for="summary">Summary</label>
                <textarea id="summary" name="summary" rows="13" cols="50"><?php echo $_POST["summary"] ?? $editableBook->summary; ?></textarea><br><br>
            </div>


            <div class="right">
                <label for="pub_year">Publication Year</label>
                <input type="text" id="pub_year" name="pub_year" maxlength="4" value="<?php echo $_POST["pub_year"] ?? $editableBook->pub_year; ?>">
                <span class="error"><?php echo isset($form_errors['pub_year']) ? $form_errors['pub_year'] : ''; ?></span>

                <br><br>
                <?php
                $allLanguages = $languageModel->getAllLanguages();
                echo display_languages_select($allLanguages, $editableBook->lang_id);
                ?>
                <span class="error"><?php echo isset($form_errors['lang']) ? $form_errors['lang'] : ''; ?></span>
                <br><br>

                <?php
                $allGenres = $genreModel->getAllGenres();
                echo display_genres_checkbox($allGenres, $editableBook->genres_array);
                ?>
                <br><br>
                <label for="cover_img_url">Cover Image URL</label>
                <input type="text" id="cover_img_url" name="cover_img_url" value="<?php echo $_POST["cover_img_url"] ?? $editableBook->cover_img_url; ?>">
                <span class="error"><?php echo isset($form_errors['cover_img_url']) ? $form_errors['cover_img_url'] : ''; ?></span>

                <br><br>

                <label for="gr_url">Goodreads URL</label>
                <input type="text" id="gr_url" name="gr_url" value="<?php echo $_POST["gr_url"] ?? $editableBook->gr_url; ?>">
                <span class="error"><?php echo isset($form_errors['gr_url']) ? $form_errors['gr_url'] : ''; ?></span>
                <br><br>

                <input type="submit" name="submit" value="Submit">
            </div>
        </form>
        <div class="flex-center">
            <a href='#'>New Author</a>&emsp;
            <a href='#'>New Language</a>&emsp;
            <a href='#'>New Genre</a>
            <br><br>
        </div>
    <?php else : ?>
        <!-- Display a success message and hide the form -->
        <div class="success-message flex-center">
            <p><span class='bold'><?php echo $title; ?></span> added successfully.<br>You'll be sent back to your Library in 3.14 seconds.</p>
            <a href='add_new_book.php'>Add a new one</a>
        </div>
    <?php endif; ?>
</body>

</html>