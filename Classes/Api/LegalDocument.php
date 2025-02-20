<?php

declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Api;

class LegalDocument extends BaseApi
{
    public const ALLOWED_DOCUMENT_TYPES = [
        'imprint',
        'privacyPolicy',
        'privacyPolicySocialMedia',
    ];

    /**
     * @var string
     */
    private $documentType = '';

    /**
     * LegalDocuments constructor.
     * @throws \Exception
     */
    public function __construct(string $apiKey, string $documentType, string $domain)
    {
        if (!self::documentTypeIsValid($documentType)) {
            throw new \Exception('Unsupported document type specified. Abort.', 6314123237);
        }

        parent::__construct($apiKey, $domain);
        $this->documentType = $documentType;
    }

    public static function documentTypeIsValid(string $documentType): bool
    {
        return in_array($documentType, self::ALLOWED_DOCUMENT_TYPES);
    }

    /**
     * Function provides ERecht24 legal document
     */
    public function importDocument(): ApiResponse
    {

        return $this->handleResponse(
            $this->performRequest(
                $this->getApiUrl($this->getApiUrlPath()),
                'GET'
            )
        );

    }

    /**
     * Function provides api url path
     */
    private function getApiUrlPath(): string
    {
        return sprintf('v1/%s', $this->documentType);
    }

}
