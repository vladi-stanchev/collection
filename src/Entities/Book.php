<?php

namespace Collection\Entities;

readonly class Book
{
    public string $title;
    public int  $author_id;
    public string $isbn;
    public int | null $id;
    public string | null $author_name;
    public string | null $author_wiki_link;
    public string | null $summary;
    public int | null $pub_year;
    public int | null $lang_id;
    public string | null $lang_name;
    public string | null $cover_img_url;
    public string | null $gr_url;
    public array | null $genres_array;
    public int $deleted;

    public function __construct(
        // Nullable int: Optional params add null to db when adding a book
        string $title,
        int $author_id,
        string $isbn,
        ?int $id = null,
        ?string $author_name = null,
        ?string $author_wiki_link = null,
        ?string $summary = null,
        ?int $pub_year = null,
        ?int $lang_id = null,
        ?string $lang_name = null,
        ?string $cover_img_url = null,
        ?string $gr_url = null,
        ?array $genres_array = null,
        ?int $deleted = 0
    ) {
        $this->title = $title;
        $this->author_id = $author_id;
        $this->isbn = $isbn;
        $this->id = $id;
        $this->author_name = $author_name;
        $this->author_wiki_link = $author_wiki_link;
        $this->summary = $summary;
        $this->pub_year = $pub_year;
        $this->lang_id = $lang_id;
        $this->lang_name = $lang_name;
        $this->cover_img_url = $cover_img_url;
        $this->gr_url = $gr_url;
        $this->genres_array = $genres_array;
        $this->deleted = $deleted;
    }
}
