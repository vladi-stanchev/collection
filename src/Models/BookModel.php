<?php

namespace Collection\Models;

use PDO;
use Collection\Entities\Book;
use Exception;
use PDOException;

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
    `Books`.`author_id`, 
    `Books`.`isbn`, 
    `Books`.`pub_year`, 
    `Books`.`cover_img_url`, 
    `Books`.`summary`, 
    `Books`.`lang`, 
    `Books`.`gr_url`, 
    `Books`.`deleted`,
    `Authors`.`author_name`,
    `Authors`.`wiki_link`,
    `Languages`.`lang_name`
    FROM `Books`
    INNER JOIN `Authors` ON `Books`.`author_id`=`Authors`.`id`
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
                $book['deleted']

            );
        }
        return $books;
    }

    public function addNewBook(
        string $title,
        int $author_id,
        string $isbn,
        // Optional params
        ?int $pub_year = null,
        ?string $cover_img_url = null,
        ?string $summary = null,
        ?int $lang = null,
        ?string $gr_url = null,
        ?array $genres = null
    ): bool {

        try {
            $this->db->beginTransaction(); // https://www.phptutorial.net/php-pdo/php-pdo-transaction/
            // Add to Books table 
            $query1 = $this->db->prepare("INSERT INTO
            `Books`(`title`, `author_id`, `isbn`, `pub_year`, `cover_img_url`, `summary`, `lang`, `gr_url`)
            VALUES (:title, :author_id, :isbn, :pub_year, :cover_img_url, :summary, :lang, :gr_url);");

            $query1->execute([
                ':title' => $title,
                ':author_id' => $author_id,
                ':isbn' => $isbn,
                ':pub_year' => $pub_year,
                ':cover_img_url' => $cover_img_url,
                ':summary' => $summary,
                ':lang' => $lang,
                ':gr_url' => $gr_url
            ]);

            // Add genre references to BooksGenres table
            $lastInsertedBookId = $this->db->lastInsertId(); // Store the id of last book to associate genres

            if ($genres !== null) { // Check if array of genres provided by user
                $query2 = $this->db->prepare("INSERT INTO
            `BooksGenres`(`book_id`, `genre_id`)
            VALUES (:book_id, :genre_id);");
                // Loop through genres array, insert each genre_id as new row /w book_id in BooksGenres table
                foreach ($genres as $genre) {
                    $query2->bindParam(':book_id', $lastInsertedBookId);
                    $query2->bindParam(':genre_id', $genre);
                    $query2->execute();
                }
            }
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Failed to add a new book: " . $e->getMessage());
        }
        return true;
    }
}
