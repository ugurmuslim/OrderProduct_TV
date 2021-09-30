<?php

namespace App\Tests\Order;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OrderTest extends ApiTestCase
{
    private EntityManagerInterface $em;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    public function testPostOrder(): void
    {
        /**
         * @var User $user
         */
        $user = $this->em->getRepository(User::class)->find(1);

        $payload = [
            'products' => [ 1, 2, 3 ]
        ];
        /**
         *
         * @var Product[]|Collection $products
         *
         */
        $products = $this->em->getRepository(Product::class)->findWhereIn($payload['products']);
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product->getPrice();
        }


        $payloadString = http_build_query($payload);
        $signature = hash_hmac('sha256',$payloadString,  $user->getSecretKey());
        $payload['signature'] = $signature;

        $response = static::createClient()->request('POST', 'http://127.0.0.1/api/v1/order',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'API-KEY'      => $user->getApiKey(),
                ],
                "body"    => json_encode($payload),
            ]);

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame('success', $responseData['status']);
        $this->assertSame((string)$totalPrice, $responseData['data']['totalPrice']);
        $this->assertSame(1, $responseData['data']['orderDetails'][0]['product']['id']);
    }
}
