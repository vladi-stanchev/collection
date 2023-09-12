<?php
function display_books_list(array $books): string
{
  $output = '';

  if (!$books) {
    throw new Error("No results found.");
  }


  foreach ($books as $book) {
    $output .= "
        <h3>$book->title</h3>
        <a href='$book->wiki_link' class='author'>$book->author_name</a> <br><br>
        <img src='$book->cover_img_url' alt='$book->title cover' class='book-cover'>
        <p>$book->summary</p>
        <p><span class='bold'>Published: </span>$book->pub_year</p>
        <p><span class='bold'>ISBN: </span><a href='$book->gr_url'>$book->isbn</a></p>
        <p><span class='bold'>Language: </span>$book->lang_name</p>
        <hr>
        ";
  }


  return  $output;
}
