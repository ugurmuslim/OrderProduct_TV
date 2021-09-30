<?php
// api/src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Interfaces\AuthenticationController;
use App\Responses\FailResponse;
use App\Services\ProductService;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class ProductController extends BaseApiController implements AuthenticationController
{
    /**
     * @var ProductService
     */
    private ProductService $productService;

    /**
     * ProductController constructor.
     *
     * @param SerializerInterface $serializer
     * @param ProductService      $productService
     */
    public function __construct(SerializerInterface $serializer, ProductService $productService)
    {
        parent::__construct($serializer);
        $this->productService = $productService;
    }


    #[Route( path: '/api/v1/product', name: 'product_get',
        methods: [ 'GET' ] )]
    public function index(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        /**
         * @var Product[]|Collection $products
         */
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->successResult($products);

    }

    #[Route( '/api/v1/product', methods: [ 'POST' ] )]
    public function store(Request $request): JsonResponse
    {
        $form = $this->buildForm(ProductType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->failureResult($this->json($form));
        }

        $product = $form->getData();
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();

        return $this->successResult($product);
    }

    #[Route( '/api/v1/product/{id}', requirements: [ 'id' => '\d+' ], methods: [ 'PUT' ] )]
    public function update(Request $request): JsonResponse
    {
        if(!$request->get('title') || !$request->get('currency') || !$request->get('price')) {
            return $this->failureResult("You must enter required Fields");
        }

        $response = $this->productService->update($request);

        if($response instanceof FailResponse) {
            return $this->failureResult($response->message);
        }

        return $this->successResult($response);
    }
}
