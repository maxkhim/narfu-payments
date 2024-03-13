<?php

namespace Narfu\Payments\Paykeeper\Resources;

use Narfu\Payments\Paykeeper\Request;

class Info
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

    public function systemList(array $params = [])
    {
        return $this->request->get('/info/systems/list/', $params);
    }

    public function getToken(array $params = [])
    {
        return $this->request->get('/info/settings/token/', $params);
    }



}
