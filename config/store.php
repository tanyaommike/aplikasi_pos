<?php

return [
    'name' => env('STORE_NAME', 'POS Kasir'),
    'phone' => env('STORE_PHONE', '0812-3456-7890'),
    'address' => env('STORE_ADDRESS', 'Jl. Contoh No. 123, Makassar'),

    'bank' => [
        'name' => env('STORE_BANK_NAME', 'BCA'),
        'account_number' => env('STORE_BANK_ACCOUNT_NUMBER', '1234567890'),
        'account_holder' => env('STORE_BANK_ACCOUNT_HOLDER', 'POS Kasir'),
    ],
];
