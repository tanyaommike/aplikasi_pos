<?php

if (!function_exists('format_rupiah')) {
    function format_rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('qris_dummy_matrix')) {
    /**
     * Matrix modul QR dummy (bukan hasil encoding asli, hanya visual).
     * Seed tetap agar pola selalu sama di setiap invoice ("QRIS Statis").
     */
    function qris_dummy_matrix(int $size = 21): array
    {
        $matrix = array_fill(0, $size, array_fill(0, $size, false));

        $drawFinder = function (array &$matrix, int $row, int $col) {
            for ($r = 0; $r < 7; $r++) {
                for ($c = 0; $c < 7; $c++) {
                    $isBorder = $r === 0 || $r === 6 || $c === 0 || $c === 6;
                    $isCore = $r >= 2 && $r <= 4 && $c >= 2 && $c <= 4;
                    $matrix[$row + $r][$col + $c] = $isBorder || $isCore;
                }
            }
        };

        $drawFinder($matrix, 0, 0);
        $drawFinder($matrix, 0, $size - 7);
        $drawFinder($matrix, $size - 7, 0);

        // Reserve area kosong (quiet zone) di sekitar tiap finder pattern
        $reserved = fn (int $r, int $c) => ($r < 8 && $c < 8)
            || ($r < 8 && $c >= $size - 8)
            || ($r >= $size - 8 && $c < 8);

        mt_srand(42);
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                if ($reserved($r, $c)) {
                    continue;
                }
                $matrix[$r][$c] = mt_rand(0, 100) < 45;
            }
        }
        mt_srand();

        return $matrix;
    }
}