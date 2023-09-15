<?php

use PHPUnit\Framework\TestCase;

require_once './src/Views/display_genres_checkbox.php';
require_once './Utilities/utils.php';
require_once './vendor/autoload.php';

class DisplayGenresTest extends TestCase
{
    public function testDisplayGenresCheckboxWithGenres()
    {
        $genres = [
            ['id' => 1, 'genre_name' => 'Fiction'],
            ['id' => 2, 'genre_name' => 'Mystery'],
        ];

        $expectedOutput = "<label>Genres</label><br>
        <div class='checkbox-options-container flex-center'>
        <input type='checkbox' id='Fiction' name='genres[1]' value=1 >
        <label for='Fiction' class='checkbox-button' tabindex='0'>Fiction</label><br>
        <input type='checkbox' id='Mystery' name='genres[2]' value=2>
        <label for='Mystery' class='checkbox-button' tabindex='0'>Mystery</label><br>
        </div>";

        $this->assertEquals(clean_test_string($expectedOutput), clean_test_string(display_genres_checkbox($genres)));
    }

    public function testDisplayGenresCheckboxWithoutGenres()
    {
        $genres = [];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No genres found.");

        display_genres_checkbox($genres);
    }
}
