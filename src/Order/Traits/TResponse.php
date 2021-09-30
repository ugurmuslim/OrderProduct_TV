<?php


namespace App\Order\Traits;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @property SerializerInterface serializer
 */
trait TResponse
{
    public SerializerInterface $serializer;

    /**
     * TResponse constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    public function failureResult($message = ""): JsonResponse
    {
        $data = [
            'status' => 'failure',
            'data'   => $message instanceof JsonResponse ? json_decode($message->getContent('data'), true) : $message,
        ];

        $data = $this->serializer->serialize($data, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_BAD_REQUEST, [], true);
    }

    public function successResult($message = "", $groups = []): JsonResponse
    {
        $data = [
            'status' => 'success',
            'data'   => $message instanceof JsonResponse ? json_decode($message->getContent('data'), true) : $message,
        ];

        $data = $this->serializer->serialize($data, JsonEncoder::FORMAT, [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'groups'=> $groups
        ]);
        return new JsonResponse($data, Response::HTTP_OK, [], true);

    }
}
