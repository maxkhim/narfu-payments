<?php

namespace Narfu\Payments\Paykeeper\Resources;

use Narfu\Payments\Paykeeper\Request;

class Invoice
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

    public function prepare(array $params = [])
    {
        return $this->request->post('/change/invoice/preview/', $params);
    }

    public function info(string $invoiceId = "")
    {
        return $this->request->get('/info/invoice/byid/', [ "id" => $invoiceId]);
    }


}
