<?php

namespace App\Controller;

use App\Entity\Enum\TicketType;
use App\Service\OrderService;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(public OrderService $orderService) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        try {
            $dataArray = json_decode($request->getContent(), true);
            if (!isset($dataArray['event_id'], $dataArray['event_date'], $dataArray['tickets'])) {
                throw new \InvalidArgumentException('Invalid data');
            }
            $eventId = $dataArray['event_id'];
            $eventDate = $dataArray['event_date'];
            $tickets = [];

            foreach ($dataArray['tickets'] as $ticket) {
                if (!isset($ticket['type'], $ticket['quantity'], $ticket['price'])) {
                    throw new InvalidArgumentException('Invalid ticket data');
                }
                $type = TicketType::from($ticket['type']);
                $tickets[] = [
                    'type' => $type,
                    'quantity' => (int) $ticket['quantity'],
                    'price' => (int) $ticket['price'],
                ];
            }

            // Вызов сервиса

            $this->orderService->bookTickets($eventId, $eventDate, $tickets);

            return new JsonResponse(['message' => 'order successfully approved'], 201);
        } catch (\Throwable $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }
    }
}
