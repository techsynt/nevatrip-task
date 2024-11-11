<?php

namespace App\Service;

class OrderService
{
    private static int $barcode;

    /**
     * @param array $payload
     * @return array
     */
    public function getDataArray(array $dataArray): array
    {
        self::$barcode = self::generateBarcode();
        $dataArray['barcode'] = self::$barcode;

        return $dataArray;
    }

    /**
     * @return int
     */
    private static function generateBarcode(): int
    {
        return substr(crc32(gethostname()), 0, 4).mt_rand(1000, 9999);
    }
}
