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
     * @param string $apiKey
     * @param string $domain
     */
    public function __construct( string $apiKey, string $domain)
    {
        $this->apiKey = $apiKey;
        $this->domain = $domain;
    }

    /**
     * Function provides api url
     * @param string $path
     * @return string
     */
    public function getApiUrl ( string $path = ''): string
    {
        return ($path)
            ? HelperUtility::API_HOST_URL . '/' . $path
            : '';
    }

    /**
     * Function provides api key
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    protected function performRequest($uri, $method, $requestData = null) {

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'eRecht24:' . (string) $this->getApiKey()
        ]);

        if($requestData !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        }

        $res = curl_exec($ch);

        return [
            'response' => [
                'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            ],
            'body' => $res
        ];

    }

    /**
     * Function evaluates response and returns ordered data
     * @param $response
     * @return ApiResponse
     */
    public function handleResponse($response) : ApiResponse
    {

//        if(!$response || $response instanceof WP_Error) {
//            return $this->handleNoResponse($response);
//        }

        $responseCode = $response['response']['code'] ?? 500;
        switch($responseCode) {
            case 200: // ok
                return $this->handleSuccess($response);

            case 400: // unsuccessful
            case 401: // unauthorized
            case 403: // invalid parameter
                return $this->handleError($response, $responseCode);

            case 404: // wrong url
                return $this->handle404();

            default:
                return $this->handleError($response, $responseCode);
        }
    }

    /**
     * Function  provides success data
     * @param array $response
     * @return ApiResponse
     */
    protected function handleSuccess(
        array $response
    ): ApiResponse
    {
        return new ApiResponse(
            200,
            true,
            json_decode($response['body'], true)
        );
    }

    /**
     * Function provides error data
     * @param array $response
     * @param int $code
     * @return ApiResponse
     */
    protected function handleError(
        array $response,
        int $code
    ) : ApiResponse
    {
        return new ApiResponse(
            $code,
            false,
            json_decode($response['body'] ?? '', true)
        );
    }

    /**
     * Function provides data for 404 responses
     * @return ApiResponse
     */
    protected function handle404() : ApiResponse
    {
        return new ApiResponse(
            404,
            false,
            [
                "message" => __('Wrong api url! Please contact admin.', HelperUtility::PLUGIN_TEXT_DOMAIN)
            ]
        );
    }

    /**
     * Function provides data for no response
     * @param $response
     * @return ApiResponse
     */
    protected function handleNoResponse(
        $response
    ): ApiResponse
    {
        return new ApiResponse(
            404,
            false,
            [
                "message" => __('No Response received. Please contact the admin.', HelperUtility::PLUGIN_TEXT_DOMAIN),
                "originResponse" => $response
            ]
        );
    }
}
