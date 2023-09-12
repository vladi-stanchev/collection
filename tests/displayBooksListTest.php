<?php

use Collection\Entities\Book;
use PHPUnit\Framework\TestCase;

require_once './src/display_books_list.php';
require_once './Utilities/utils.php';
require_once './vendor/autoload.php';

class displayBooksListTest extends TestCase
{
    public function test_success_display_books_list()
    {
        $expectedOutput = "
    <h3>Title</h3>
    <a href='https://wikipedia.org' class='author'>AuthorName</a> <br><br>
    <img src='https://wikipedia.org' alt='Title cover' class='book-cover'>
    <p>Summary</p>
    <p><span class='bold'>Published: </span>1900</p>
    <p><span class='bold'>ISBN: </span><a href='https://wikipedia.org'>1234567890123</a></p>
    <p><span class='bold'>Language: </span>Spanish</p>
    <hr>
    ";

        $book = [new Book(
            1,
            'Title',
            'AuthorName',
            'https://wikipedia.org',
            1234567890123,
            1900,
            'https://wikipedia.org',
            'Summary',
            'Spanish',
            'https://wikipedia.org',
            'notes',
            0
        )];

        $actualOutput = display_books_list($book);

        // Standardise test strings
        $expectedOutput = clean_test_string($expectedOutput);
        $actualOutput = clean_test_string($actualOutput);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    public function test_empty_array()
    {
        $this->expectExceptionMessage("No books found.");
        display_books_list([]);
    }
}
