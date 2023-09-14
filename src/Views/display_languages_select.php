<?php
// DISPLAY DROPDOWN SELECT HTML ELEMENTS WITH LANGUAGES 

function display_languages_select(array $languages): string
{
    $output = "<label for='lang'>Language</label>
    <select name='lang' id='lang'>
        <option value='' selected disabled hidden>Select an Option</option>
    ";

    if (!$languages) {
        throw new Exception("No languages found.");
    }

    foreach ($languages as $lang) {
        $selected = (isset($_POST['lang']) && $_POST['lang'] == $lang['id']) ? 'selected' : '';
        $output .= "<option value=$lang[id] $selected>$lang[lang_name]</option>";
    }

    $output .= "</select>";

    return  $output;
}
