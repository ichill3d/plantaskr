<?php

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9\-]+/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return strtolower(trim($text, '-'));
    }
}

if (!function_exists('get_contrast_color')) {
    /**
     * Determine the contrasting text color (black or white) based on a background color.
     *
     * @param string $hexColor The background color in hex format (e.g., #FFFFFF).
     * @return string 'black' or 'white'
     */
    function get_contrast_color(?string $hexColor = '#000000'): string
    {
        // Remove the '#' if present
        $hexColor = ltrim($hexColor, '#');

        // Ensure the input is a valid hex color
        if (strlen($hexColor) !== 6 || !ctype_xdigit($hexColor)) {
            return 'black'; // Fallback to 'black' if invalid
        }

        // Convert hex to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Return 'white' for dark colors, 'black' for light colors
        return $luminance > 0.65 ? '#000000' : '#ffffff';
    }
}

if (!function_exists('toObject')) {
    /**
     * Convert an array to an object.
     *
     * @param mixed $data
     * @return object
     */
    function toObject($data)
    {
        if (is_array($data)) {
            return json_decode(json_encode($data));
        }
        return (object) $data;
    }
}
