<?php

namespace ERecht24\Er24Rechtstexte\Api;

use ERecht24\Er24Rechtstexte\Utility\HelperUtility;

class BaseApi
{
    /**
     * Api key
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $domain = '';

    /**
     * BaseApi constructor.
     */
    public function __construct(string $apiKey, string $domain)
    {
        $this->apiKey = $apiKey;
        $this->domain = $domain;
    }

    /**
     * Function provides api url
     */
    public function getApiUrl(string $path = ''): string
    {
        return ($path !== '' && $path !== '0')
            ? HelperUtility::API_HOST_URL . '/' . $path
            : '';
    }

    /**
     * Function provides api key
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    protected function performRequest($uri, $method, $requestData = null)
    {

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'eRecht24:' . $this->getApiKey(),
        ]);

        if ($requestData !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        }

        $res = curl_exec($ch);

        return [
            'response' => [
                'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            ],
            'body' => $res,
        ];

    }

    /**
     * Function evaluates response and returns ordered data
     * @param $response
     */
    public function handleResponse($response): ApiResponse
    {
        $responseCode = $response['response']['code'] ?? 500;
        return match ($responseCode) {
            200           => $this->handleSuccess($response),              // ok
            400, 401, 403 => $this->handleError($response, $responseCode), // unsuccessful, unauthorized, invalid parameter
            404           => $this->handle404(),                           // wrong url
            default       => $this->handleError($response, $responseCode), // fallback
        };
    }

    /**
     * Function provides success data
     */
    protected function handleSuccess(array $response): ApiResponse
    {
        return new ApiResponse(
            200,
            true,
            json_decode((string)$response['body'], true)
        );
    }

    /**
     * Function provides error data
     */
    protected function handleError(array $response, int $code): ApiResponse
    {
        return new ApiResponse(
            $code,
            false,
            json_decode($response['body'] ?? '', true)
        );
    }

    /**
     * Function provides data for 404 responses
     */
    protected function handle404(): ApiResponse
    {
        return new ApiResponse(
            404,
            false,
            [
                'message' => __('Wrong api url! Please contact admin.', HelperUtility::PLUGIN_TEXT_DOMAIN),
            ]
        );
    }

    /**
     * Function provides data for no response
     * @param $response
     */
    protected function handleNoResponse(
        $response
    ): ApiResponse {
        return new ApiResponse(
            404,
            false,
            [
                'message' => __('No Response received. Please contact the admin.', HelperUtility::PLUGIN_TEXT_DOMAIN),
                'originResponse' => $response,
            ]
        );
    }
}
