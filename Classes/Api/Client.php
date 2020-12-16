<?php
declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Api;

//use eRecht24\LegalTexts\App\Hooks\Actions\ClientCreated as ClientCreatedAction;
//use eRecht24\LegalTexts\App\Hooks\Actions\ClientDeleted as DatabaseStorer;
//use eRecht24\LegalTexts\App\Hooks\Filter\ClientCreated as ClientCreatedFilter;
//use eRecht24\LegalTexts\App\Hooks\Filter\ClientDeleted as RequestSanitizer;

class Client extends BaseApi
{
    const CLIENT_CREATED_ACTION = 'erecht24_action_client_created';
    const CLIENT_CREATED_FILTER = 'erecht24_filter_client_created';

    const CLIENT_DELETED_ACTION = 'erecht24_action_client_deleted';
    const CLIENT_DELETED_FILTER = 'erecht24_filter_client_deleted';

    /**
     * Function provides a list of all clients
     * @return ApiResponse
     */
    public function listClients() : ApiResponse
    {
        return $this->handleResponse(
            wp_remote_request(
                $this->getApiUrl('v1/clients'),
                [
                    'method' => 'GET',
                    'headers' => [
                        'Content-Type' => 'application/json; charset=utf-8',
                        'eRecht24' => (string) $this->getApiKey()
                    ]
                ]
            )
        );
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

//        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($response);
//
//        // register default hooks
//        $this->registerAddClientHooks();
//
//        // Apply filter hooks
//        $response = apply_filters(
//            self::CLIENT_CREATED_FILTER,
//            $response
//        );
//
//        // Apply action hooks
//        do_action(
//        self::CLIENT_CREATED_ACTION,
//            $response
//        );
//
//        return $response;
    }

    /**
     * Function deletes client remotely
     * @param int $clientId
     * @return ApiResponse
     */
    public function deleteClient(
        int $clientId
    ) : ApiResponse
    {


        // Api Request
        $response = $this->handleResponse(
            $this->performRequest(
                $this->getApiUrl('v1/clients/' . $clientId),
                'DELETE'
            )
        );

        return $response;

//        $response = $this->handleResponse(
//            wp_remote_request(
//                $this->getApiUrl('v1/clients/' . $clientId),
//                [
//                    'method' => 'DELETE',
//                    'headers' => [
//                        'Content-Type' => 'application/json; charset=utf-8',
//                        'eRecht24' => (string) $this->getApiKey()
//                    ]
//                ]
//            )
//        );
//
//        // register default hooks
//        $this->registerDeleteClientHooks();
//
//        // Apply filter hooks
//        $response = apply_filters(
//            self::CLIENT_DELETED_FILTER,
//            $response
//        );
//
//        // Apply action hooks
//        do_action(
//            self::CLIENT_DELETED_ACTION,
//            $response
//        );
//
//        return $response;
    }

    /**
     * Function executes test push
     * @param int $clientId
     * @return ApiResponse
     */
    public function testPushPing(
        int $clientId
    ) : ApiResponse
    {
        return $this->handleResponse(
            wp_remote_request(
                $this->getApiUrl('v1/clients/' . $clientId . '/testPush'),
                [
                    'method' => 'POST',
                    'headers' => [
                        'Content-Type' => 'application/json; charset=utf-8',
                        'eRecht24' => (string) $this->getApiKey(),
                    ]
                ]
            )
        );
    }

    /**
     * Function provides request body for add client method
     *
     * @return string
     */
    private function createRequestBody() : string
    {
        $request_body  = [];

        $typo3Version = new \TYPO3\CMS\Core\Information\Typo3Version();

        $request_body['push_method'] = 'GET';
        $request_body['push_uri']    = $this->domain . 'erecht24/v1/push';
        $request_body['cms']         = 'TYPO3';
        $request_body['cms_version'] = $typo3Version->getVersion();
        $request_body['plugin_name'] = 'eRecht24.de Rechtstexte für TYPO3';
        $request_body['author_mail'] = 'test@test.com';

        return json_encode($request_body, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Register default hooks after client was created
     */
    private function registerAddClientHooks() : void
    {
        // Register default filter Hook
        add_filter(
            self::CLIENT_CREATED_FILTER,
            [new ClientCreatedFilter(), 'execute'],
            1
        );

        // Register default action Hook
        add_action(
            self::CLIENT_CREATED_ACTION,
            [new ClientCreatedAction(), 'execute']
        );
    }

    /**
     * Register default hooks after Client was deleted
     */
    private function registerDeleteClientHooks() : void
    {
        // Register default filter Hook
        add_filter(
            self::CLIENT_DELETED_FILTER,
            [new RequestSanitizer(), 'execute'],
            1
        );

        // Register default action Hook
        add_action(
            self::CLIENT_DELETED_ACTION,
            [new DatabaseStorer(), 'execute']
        );
    }
}
