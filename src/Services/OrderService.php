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
    public function productsLegit($productsArray, array $products): bool
    {
        return count($productsArray) == count($products);
    }

    public function insertOrder(User $user, $productsCollection): OrderHeader
    {

        $entityManager = $this->getDoctrine()->getManager();

        /**
         * @var User $user
         */
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);

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