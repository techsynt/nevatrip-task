<?php

namespace App\Service\SomeApi;

class ApiClient
{
    public function book(array $payload): array
    {
        return rand(0, 1)
            ? ['message' => 'order successfully booked']
            : ['error' => 'barcode already exists'];
    }

    public function approve(array $barcodes): array
    {
        if (mt_rand(0, 1)) {
            return ['message' => 'order successfully aproved'];
        }
        $errors = [
            ['error' => 'event cancelled'],
            ['error' => 'no tickets'],
            ['error' => 'no seats'],
            ['error' => 'fan removed'],
        ];

        return $errors[array_rand($errors)];
    }
}
