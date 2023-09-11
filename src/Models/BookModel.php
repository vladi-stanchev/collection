<?php

namespace Collection\Models;

use PDO;
use Collection\Entities\Book;

class BookModel
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getAllBooks(): array | false
  {
    $query = $this->db->prepare("SELECT 
    `Books`.`id`, 
    `Books`.`title`, 
    `Books`.`author`, 
    `Books`.`isbn`, 
    `Books`.`pub_year`, 
    `Books`.`cover_img_url`, 
    `Books`.`summary`, 
    `Books`.`lang`, 
    `Books`.`gr_url`, 
    `Books`.`notes`, 
    `Books`.`deleted`,
    `Authors`.`author_name`,
    `Authors`.`wiki_link`,
    `Languages`.`lang_name`
    FROM `Books`
    INNER JOIN `Authors` ON `Books`.`author`=`Authors`.`id`
    INNER JOIN `Languages` ON `Books`.`lang`=`Languages`.`id`;");

    $query->execute();

    $data = $query->fetchAll();

    if (!$data) {
      return false;
    }

    $books = [];


    foreach ($data as $book) {

      $books[] = new Book(
        $book['id'],
        $book['title'],
        $book['author_name'],
        $book['wiki_link'],
        $book['isbn'],
        $book['pub_year'],
        $book['cover_img_url'],
        $book['summary'],
        $book['lang_name'],
        $book['gr_url'],
        $book['notes'],
        $book['deleted']

      );
    }
    return $books;
  }
}
