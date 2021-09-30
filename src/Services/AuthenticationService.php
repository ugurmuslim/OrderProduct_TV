<?php


namespace App\Services;

use App\Controller\BaseApiController;
use App\Entity\Currency;
use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Responses\FailResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationService extends BaseApiController
{

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function payload(Request $request)
    {
        $payload = $request->request->all();
        $signature = $payload['signature'];
        unset($payload['signature']);

        return  http_build_query($payload);

    }

    public function auth(Request $request, $secretKey) : bool
    {

        $payloadString =  $this->payload($request);
        $signature = hash_hmac('sha256',$payloadString,  $secretKey);
        if($signature !== $request->get('signature')) {
            return false;
        }
        return true;
    }
}