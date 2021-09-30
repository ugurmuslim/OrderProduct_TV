<?php


namespace App\Services;

use App\Controller\BaseApiController;
use App\Entity\Currency;
use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Responses\FailResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductService extends BaseApiController
{

    /**
     * @param Request $request
     *
     * @return Product
     */
    public function update(Request $request): Product|FailResponse {

        $productId = $request->get('id');
        /**
         * @var Product $product
         */
        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);

        if (!$product) {
            return new FailResponse("There is no Product");
        }

        $currencyId = $request->get('currency');

        /**
         * @var Currency $currency
         */
        $currency = $this->getDoctrine()->getRepository(Currency::class)->find($currencyId);

        if (!$currency) {
            return new FailResponse("Currency not Found");
        }

        $form = $this->buildForm(ProductType::class, $product, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return new FailResponse($form);
        }

        $product->setStatus($request->get('status'));
        $product->setTitle($request->get('title'));
        $product->setPrice($request->get('price'));
        $product->setCurrency($currency);
        $product->setDescription($request->get('description'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }


}