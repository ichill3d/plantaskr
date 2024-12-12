<?php
if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9\-]+/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return strtolower(trim($text, '-'));
    }
}
