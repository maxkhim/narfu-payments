<?php

namespace Narfu\Payments\Yookassa\Resources;

use Narfu\Payments\Yookassa\Request;

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

    public function info()
    {
        return $this->request->get('/me');
    }

}
