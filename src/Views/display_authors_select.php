<?php
// DISPLAYS A DROPDOWN SELECT ELEMENT WITH ALL AUTHORS

function display_authors_select(array $authors): string
{
    $output = "<label for='author'>Author<span class='red-star'>*</span></label>
    <select name='author' id='author'>
        <option value='start' selected disabled hidden>Select an Option</option>";

    if (!$authors) {
        throw new Exception("No authors found.");
    }

    foreach ($authors as $author) {
        // Check if $_POST has values and apply
        $selected = (isset($_POST['author']) && $_POST['author'] == $author['id']) ? 'selected' : '';
        $output .= "<option value='{$author['id']}' $selected>{$author['author_name']}</option>";
    }

    $output .= "</select>";

    return  $output;
}
