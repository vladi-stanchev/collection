<?php

namespace Collection\Models;

use PDO;

class LanguageModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllLanguages()
    {
        $query = $this->db->prepare("SELECT
        `Languages`.`id`,
        `Languages`.`lang_name`
        FROM `Languages`
        ");

        $query->execute();

        $languagesArray = $query->fetchAll();

        // Sort alphabetically
        usort($languagesArray, function ($a, $b) {
            return strcmp($a['lang_name'], $b['lang_name']);
        });

        return $languagesArray;
    }
}
