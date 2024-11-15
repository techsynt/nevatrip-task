<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Service\SomeApi\ApiClient;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(public readonly ApiClient $apiClient, private EntityManagerInterface $em) {}

    public function bookTickets(int $eventId, $eventDate, array $tickets): void
    {
        $order = new Order();
        $order->setEventId($eventId);
        $order->setEventDate($eventDate);
        foreach ($tickets as $ticket) {
            for ($i = 0; $i < $ticket['quantity']; ++$i) {
                do {
                    $barcode = self::generateBarcode();
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

                if (isset($approveResponse['error'])) {
                    throw new \RuntimeException('Failed to approve ticket: '.$approveResponse['error']);
                }

                $ticketObj = new Ticket();
                $ticketObj->setOrdId($order);
                $ticketObj->setBarcode($barcode);
                $ticketObj->setPrice($ticket['price']);
                $ticketObj->setType($ticket['type']);

                $this->em->persist($ticketObj);

                $order->addTicket($ticketObj);
            }
        }
        $order->calculateTotalPrice();
        $this->em->persist($order);
        $this->em->flush();
    }

    private static function generateBarcode(): int
    {
        return substr(crc32(gethostname()), 0, 4).mt_rand(1000, 9999);
    }
}
