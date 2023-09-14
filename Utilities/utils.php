<?php

// Ditch formatting for strings to be compared for PHPUnit testing
function clean_test_string(string $string): string
{
    return str_replace(["\n", "\r", ' '], '', $string);
}

function validate_isbn(string $isbn): bool
{
    // Remove hyphens and spaces
    $isbn = str_replace(['-', ' '], '', $isbn);

    // Check the length and format
    $length = strlen($isbn);
    if ($length == 10) {
        // Validate ISBN-10
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            if ($isbn[$i] == 'X' && $i == 9) {
                $sum += 10; // 'X' represents 10
            } elseif (is_numeric($isbn[$i])) {
                $sum += intval($isbn[$i]) * (10 - $i);
            } else {
                return false; // Invalid character found
            }
        }
        return ($sum % 11) === 0;
    } elseif ($length == 13 && (substr($isbn, 0, 3) === '978' || substr($isbn, 0, 3) === '979')) {
        // Validate ISBN-13
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $factor = ($i % 2 === 0) ? 1 : 3;
            $sum += intval($isbn[$i]) * $factor;
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        return intval($isbn[12]) === $checkDigit;
    } else {
        return false; // Invalid length or format
    }
}

function validate_url(string $url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL);
}

function validation_add_new_book_form($title, $author, $isbn, $pub_year, $cover_img_url, $lang, $gr_url): array
{

    $errors = [];

    // TITLE (required)
    if (!$title) {
        $errors['title'] = 'Book Title can\'t be empty.';
    }

    // AUTHOR (required)
    if (empty($author) || !is_numeric($author)) {
        $errors['author'] = 'Author is a required field.';
    }

    // ISBN (required)
    if (!validate_isbn($isbn)) {
        $errors['isbn'] = 'Invalid ISBN.';
    }

    // PUB YEAR
    $currentYear = date("Y");
    $minYear = 1500;
    $maxYear = $currentYear;

    if (!empty($pub_year) && (!(preg_match("/^\d{4}$/", $pub_year)) || $pub_year < $minYear || $pub_year > $maxYear)) {
        $errors['pub_year'] = 'Publication Year incorrect format.';
    }

    // COVER IMG URL
    if (!empty($cover_img_url) && !validate_url($cover_img_url)) {
        $errors['cover_img_url'] = 'Invalid URL for cover image.';
    }

    // GOODREADS URL
    if (!empty($gr_url) && !validate_url($gr_url)) {
        $errors['gr_url'] = 'Invalid Goodreads URL.';
    }

    // LANG (almost redundant validation)
    if (!empty($lang) && !is_numeric($lang)) {
        $errors['lang'] = 'Language error. Please try again.';
    }

    return $errors;
}
