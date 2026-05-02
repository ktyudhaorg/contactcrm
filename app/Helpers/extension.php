<?php

use Symfony\Component\Mime\MimeTypes;

if (!function_exists('guessExtension')) {
    function guessExtension(string $mimetype): string
    {
        $mimeType = new MimeTypes();
        $extensions = $mimeType->getExtensions($mimetype);
        return $extensions[0] ?? 'bin';
    }
}
