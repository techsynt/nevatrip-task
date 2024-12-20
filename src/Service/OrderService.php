<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Service\SomeApi\ApiClient;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private array $barcodes;

    public function __construct(public readonly ApiClient $apiClient, private EntityManagerInterface $em) {}

    public function bookTickets(int $eventId, $eventDate, array $tickets): void
    {
        /*
         * Создаем сначала объект "ордер", для привязки к тикетам.
         * Проходимся через массив тикетов c помощью foreach, так как для каждого тикета нужен баркод,
         * то внутри еще один цикл for, в нем вызывается метод book для каждого тикета до тех пор,
         * пока он не отработает без возврата ошибки - для этого исп. do - while конструкция
         */
        $order = new Order();
        $order->setEventId($eventId);
        $order->setEventDate($eventDate);
        foreach ($tickets as $ticket) {
            for ($i = 0; $i < $ticket['quantity']; ++$i) {
                do {
                    $barcode = self::generateBarcode();
                    // Имитация вызова апи book до тех пор пока не пройдет без ошибок
                    $bookResponse = $this->apiClient->book(
                        [
                            'event_id' => $eventId,
                            'event_date' => $eventDate,
                            'ticket_price' => $ticket['price'],
                            'ticket_type' => $ticket['type'],
                            'barcode' => $barcode,
                        ]
                    );
                } while (isset($bookResponse['error']));
                // так как мне после брони нужно имитировать approve с передачей баркодов заказа, собираю их в массив
                $this->barcodes[] = $barcode;

                $ticketObj = new Ticket();
                $ticketObj->setOrdId($order);
                $ticketObj->setBarcode($barcode);
                $ticketObj->setPrice($ticket['price']);
                $ticketObj->setType($ticket['type']);

                $this->em->persist($ticketObj);

                $order->addTicket($ticketObj);
            }
        }

        // Имитация вызова апи approve
        $approveResponse = $this->apiClient->approve([$this->barcodes]);
        if (isset($approveResponse['error'])) {
            throw new \RuntimeException('Failed to approve ticket: '.$approveResponse['error']);
        }
        // калькуляция суммы реализована в сущности
        $order->calculateTotalPrice();
        $this->em->persist($order);
        $this->em->flush();

        // сбрасываю массив баркодов
        $this->barcodes = [];
    }

    private static function generateBarcode(): int
    {
        return substr(crc32(gethostname()), 0, 2).mt_rand(100000, 999999);
    }
}
