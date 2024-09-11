<?php

namespace App\Base;

class JsParser
{
    public static function parse($content)
    {
        // Ensure $content is a valid string
        if (!is_string($content) || empty($content)) {
            return $content; // If $content is null or not a string, just return it
        }

        $pattern = '/<\?reactEmbed\s+([\s\S]*?)\s*\?>/';

        // Callback function to evaluate the PHP code found in the pattern
        $content = preg_replace_callback($pattern, function ($matches) {
            ob_start();
            eval($matches[1]); // Execute the embedded code
            return ob_get_clean(); // Return the output of the code
        }, $content);

        return $content;
    }
}
