<?php
// api/src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\Product;
use App\Form\Type\CurrencyType;
use App\Form\Type\ProductType;
use App\Responses\FailResponse;
use App\Services\CurrencyService;
use App\Services\ProductService;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyController extends BaseApiController
{
    private $cache;
    /**
     * ProductController constructor.
     *
     * @param SerializerInterface $serializer
     * @param                     $cache
     */
    public function __construct(SerializerInterface $serializer, $cache)
    {
        parent::__construct($serializer);
        $this->cache = $cache;
    }


    #[Route( path: '/api/v1/currency', name: 'currency_get',
        methods: [ 'GET' ] )]
    public function index(): JsonResponse
    {
        if(!$this->cache->get('currencies')) {
            /**
             * @var Currency[]|Collection $currencies
             */
            $currencies = $this->getDoctrine()->getRepository(Currency::class)->findAll();
            $this->cache->set('currencies', serialize($currencies));
        }
        return $this->successResult(unserialize($this->cache->get('currencies')));
    }

}
