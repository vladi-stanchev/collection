<?php
// DISPLAY HTML CHECKBOX OPTIONS FOR NEW BOOK FORM

function display_genres_checkbox(array $genres, array $selected_genres = null): string
{
    $output = "<label>Genres</label><br>
    <div class='checkbox-options-container flex-center'>";

    if (!$genres) {
        throw new Exception("No genres found.");
    }

    foreach ($genres as $genre) {
        $id = $genre['id']; // Persistent checked btns when serving errors
        // $isChecked = (isset($_POST["genres"][$id])) ? 'checked' : '';

        // Check if selections passed when editing a book
        if (isset($_POST["genres"][$id])) {
            $isChecked = (isset($_POST["genres"][$id])) ? 'checked' : '';
        } else {
            $isChecked = (isset($selected_genres) && $selected_genres['genre_id'] === $id) ? 'checked' : '';
        }

        $output .= "        
<input type='checkbox' id='$genre[genre_name]' name='genres[$genre[id]]' value=$genre[id] $isChecked>
<label for='$genre[genre_name]' class='checkbox-button' tabindex='0'>$genre[genre_name]</label><br>
";
    }

    $output .= "</div>";

    return  $output;
}
