<?php

use Collection\Entities\Book;

use PHPUnit\Framework\TestCase;

require_once './src/Views/display_books_list.php';
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
    <h4>Summary</h4><p>Summary</p>
    <p><span class='bold'>Published: </span>1900</p>
    <p><span class='bold'>ISBN: </span><a href='https://wikipedia.org'>1234567890123</a></p>
    <p><span class='bold'>Language: </span>Spanish</p>
    <hr>
    ";

        $book = [new Book(
            'Title',
            1,
            1234567890123,
            1,
            'AuthorName',
            'https://wikipedia.org',
            'Summary',
            1900,
            1,
            'Spanish',
            'https://wikipedia.org',
            'https://wikipedia.org',
            null
        )];

        $actualOutput = display_books_list($book);

        // Standardise test strings
        $expectedOutput = clean_test_string($expectedOutput);
        $actualOutput = clean_test_string($actualOutput);

        $this->assertEquals($expectedOutput, $actualOutput);
    }




    public function testDisplayBooksListWithValidBooks()
    {
        // Mock an array of Book objects
        $books = [
            new Book(
                'Title',
                1,
                1234567890123,
                1,
                'AuthorName1',
                'https://wikipedia.org',
                'Summary',
                1900,
                1,
                'Spanish',
                'https://wikipedia.org',
                'https://wikipedia.org',
                null
            ),
            new Book(
                'Title',
                4,
                1234567890123,
                2,
                'AuthorName2',
                'https://wikipedia.org',
                'Summary',
                1900,
                1,
                'German',
                'https://wikipedia.org',
                'https://wikipedia.org'
            )
        ];

        // Call the function with the mock books data
        $output = display_books_list($books);

        // Assert that the output contains expected HTML elements
        $this->assertStringContainsString('<h3>Title</h3>', $output);
        $this->assertStringContainsString('<h3>Title</h3>', $output);
        $this->assertStringContainsString('<a href=\'https://wikipedia.org\' class=\'author\'>AuthorName1</a>', $output);
        $this->assertStringContainsString('<a href=\'https://wikipedia.org\' class=\'author\'>AuthorName2</a>', $output);
        // Add more assertions for other HTML elements as needed
    }

    public function testDisplayBooksListWithNoBooks()
    {
        // Mock an empty array
        $books = [];

        // Expect an exception when there are no books
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No books found.");

        // Call the function with the empty array
        display_books_list($books);
    }

    public function testDisplayBooksListWithInvalidBookObject()
    {
        // Mock an array with an invalid object (not a Book)
        $books = [new stdClass()];

        // Expect an exception when an invalid object is encountered
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid Book object found.");

        // Call the function with the array containing an invalid object
        display_books_list($books);
    }
}
