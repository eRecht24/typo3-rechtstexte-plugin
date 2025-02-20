<?php

namespace ERecht24\Er24Rechtstexte\Utility;

use ERecht24\Er24Rechtstexte\Api\Client;
use ERecht24\Er24Rechtstexte\Api\LegalDocument;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class ApiUtility
{
    public function deleteDomainConfigClient(DomainConfig $domainConfig, string $apiKey)
    {

        $errors = [];
        $successes = [];
        $client = new Client($apiKey, $domainConfig->getDomain());
        $clientResult = $client->deleteClient($domainConfig->getClientId());

        if ($clientResult->isSuccess() === false) {
            $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
        } else {
            $successes[] = LocalizationUtility::translate('api-client-removed', 'Er24Rechtstexte');
        }

        $domainConfig->setClientId('');
        $domainConfig->setClientSecret('');

        return [$errors, $successes];

    }

    /**
     * @throws \Exception
     */
    public function importDocument(DomainConfig $domainConfig, string $documentType, ?string $success_message = null)
    {

        $errors = [];
        $successes = [];
        $documentClient = new LegalDocument($domainConfig->getApiKey(), $documentType, $domainConfig->getDomain());
        $document = $documentClient->importDocument();

        if ($document->isSuccess() === false) {
            $errors[] = HelperUtility::getBestFittingApiErrorMessage($document);
            if ($document->getCode() === 400) {
                HelperUtility::removeDocument($domainConfig, $documentType);
            }
        } else {
            HelperUtility::assignDocumentToDomainConfig($document, $domainConfig, $documentType);
            $successes[] = $success_message ?? LocalizationUtility::translate($documentType . '_imported', 'Er24Rechtstexte');
        }

        return [$errors, $successes];
    }

    /**
     * @throws TooDirtyException
     */
    public function handleDomainConfigUpdate(DomainConfig $domainConfig, string $newApiKey = ''): array
    {

        $errors = [];
        $successes = [];
        if ($newApiKey !== $domainConfig->getApiKey()) {
            $domainConfig->setApiKey($newApiKey);
        }

        if ($domainConfig->getApiKey() === '') {
            $errors[] = LocalizationUtility::translate('no-api-key', 'Er24Rechtstexte');
        }

        if ($newApiKey !== $domainConfig->getApiKey() || $domainConfig->_isDirty('apiKey')) {

            // Api Key has been modified
            $oldApiKey = $domainConfig->_getCleanProperty('apiKey');

            $client = new Client($domainConfig->getApiKey(), $domainConfig->getDomain());
            $apiResponse = $client->listClients();

            if ($apiResponse->isSuccess() === false && $domainConfig->getApiKey() !== '') {
                $errors[] = LocalizationUtility::translate('invalid-api-key', 'Er24Rechtstexte');
                $domainConfig->setApiKey($oldApiKey);
            } elseif ($domainConfig->getClientId() !== '' && $oldApiKey !== '') {
                $handlerResponse = self::deleteDomainConfigClient($domainConfig, $oldApiKey);
                $errors = array_merge($handlerResponse[0], $errors);
                $successes = array_merge($handlerResponse[1], $successes);
            }
        }

        if ($domainConfig->getApiKey() === '') {
            // At least yet there is nothing more to do
            return [$errors, $successes];
        }

        if ($domainConfig->getClientId() === '') {

            $client = new Client($domainConfig->getApiKey(), $domainConfig->getDomain());
            $clientResult = $client->addClient();

            if ($clientResult->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
            } else {
                $successes[] = LocalizationUtility::translate('connection-established', 'Er24Rechtstexte');
                $domainConfig->setClientId($clientResult->getData('client_id'));
                $domainConfig->setClientSecret($clientResult->getData('secret'));
            }
        }

        if ($domainConfig->getClientId() === '') {
            $errors[] = LocalizationUtility::translate('client-creation-failed', 'Er24Rechtstexte');
        }

        return [$errors, $successes];

    }

}
