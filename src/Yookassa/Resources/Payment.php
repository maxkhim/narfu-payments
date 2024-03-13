<?php

namespace Narfu\Payments\Yookassa\Resources;

use Narfu\Payments\Yookassa\Request;

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

    public function list()
    {
        return $this->request->get('/payments');
    }

    public function get(string $id)
    {
        return $this->request->get('/payments/'.$id);
    }

    public function create($paymentData)
    {
        return $this->request->post('/payments', $paymentData);
    }
}
