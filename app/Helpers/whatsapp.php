<?php

if (!function_exists('normalizePhoneNumber')) {
    function normalizePhoneNumber(string $number): string
    {
        // Hapus semua karakter selain angka
        $number = preg_replace('/\D+/', '', $number);

        // Jika nomor diawali '0', ganti dengan '62'
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        // Jika nomor sudah diawali '8', tambahkan '62' di depan
        if (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }
}
