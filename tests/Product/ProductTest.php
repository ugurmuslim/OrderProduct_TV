<?php

namespace App\Tests\Product;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Currency;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

class ProductTest extends ApiTestCase
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

    public function testPostProduct()
    {
        /**
         * @var User $user
         */
        $user = $this->em->getRepository(User::class)->find(1);

        $payload = [
            "currency"    => "EUR",
            "title"       => "exampleTitle",
            "description" => "exampleDesc",
            "price"       => 10.23,
        ];

        $payloadString = http_build_query($payload);
        $signature = hash_hmac('sha256', $payloadString, $user->getSecretKey());
        $payload['signature'] = $signature;

        $response = static::createClient()->request('POST', 'http://127.0.0.1/api/v1/product',
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
        $this->assertSame('10.23', $responseData['data']['price']);
        $this->assertSame('exampleTitle', $responseData['data']['title']);
    }

    public function testUpdateProduct(): void
    {
        /**
         * @var User $user
         */
        $user = $this->em->getRepository(User::class)->find(1);

        $payload = [
            "currency"    => "EUR",
            "status"      => false,
            "title"       => "UpdatedTitle1",
            "description" => "UpdatedDescription1",
            "price"       => 200,
        ];

        $payloadString = http_build_query($payload);
        $signature = hash_hmac('sha256', $payloadString, $user->getSecretKey());
        $payload['signature'] = $signature;

        $response = static::createClient()->request('PUT', 'http://127.0.0.1/api/v1/product/14',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'API-KEY'      => $user->getApiKey(),
                ],
                "body"    => json_encode($payload),
            ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame('success', $responseData['status']);
        $this->assertSame('200', $responseData['data']['price']);
        $this->assertSame('UpdatedTitle1', $responseData['data']['title']);
        $this->assertSame(false, $responseData['data']['status']);
    }


    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', 'http://127.0.0.1/api/v1/product');
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame('success', $responseData['status']);
    }


}
