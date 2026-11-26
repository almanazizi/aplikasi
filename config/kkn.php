<?php

return [
    'ukuran_baju' => ['S', 'M', 'L', 'XL', 'XXL', '3XL'],

    'alfabet_kode' => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',

    'masa_berlaku_aktivasi_hari' => 7,

    'masa_berlaku_pemulihan_menit' => 30,

    'ukuran_maks_pdf_kb' => 5120,

    'per_halaman' => 20,

    'ketentuan_default' => [
        'reguler' => [
            'min_anggota' => 9,
            'maks_anggota' => 12,
            'min_prodi' => 5,
            'maks_laki_laki' => 2,
        ],
        'nonreguler' => [
            'min_anggota' => 9,
            'maks_anggota' => 12,
            'min_prodi' => null,
            'maks_laki_laki' => null,
        ],
    ],
];
