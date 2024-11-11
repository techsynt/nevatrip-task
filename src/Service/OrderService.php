<?php

namespace App\Service;

use App\Service\SomeApi\ApiClient;

class OrderService
{
    public function __construct(public readonly ApiClient $apiClient) {}

    public function getData(array $dataArray)
    {
        $dataArray['barcode'] = self::generateBarcode();
        $bookResponse = $this->apiClient->book($dataArray);
        if (isset($bookResponse['error'])) {
            $dataArray['barcode'] = self::generateBarcode();

            // тут вызов рекурсии
            return $this->getData($dataArray);
        }
        $approveResponse = $this->apiClient->approve($dataArray['barcode']);
        if (isset($approveResponse['error'])) {
            return $approveResponse['error'];
        }


        //логика сохранения


    }

    private static function generateBarcode(): int
    {
        return substr(crc32(gethostname()), 0, 4).mt_rand(1000, 9999);
    }
}
