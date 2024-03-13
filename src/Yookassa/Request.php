<?php

namespace Narfu\Payments\Yookassa;

use Illuminate\Support\Str;
use Narfu\Payments\HttpClient\CurlClientResponse;
use Narfu\Payments\HttpClient\CurlHttpClient;
use Narfu\Payments\HttpClient\CurlRequestException;

class Request
{
    protected const HTTP_STATUS_CODE_OK = 200;
    protected const HTTP_STATUS_CODE_CREATED = 201;
    protected const HTTP_STATUS_CODE_ACCEPTED = 202;
    protected const HTTP_STATUS_CODE_NO_CONTENT = 204;

    /**
     * @var string
     */
    private string $apiFullUrl;

    /**
     * @var CurlHttpClient
     */
    private CurlHttpClient $httpClient;


    /**
     * Request constructor.
     * @param string $apiUrl
     */
    public function __construct(string $apiUrl, ?string $login = null, ?string $password = null)
    {
        $this->httpClient = new CurlHttpClient($login, $password);
        $this->apiFullUrl = $apiUrl;
    }

    private function getApiUri(): string
    {
        return $this->apiFullUrl."/";
    }

    /**
     * Makes request.
     *
     * @param string $resource
     * @param array $params
     *
     * @return mixed
     *
     **/
    private function request(string $resource, array $params = array(), string $requestType = "get")
    {
        //$params = $this->formatParams($params);

        $url = $this->getApiUri() . $resource;

        try {
            $response = null;
            switch ($requestType) {
                case "post":
                    if ($params) {
                        $this->httpClient->addInitialHeaderContentType("application/json;charset=UTF-8");
                        $this->httpClient->addInitialHeaderCustom("Idempotence-Key", Str::uuid());
                    }
                    $request = json_encode($params, JSON_THROW_ON_ERROR);
                    $response = $this->httpClient->post($url, $request);
                    break;
                default:
                    $response = $this->httpClient->get($url, $params);
                    break;
            }
        } catch (CurlRequestException|\JsonException $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->parseResponse($response);
    }

    /**
     * Makes post request.
     *
     * @param string $resource
     * @param array $params
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function post(string $resource, array $params = array())
    {
        return $this->request($resource, $params, "post");
    }

    /**
     * Makes get request.
     *
     * @param string $resource
     * @param array $params
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function get(string $resource, array $params = array())
    {
        return $this->request($resource, $params);
    }

    /**
     * Decodes the response and checks its status code and whether it has an Api error. Returns decoded response.
     *
     * @param CurlClientResponse $response
     *
     * @return mixed
     *
     */
    private function parseResponse(CurlClientResponse $response)
    {
        $this->checkHttpStatus($response);

        $body = $response->getBody();
        $decode_body = $this->decodeBody($body);
        $headers = $response->getHeaders();

        return ["status" => $headers[0]??null, "header" => $headers[1]??[], "body" => $decode_body];
    }

    /**
     * Formats given array of parameters for making the request.
     *
     * @param array $params
     *
     * @return array
     */
    private function formatParams(array $params)
    {
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $params[$key] = $value ? 1 : 0;
            }
        }
        return $params;
    }

    /**
     * Decodes body.
     *
     * @param string $body
     *
     * @return mixed
     */
    protected function decodeBody(string $body)
    {
        $decoded_body = json_decode($body, true);

        if ($decoded_body === null || !is_array($decoded_body)) {
            $decoded_body = [];
        }

        return $decoded_body;
    }

    /**
     * @param CurlClientResponse $response
     *
     * @throws \Exception
     */
    protected function checkHttpStatus(CurlClientResponse $response): void
    {
        if (!in_array(
            (int)$response->getHttpStatus(),
            [
                static::HTTP_STATUS_CODE_OK,
                static::HTTP_STATUS_CODE_CREATED,
                static::HTTP_STATUS_CODE_ACCEPTED,
                static::HTTP_STATUS_CODE_NO_CONTENT
            ]
        )) {
            throw new \Exception($response->getBody(), $response->getHttpStatus());
        }
    }
}
