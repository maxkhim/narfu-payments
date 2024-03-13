<?php

namespace Narfu\Payments\HttpClient;

class CurlHttpClient
{
    protected const QUESTION_MARK = '?';
    protected const CONNECTION_TIMEOUT = 10;
    /**
     * @var array
     */
    protected array $initialConnectionOptions;

    /**
     * CurlHttpClient constructor.
     * @param string|null $login
     * @param string|null $password
     * @param int $connectionTimeout
     */
    public function __construct(?string $login = null, ?string $password = null, int $connectionTimeout = self::CONNECTION_TIMEOUT)
    {
        if (!$login) {
            $login = config("narfu-payments.login");
        }

        if (!$password) {
            $password = config("narfu-payments.password");
        }

        $base64AuthData = base64_encode("$login:$password");

        $this->initialConnectionOptions = array(
            CURLOPT_HEADER         => true,
            CURLOPT_CONNECTTIMEOUT => $connectionTimeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Basic ' . $base64AuthData,
            ]
        );
    }

    public function addInitialHeaderContentType(string $contentType = "application/x-www-form-urlencoded")
    {
        $this->addInitialHeaderCustom("Content-Type", $contentType);
    }

    public function addInitialHeaderCustom($header = "Content-Type", string $value = "application/x-www-form-urlencoded")
    {
        $this->initialConnectionOptions[CURLOPT_HTTPHEADER][] = $header.": ".$value;
    }



    /**
     * post request.
     *
     * @param string $url
     * @param array|null $payload
     *
     * @return CurlClientResponse
     * @throws CurlRequestException
     */
    public function post(string $url, string $request = null): CurlClientResponse
    {
        return $this->sendRequest($url, array(
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_CUSTOMREQUEST => "POST"
        ));
    }

    /**
     * get request.
     *
     * @param string $url
     * @param array|null $payload
     *
     * @return CurlClientResponse
     * @throws CurlRequestException
     */
    public function get(string $url, ?array $payload = null): CurlClientResponse
    {
        $fullUrl = $url;
        if ($payload) {
            $fullUrl = $url . static::QUESTION_MARK . http_build_query($payload);
        }

        return $this->sendRequest($fullUrl, array());
    }



    /**
     * and sends request.
     *
     * @param string $url
     * @param array $opts
     *
     * @return CurlClientResponse
     * @throws CurlRequestException
     */
    public function sendRequest(string $url, array $opts): CurlClientResponse
    {
        $curl = curl_init($url);

        curl_setopt_array($curl, $this->initialConnectionOptions + $opts);

        $response = curl_exec($curl);

        $curl_error_code = curl_errno($curl);
        $curl_error = curl_error($curl);

        $http_status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if ($curl_error || $curl_error_code) {
            $error_msg = "Curl error $curl_error_code";
            if ($curl_error) {
                $error_msg .= ": $curl_error";
            }

            $error_msg .= '.';

            throw new CurlRequestException($error_msg);
        }

        return $this->parseRawResponse($http_status, $response);
    }

  /**
   * Breaks the raw response down into its headers, body and http status code.
   *
   * @param int $http_status
   * @param string $response
   *
   * @return CurlClientResponse
   */
    protected function parseRawResponse(int $http_status, string $response): CurlClientResponse
    {
        list($raw_headers, $body) = $this->extractResponseHeadersAndBody($response);
        $headers = $this->getHeaders($raw_headers);
        return new CurlClientResponse($http_status, $headers, $body);
    }

    /**
     * Extracts the headers and the body into a two-part array.
     *
     * @param string $response
     *
     * @return array
     */
    protected function extractResponseHeadersAndBody(string $response): array
    {
        $parts = explode("\r\n\r\n", $response);
        $raw_body = array_pop($parts);
        $raw_headers = implode("\r\n\r\n", $parts);

        return [trim($raw_headers), trim($raw_body)];
    }

    /**
     * Parses the raw headers and sets as an array.
     *
     * @param string The raw headers from the response.
     *
     * @return array
     */
    protected function getHeaders(string $raw_headers): array
    {
        // Normalize line breaks
        $raw_headers = str_replace("\r\n", "\n", $raw_headers);

        // There will be multiple headers if a 301 was followed
        // or a proxy was followed, etc
        $header_collection = explode("\n\n", trim($raw_headers));
        // We just want the last response (at the end)
        $raw_header = array_pop($header_collection);

        $header_components = explode("\n", $raw_header);
        $result = array();
        $http_status = 0;
        foreach ($header_components as $line) {
            if (strpos($line, ': ') === false) {
                $http_status = $this->getHttpStatus($line);
            } else {
                list($key, $value) = explode(': ', $line, 2);
                $result[$key] = $value;
            }
        }

        return array($http_status, $result);
    }

    /**
     * Sets the HTTP response code from a raw header.
     *
     * @param string $raw_response_header
     *
     * @return int
     */
    protected function getHttpStatus(string $raw_response_header): int
    {
        preg_match('|HTTP/\d(?:\.\d)?\s+(\d+)\s+.*|', $raw_response_header, $match);
        return (int)$match[1];
    }
}
