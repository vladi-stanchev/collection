<?php

use PHPUnit\Framework\TestCase;

require_once './src/Views/display_authors_select.php';
require_once './Utilities/utils.php';
require_once './vendor/autoload.php';

class DisplayAuthorsSelectTest extends TestCase
{
    public function testDisplayAuthorsSelectWithAuthors()
    {
        // Mock a list of authors
        $authors = [
            ['id' => 1, 'author_name' => 'Author 1'],
            ['id' => 2, 'author_name' => 'Author 2'],
            ['id' => 3, 'author_name' => 'Author 3'],
        ];

        // Call the function with the mock authors data
        $output = display_authors_select($authors);

        // Define the expected HTML output
        $expectedOutput = "<label for='author'>Author<span class='red-star'>*</span></label>
        <select name='author' id='author'>
            <option value='start' selected disabled hidden>Select an Option</option>
            <option value='1'>Author 1</option>
            <option value='2'>Author 2</option>
            <option value='3'>Author 3</option>
        </select>";

        // Assert that the function output matches the expected output
        $this->assertEquals(clean_test_string($expectedOutput), clean_test_string($output));
    }

    public function testDisplayAuthorsSelectWithNoAuthors()
    {
        // Mock an empty list of authors
        $authors = [];

        // Expect an exception to be thrown when no authors are found
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No authors found.");

        // Call the function with the mock authors data
        display_authors_select($authors);
    }
}
