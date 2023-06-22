<?php
declare(strict_types=1);
namespace ERecht24\Er24Rechtstexte\Api;

use TYPO3\CMS\Core\Information\Typo3Version;
class Client extends BaseApi
{
    /**
     * Function provides a list of all clients
     * @return ApiResponse
     */
    public function listClients() : ApiResponse
    {
        // Api Request
        $response = $this->handleResponse(
            $this->performRequest(
                $this->getApiUrl('v1/clients'),
                'GET'
            )
        );
        return $response;
    }

    /**
     * Function adds a client remotely
     * @return ApiResponse
     */
    public function addClient() : ApiResponse
    {

        // Api Request
        $response = $this->handleResponse(
            $this->performRequest(
                $this->getApiUrl('v1/clients'),
                'POST',
                $this->createRequestBody()
            )
        );

        return $response;
    }

    /**
     * Function deletes client remotely
     * @param int $clientId
     * @return ApiResponse
     */
    public function deleteClient(int $clientId) : ApiResponse {

        // Api Request
        $response = $this->handleResponse(
            $this->performRequest(
                $this->getApiUrl('v1/clients/' . $clientId),
                'DELETE'
            )
        );
        return $response;
    }

    /**
     * Function executes test push
     * @param int $clientId
     * @return ApiResponse
     */
    public function testPushPing(int $clientId) : ApiResponse
    {

        // Api Request
        $response = $this->handleResponse(
            $this->performRequest(
                $this->getApiUrl('v1/clients/' . $clientId . '/testPush'),
                'POST'
            )
        );
        return $response;
    }

    /**
     * Function provides request body for add client method
     *
     * @return string
     */
    private function createRequestBody() : string
    {
        $request_body  = [];

        $typo3Version = new Typo3Version();

        $request_body['push_method'] = 'GET';
        $request_body['push_uri']    = $this->domain . '/erecht24/v1/push';
        $request_body['cms']         = 'TYPO3';
        $request_body['cms_version'] = $typo3Version->getVersion();
        $request_body['plugin_name'] = 'eRecht24.de Rechtstexte für TYPO3';
        $request_body['author_mail'] = 'test@test.com'; // TODO

        return json_encode($request_body, JSON_UNESCAPED_UNICODE);
    }
}
