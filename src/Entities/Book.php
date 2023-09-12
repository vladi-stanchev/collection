<?php

namespace Collection\Entities;

readonly class Book
{
    public int $id;
    public string $title;
    public string $author_name;
    public string $wiki_link;
    public string $isbn;
    public int $pub_year;
    public string $cover_img_url;
    public string $summary;
    public string $lang_name;
    public string $gr_url;
    public string $notes;
    public int $deleted;

    public function __construct(
        int $id,
        string $title,
        string $author_name,
        string $wiki_link,
        string $isbn,
        int $pub_year,
        string $cover_img_url,
        string $summary,
        string $lang_name,
        string $gr_url,
        string $notes,
        int $deleted
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author_name = $author_name;
        $this->wiki_link = $wiki_link;
        $this->isbn = $isbn;
        $this->pub_year = $pub_year;
        $this->cover_img_url = $cover_img_url;
        $this->summary = $summary;
        $this->lang_name = $lang_name;
        $this->gr_url = $gr_url;
        $this->notes = $notes;
        $this->deleted = $deleted;
    }
}
