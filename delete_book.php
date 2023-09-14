<?php

use Collection\Models\BookModel;

require_once './db_connect.php';
// require_once './Utilities/utils.php';
require_once 'vendor/autoload.php';

$form_submitted = false;

$bookModel = new BookModel($db);

$success = false;
$bookDeletedOK = false;

if (isset($_POST["id"]) && isset($_POST["title"])) {

    // IF DELETION CONFIRMED, FLAG AS DELETED IN DB
    if (isset($_POST['delete-confirm'])) {
        $bookDeletedOK = $bookModel->deleteBook($_POST['id']);
    }

    if ($bookDeletedOK) {
        $success = true;
        header("refresh:4;url=index.php");
    }
} else {
    // REDIRECT TO HOMEPAGE 
    header("Location:index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Delete Book</title>

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
    <?php if (!$success) : ?>
        <h2>Sure you want to delete "<?php echo $_POST["title"] ?>" from your collection?</h2>

        <!-- DELETE BUTTON -->
        <form method='post' action='delete_book.php'>
            <input type='hidden' name='id' value='<?php echo $_POST["id"] ?>'>
            <input type='hidden' name='title' value='<?php echo $_POST["title"] ?>'>
            <input type='submit' name='delete-confirm' value='Delete' class='delete'>
        </form>

        <!-- CANCEL BUTTON -->
        <form action='index.php'>
            <input type='submit' name='' value='No, go back'>
        </form>

    <?php else : ?>

        <!-- Display a success message  -->
        <div class="success-message flex-center">
            <h2>"<?php echo $_POST["title"]; ?>" has been deleted.<br>Taking you back Home in 4 seconds.</h2>
        </div>
    <?php endif; ?>
</body>

</html>