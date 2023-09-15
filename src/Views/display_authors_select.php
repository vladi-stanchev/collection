<?php
// DISPLAYS A DROPDOWN SELECT ELEMENT WITH ALL AUTHORS

function display_authors_select(array $authors, int $selected_author = null): string
{
    $output = "<label for='author'>Author<span class='red-star'>*</span></label>
    <select name='author' id='author'>
        <option value='start' selected disabled hidden>Select an Option</option>";

    if (!$authors) {
        throw new Exception("No authors found.");
    }

    foreach ($authors as $author) {
        // Check if $_POST has values and apply or use passed if editing

        if (isset($_POST['author'])) {
            $selected = ($_POST['author'] == $author['id']) ? 'selected' : '';
        } else {
            $selected = (isset($selected_author) && $selected_author == $author['id']) ? 'selected' : '';
        }



        $output .= "<option value='{$author['id']}' $selected>{$author['author_name']}</option>";
    }

    $output .= "</select>";

    return  $output;
}
