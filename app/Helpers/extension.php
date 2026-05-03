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

if (!function_exists('resolveContentType')) {
    function resolveContentType(string $mimetype): string
    {
        return match (true) {
            $mimetype === 'image/webp'           => 'sticker',
            str_starts_with($mimetype, 'image/') => 'image',
            str_starts_with($mimetype, 'video/') => 'video',
            $mimetype === 'audio/ogg'            => 'ptt',
            str_starts_with($mimetype, 'audio/') => 'audio',
            default                              => 'document',
        };
    }
}
