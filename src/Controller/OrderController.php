<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(public OrderService $orderService) {}

    #[Route('/', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $dataArray = json_decode($request->getContent(), true);
        $this->orderService->getDataArray($dataArray);
    }
}
