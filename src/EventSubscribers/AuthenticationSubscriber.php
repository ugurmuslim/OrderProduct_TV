<?php

// src/EventSubscriber/TokenSubscriber.php
namespace App\EventSubscribers;

use App\Entity\User;
use App\Interfaces\AuthenticationController;
use App\Services\AuthenticationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var AuthenticationService
     */
    private AuthenticationService $authenticationService;

    public function __construct(EntityManagerInterface $em, AuthenticationService $authenticationService)
    {
        $this->em = $em;
        $this->authenticationService = $authenticationService;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        $method = null;

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $method = $controller[1];
            $controller = $controller[0];
        }

        if (( $controller instanceof AuthenticationController && ($method == 'store' || $method == 'update') )) {

            $request = $event->getRequest();

            $this->validate($request);
            /**
             * @var User $user
             */
            $user = $this->em->getRepository(User::class)->findOneBy([ 'apiKey' => $request->headers->get('api-key') ]);

            if (!$user) {
                $this->fail("Invalid Apikey");
            }

            if (!$this->authenticationService->auth($request, $user->getSecretKey())) {
                return $this->fail("Not a valid Token");
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    private function fail($message = ""): array
    {
        echo json_encode([
            'status' => 'failure',
            'data'   => $message,
        ]);
        exit();
    }

    private function validate(Request $request)
    {

        if (!$request->headers->get('api-key')) {
            return $this->fail("ApiKey is a required");
        }

        if (!$request->request->get('signature')) {
            return $this->fail("Signature is a required");
        }
    }

}