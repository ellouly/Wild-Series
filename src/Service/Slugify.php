<?php

namespace App\Service;

class Slugify
{
    public function generate(string $slug) : string
    {
        // lowercase
        $slug = strtolower($slug);
        // transliterate
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        // remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);
        // remove punctuation and spaces
        $slug = preg_replace('\'/[^a-z0-9\s-]+/\'', '', $slug);
        return $slug;
    }
}
