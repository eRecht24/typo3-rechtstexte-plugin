<?php
declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Api;

//use eRecht24\LegalTexts\App\Hooks\Actions\DocumentImported as DatabaseStorer;
//use eRecht24\LegalTexts\App\Hooks\Filter\DocumentImported as RequestSanitizer;

class LegalDocument extends BaseApi
{
    const ALLOWED_DOCUMENT_TYPES = [
        'imprint',
        'privacyPolicy',
        'privacyPolicySocialMedia'
    ];

    private $documentType;

    /**
     * LegalDocuments constructor.
     * @param string $apiKey
     * @param string $documentType
     * @param string $domain
     * @throws \Exception
     */
    public function __construct(string $apiKey, string $documentType, string $domain) {
        if (!self::documentTypeIsValid($documentType)) {
            throw new \Exception('Unsupported document type specified. Abort.');
        }

        parent::__construct($apiKey, $domain);
        $this->documentType = $documentType;
    }

    public static function documentTypeIsValid(string $documentType) :bool
    {
        return in_array($documentType, self::ALLOWED_DOCUMENT_TYPES);
    }

    /**
     * Function provides ERecht24 legal document
     * @return ApiResponse
     */
    public function importDocument() : ApiResponse {

        $response = $this->handleResponse(
            $this->performRequest(
                $this->getApiUrl($this->getApiUrlPath()),
                'GET'
            )
        );

        return $response;

    }

    /**
     * Function provides api url path
     * @return string
     */
    private function getApiUrlPath() :string
    {
        return sprintf('v1/%s', $this->documentType);
    }

}
