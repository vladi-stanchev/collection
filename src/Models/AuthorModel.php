<?php

namespace Collection\Models;

use PDO;

class AuthorModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllAuthors()
    {
        $query = $this->db->prepare("SELECT
        `Authors`.`id`,
        `Authors`.`author_name`,
        `Authors`.`wiki_link`
        FROM `Authors`
        ");

        $query->execute();

        $authorsArray = $query->fetchAll();

        // Sort alphabetically
        usort($authorsArray, function ($a, $b) {
            return strcmp($a['author_name'], $b['author_name']);
        });

        return $authorsArray;
    }
}
