<?php
function display_genres_checkbox(array $genres): string
{
    $output = "<label>Genres</label><br>
    <div class='checkbox-options-container flex-center'>";

    if (!$genres) {
        throw new Exception("No genres found.");
    }

    foreach ($genres as $genre) {
        $output .= "        
<input type='checkbox' id='$genre[genre_name]' name='genres[$genre[id]]' value=$genre[id]>
<label for='$genre[genre_name]' class='checkbox-button' tabindex='0'>$genre[genre_name]</label><br>
        ";
    }

    $output .= "</div>";

    return  $output;
}
