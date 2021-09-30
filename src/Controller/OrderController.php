<?php

namespace App\Controller;

use App\Entity\OrderHeader;
use App\Entity\Product;
use App\Entity\User;
use App\Interfaces\AuthenticationController;
use App\Responses\FailResponse;
use App\Services\OrderService;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class OrderController extends BaseApiController implements AuthenticationController
{
    /**
     * @var OrderService
     */
    private OrderService $orderService;

    /**
     * ProductController constructor.
     *
     * @param SerializerInterface $serializer
     * @param OrderService        $orderService
     */
    public function __construct(SerializerInterface $serializer, OrderService $orderService)
    {
        parent::__construct($serializer);
        $this->orderService = $orderService;
    }


    #[Route( path: '/api/v1/order', name: 'order_get',
        methods: [ 'GET' ] )]
    public function index(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        /**
         * @var OrderHeader[]|Collection $orders
         */
        $orders = $this->getDoctrine()->getRepository(OrderHeader::class)->findWithDetails($request);

        return $this->successResult($orders, [ 'order', 'order_detail', 'products', 'user' ]);

    }

    #[Route( '/api/v1/order', methods: [ 'POST' ] ),
    ]
    public function store(Request $request): JsonResponse
    {
        $response = $this->orderService->insertOrder($request);

        if ($response instanceof FailResponse) {
            return $this->failureResult($response->message);
        }

        return $this->successResult($response, [ 'order', 'order_detail', 'products', 'user' ]);
    }

    #[Route( '/api/v1/order/{id}', requirements: [ 'id' => '\d+' ], methods: [ 'GET' ] )]
    public function show(int $id): JsonResponse
    {
        $order = $this->getDoctrine()->getRepository(OrderHeader::class)->find($id);
        return $this->successResult($order, [ 'user', 'order', 'order_detail', 'products' ]);
    }

}
