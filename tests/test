<?php

use PHPUnit\Framework\TestCase;

require_once './src/Views/display_languages_select.php';
require_once './Utilities/utils.php';
require_once './vendor/autoload.php';

class DisplayLanguagesSelectTest extends TestCase
{
    public function testDisplayLanguagesSelectWithLanguages()
    {
        // Mock a list of languages
        $languages = [
            ['id' => 1, 'lang_name' => 'English'],
            ['id' => 2, 'lang_name' => 'French'],
            ['id' => 3, 'lang_name' => 'Spanish'],
        ];

        // Call the function with the mock languages data
        $output = display_languages_select($languages);

        // Define the expected HTML output
        $expectedOutput = "<label for='lang'>Language</label>
        <select name='lang' id='lang'>
            <option value='' selected disabled hidden>Select an Option</option>
            <option value=1>English</option>
            <option value=2>French</option>
            <option value=3>Spanish</option>
        </select>";

        // Assert that the function output matches the expected output
        $this->assertEquals(clean_test_string($expectedOutput), clean_test_string($output));
    }

    public function testDisplayLanguagesSelectWithNoLanguages()
    {
        // Mock an empty list of languages
        $languages = [];

        // Expect an exception to be thrown when no languages are found
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No languages found.");


        // Call the function with the mock languages data
        display_languages_select($languages);
    }

    public function testDisplayLanguagesSelectWithSelectedLanguage()
    {
        // Mock a list of languages
        $languages = [
            ['id' => 1, 'lang_name' => 'English'],
            ['id' => 2, 'lang_name' => 'French'],
            ['id' => 3, 'lang_name' => 'Spanish'],
        ];

        // Simulate POST data with a selected language
        $_POST['lang'] = 2;

        // Call the function with the mock languages data
        $output = display_languages_select($languages);

        // Assert that the generated HTML includes the 'selected' attribute
        $this->assertStringContainsString('selected', $output);

        // Unset the POST data
        unset($_POST['lang']);
    }
}
