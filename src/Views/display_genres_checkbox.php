<?php
// DISPLAY HTML CHECKBOX OPTIONS FOR NEW BOOK FORM

function display_genres_checkbox(array $genres): string
{
    $output = "<label>Genres</label><br>
    <div class='checkbox-options-container flex-center'>";

    if (!$genres) {
        throw new Exception("No genres found.");
    }

    foreach ($genres as $genre) {
        $id = $genre['id']; // Persistent checked btns when serving errors
        $isChecked = (isset($_POST["genres"][$id])) ? 'checked' : '';

        $output .= "        
<input type='checkbox' id='$genre[genre_name]' name='genres[$genre[id]]' value=$genre[id] $isChecked>
<label for='$genre[genre_name]' class='checkbox-button' tabindex='0'>$genre[genre_name]</label><br>
";
    }

    $output .= "</div>";

    return  $output;
}
