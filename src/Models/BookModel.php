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
        `B`.`id` AS `book_id`,
        `B`.`title` AS `book_title`,
        `B`.`isbn` AS `book_isbn`,
        `B`.`pub_year` AS `book_pub_year`,
        `B`.`cover_img_url` AS `book_cover_img_url`,
        `B`.`summary` AS `book_summary`,
        `B`.`gr_url` AS `book_gr_url`,
        `B`.`deleted` AS `book_deleted`,
        `A`.`id` AS `author_id`,
        `A`.`author_name` AS `author_name`,
        `A`.`wiki_link` AS `author_wiki_link`,
        `L`.`id` AS `lang_id`,
        `L`.`lang_name` AS `lang_name`
    FROM `Books` AS `B`
    LEFT JOIN `Authors` AS `A` ON `B`.`author_id` = `A`.`id`
    LEFT JOIN `Languages` AS `L` ON `B`.`lang` = `L`.`id`;
    ");

        $query->execute();
        $data = $query->fetchAll();

        if (!$data) {
            return false;
        }

        $books = [];

        foreach ($data as $book) {
            // Get Genre IDs for current book (from ref table)
            $query = $this->db->prepare("SELECT
            `BG`.`book_id`,
            `G`.`id` AS `genre_id`,
            `G`.`genre_name` AS `genre_name`
        FROM `BooksGenres` AS `BG`
        INNER JOIN `Genres` AS `G` ON `BG`.`genre_id` = `G`.`id`
        WHERE `BG`.`book_id` = :book_id;
        ");

            $query->execute([
                ':book_id' => $book['book_id'],
            ]);
            $genres_array = $query->fetchAll();

            $books[] = new Book(
                $book['book_title'] ?? 'Missing Title',
                $book['author_id'],
                $book['book_isbn'] ?? '',
                $book['book_id'],
                $book['author_name'] ?? 'Unknown Author',
                $book['author_wiki_link'] ?? null,
                $book['book_summary'] ?? null,
                $book['book_pub_year'] ?? null,
                $book['lang_id'],
                $book['lang_name'] ?? null,
                $book['book_cover_img_url'] ?? null,
                $book['book_gr_url'] ?? null,
                $genres_array ?? null,
                $book['book_deleted'] ?? 0
            );
        }
        return $books;
    }

    public function getBookDetails(int $id): Book | false
    {
        $query = $this->db->prepare("SELECT
            `B`.`id` AS `book_id`,
            `B`.`title` AS `book_title`,
            `B`.`isbn` AS `book_isbn`,
            `B`.`pub_year` AS `book_pub_year`,
            `B`.`cover_img_url` AS `book_cover_img_url`,
            `B`.`summary` AS `book_summary`,
            `B`.`gr_url` AS `book_gr_url`,
            `B`.`deleted` AS `book_deleted`,
            `A`.`id` AS `author_id`,
            `A`.`author_name` AS `author_name`,
            `A`.`wiki_link` AS `author_wiki_link`,
            `L`.`id` AS `lang_id`,
            `L`.`lang_name` AS `lang_name`
        FROM `Books` AS `B`
        LEFT JOIN `Authors` AS `A` ON `B`.`author_id` = `A`.`id`
        LEFT JOIN `Languages` AS `L` ON `B`.`lang` = `L`.`id`
        WHERE `B`.`id` = :book_id
        LIMIT 1;");

        $query->execute([':book_id' => $id]);
        $data = $query->fetch();

        if (!$data) {
            return false;
        }

        // Get Genre IDs for current book (from ref table)
        $query = $this->db->prepare("SELECT
            `BG`.`book_id`,
            `G`.`id` AS `genre_id`,
            `G`.`genre_name` AS `genre_name`
        FROM `BooksGenres` AS `BG`
        INNER JOIN `Genres` AS `G` ON `BG`.`genre_id` = `G`.`id`
        WHERE `BG`.`book_id` = :book_id;");

        $query->execute([':book_id' => $id]);
        $genres_array = $query->fetch();
        // $genres_array = $query->fetchAll();          /// backup

        // Create the Book object outside of the loop
        // $book = null;

        // foreach ($data as $bookData) {
        $book = new Book(
            $data['book_title'] ?? 'Missing Title',
            $data['author_id'],
            $data['book_isbn'] ?? '',
            $data['book_id'],
            $data['author_name'] ?? 'Unknown Author',
            $data['author_wiki_link'] ?? null,
            $data['book_summary'] ?? null,
            $data['book_pub_year'] ?? null,
            $data['lang_id'],
            $data['lang_name'] ?? null,
            $data['book_cover_img_url'] ?? null,
            $data['book_gr_url'] ?? null,
            ($genres_array !== false) ? $genres_array : null, // Check if $genres_array is not false
            $data['book_deleted'] ?? 0
        );
        // }

        return $book;
    }

    public function addNewBook(Book $book): bool
    {

        try {
            $this->db->beginTransaction(); // https://www.phptutorial.net/php-pdo/php-pdo-transaction/
            $query1 = $this->db->prepare("INSERT INTO
            `Books`(`title`, `author_id`, `isbn`, `pub_year`, `cover_img_url`, `summary`, `lang`, `gr_url`)
            VALUES (:title, :author_id, :isbn, :pub_year, :cover_img_url, :summary, :lang, :gr_url);");

            $query1->execute([
                ':title' => $book->title,
                ':author_id' => $book->author_id,
                ':isbn' => $book->isbn,
                ':pub_year' => $book->pub_year,
                ':cover_img_url' => $book->cover_img_url,
                ':summary' => $book->summary,
                ':lang' => $book->lang_id,
                ':gr_url' => $book->gr_url
            ]);

            // Add genre references to BooksGenres table
            $lastInsertedBookId = $this->db->lastInsertId(); // Store the id of last book to associate genres

            if ($book->genres_array !== null) { // Check if array of genres provided by user
                $query2 = $this->db->prepare("INSERT INTO
            `BooksGenres`(`book_id`, `genre_id`)
            VALUES (:book_id, :genre_id);");
                // Loop through genres array, insert each genre_id as new row /w book_id in BooksGenres table
                foreach ($book->genres_array as $genre) {
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

    public function editBook(Book $book): bool
    {
        try {
            $this->db->beginTransaction();

            // Update the existing book record
            $query = $this->db->prepare("UPDATE Books
            SET
                title = :title,
                author_id = :author_id,
                isbn = :isbn,
                pub_year = :pub_year,
                cover_img_url = :cover_img_url,
                summary = :summary,
                lang = :lang,
                gr_url = :gr_url
            WHERE id = :book_id");

            $query->execute([
                ':title' => $book->title,
                ':author_id' => $book->author_id,
                ':isbn' => $book->isbn,
                ':pub_year' => $book->pub_year,
                ':cover_img_url' => $book->cover_img_url,
                ':summary' => $book->summary,
                ':lang' => $book->lang_id,
                ':gr_url' => $book->gr_url,
                ':book_id' => $book->id, // Use the existing book's ID for the update
            ]);

            // Delete existing genre associations for this book
            $deleteQuery = $this->db->prepare("DELETE FROM BooksGenres WHERE book_id = :book_id");
            $deleteQuery->execute([
                ':book_id' => $book->id, // Use the existing book's ID
            ]);

            // Insert new genre associations for this book
            if ($book->genres_array !== null) {
                $insertQuery = $this->db->prepare("INSERT INTO BooksGenres (book_id, genre_id) VALUES (:book_id, :genre_id)");
                foreach ($book->genres_array as $genre) {
                    $insertQuery->execute([
                        ':book_id' => $book->id, // Use the existing book's ID
                        ':genre_id' => $genre,
                    ]);
                }
            }

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Failed to edit the book: " . $e->getMessage());
        }
        return true;
    }



    public function deleteBook(int $id): bool
    {
        try {
            $query = $this->db->prepare("UPDATE `Books` SET deleted = 1 WHERE id = :book_id LIMIT 1");
            $query->bindParam(':book_id', $id);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to delete: " . $e->getMessage());
        }
    }
}
