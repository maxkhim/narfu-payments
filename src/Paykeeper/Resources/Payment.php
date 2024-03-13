<?php

namespace Narfu\Payments\Paykeeper\Resources;

use Narfu\Payments\Paykeeper\Request;

class Payment
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * Info constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function info(string $paymentId = "")
    {
        return $this->request->get('/info/payments/byid/', [ "id" => $paymentId]);
    }

    public function params(string $paymentId = "")
    {
        return $this->request->get('/info/params/byid/', [ "id" => $paymentId]);
    }

}
