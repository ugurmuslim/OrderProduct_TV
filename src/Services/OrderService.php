<?php


namespace App\Services;

use App\Controller\BaseApiController;
use App\Entity\Currency;
use App\Entity\OrderDetail;
use App\Entity\OrderHeader;
use App\Entity\Product;
use App\Entity\User;
use App\Form\Type\ProductType;
use App\Responses\FailResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderService extends BaseApiController
{
    protected function productsLegit($productsArray, array $products): bool
    {
        return count($productsArray) == count($products);
    }

    public function insertOrder(Request $request): OrderHeader|FailResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $products = $request->request->all('products');

        if (!is_array($products)) {
            return new FailResponse("Products must be array");
        }

        if(!count($products) > 0) {
            return new FailResponse("You must enter products");
        }

        $productsCollection = $this->getDoctrine()->getRepository(Product::class)->findWhereIn($products);

        if (!$this->productsLegit($productsCollection, $products)) {
            return new FailResponse("There are invalid Product you entered");
        }

        /**
         * @var User $user
         */
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([ 'apiKey' => $request->headers->get('api-key') ]);

        if(!$user) {
            return new FailResponse("User is invalid");
        }

        $orderHeader = $this->initiateOrderHeader($user);

        $totalPrice = 0;
        foreach ($productsCollection as $product) {
            $totalPrice += $product->getPrice();
            $orderDetail = new OrderDetail();
            $orderDetail->setPrice($product->getPrice());
            $orderDetail->setProduct($product);
            $orderHeader->addOrderDetails($orderDetail);
            $entityManager->persist($orderDetail);
        }

        $orderHeader->setTotalPrice($totalPrice);
        $entityManager->persist($orderHeader);
        $entityManager->flush();
        return $orderHeader;
    }

    protected function initiateOrderHeader(User $user): OrderHeader
    {
        $orderHeader = new OrderHeader();
        $orderHeader->setUser($user);
        $orderHeader->setStatus("SUCCESS");
        return $orderHeader;
    }
}