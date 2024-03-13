<?php

namespace Narfu\Payments\Yookassa;

use Narfu\Payments\Yookassa\Resources\Info;
use Narfu\Payments\Yookassa\Resources\Payment;

class YookassaClient
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var ?Info
     */
    private ?Info $info = null;

    /**
     * @var ?Payment
     */
    private ?Payment $payment = null;

    /**
     * Client constructor.
     * @param string|null $apiHost
     * @param string|null $login
     * @param string|null $password
     */
    public function __construct(?string $apiHost = null, ?string $login = null, ?string $password = null)
    {
        if (!$apiHost) {
            $apiHost = config("narfu-payments.yookassa_api_url");
        }

        $this->request = new Request(
            $apiHost,
            $login,
            $password,
        );
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Info
     */
    public function info(): Info
    {
        if (!$this->info) {
            $this->info = new Info($this->request);
        }

        return $this->info;
    }

    /**
     * @return Payment
     */
    public function payment(): Payment
    {
        if (!$this->payment) {
            $this->payment = new Payment($this->request);
        }
        return $this->payment;
    }







}
