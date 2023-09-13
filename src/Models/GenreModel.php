<?php

namespace Collection\Models;

use PDO;

class GenreModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllGenres()
    {
        $query = $this->db->prepare("SELECT
        `Genres`.`id`,
        `Genres`.`genre_name`
        FROM `Genres`
        ");

        $query->execute();

        $genres = $query->fetchAll();

        // Sort $genres array by genre_name alphabetically
        usort($genres, function ($a, $b) {
            return strcmp($a['genre_name'], $b['genre_name']);
        });

        return $genres;
    }
}
