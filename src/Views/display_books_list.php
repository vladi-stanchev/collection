<?php

use Collection\Entities\Book;

require_once 'vendor/autoload.php';
// DISPLAYS AN AGNOSTIC LIST OF BOOKS

function display_books_list(array $books): string
{
    $output = '';

    if (!$books) {
        throw new Exception("No books found.");
    }

    foreach ($books as $book) {

        // Make sure $book is a Book object
        if (!$book instanceof Book) {
            throw new InvalidArgumentException("Invalid Book object found.");
        }
        $output .= "<h3>$book->title</h3>";

        // Author wiki ? <a> : <p>
        if (!empty($book->author_wiki_link)) {
            $output .= "<a href='$book->author_wiki_link' class='author'>$book->author_name</a> <br><br>";
        } else {
            $output .= "<p class='author'>$book->author_name</p> <br><br>";
        }
        // COVER IMG - Conditional rendering of 
        !empty($book->cover_img_url) && $output .= "<img src='$book->cover_img_url' alt='$book->title cover' class='book-cover'>";

        // SUMMARY
        !empty($book->summary) && $output .= "<h4>Summary</h4><p>$book->summary</p>";

        // GENRES LIST
        foreach ($book->genres_array as $genre) {
            $output .= "<div class='genre-tag'> $genre[genre_name] </div>";
        }

        // YEAR OF PUBLISHING
        !empty($book->pub_year) && $output .= "<p><span class='bold'>Published: </span>$book->pub_year</p>";

        // GOODREADS link ? <a> : <p>
        if (!empty($book->gr_url)) {
            $output .= "<p><span class='bold'>ISBN: </span><a href='$book->gr_url'>$book->isbn</a></p>";
        } else {
            $output .= "<p><span class='bold'>ISBN: </span>$book->isbn</p>";
        }

        // LANGUAGE
        !empty($book->lang_name) && $output .= "<p><span class='bold'>Language: </span>$book->lang_name</p>";

        $output .= "<hr>";
    }

    return  $output;
}
