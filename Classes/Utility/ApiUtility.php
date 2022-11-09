<?php
namespace ERecht24\Er24Rechtstexte\Utility;


use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class ApiUtility
{

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @param string $apiKey
     */
    public function deleteDomainConfigClient(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig, string $apiKey) {

        $errors = $successes = [];

        $client = new \ERecht24\Er24Rechtstexte\Api\Client($apiKey, $domainConfig->getDomain());
        $clientResult = $client->deleteClient($domainConfig->getClientId());

        if($clientResult->isSuccess() === false) {
            $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
        } else {
            $successes[] = LocalizationUtility::translate('api-client-removed', 'er24_rechtstexte');
        }

        $domainConfig->setClientId('');
        $domainConfig->setClientSecret('');

        return [$errors, $successes];

    }

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @param string $documentType
     * @throws \Exception
     */
    public function importDocument(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig, string $documentType) {

        $errors = $successes = [];

        $documentClient = new \ERecht24\Er24Rechtstexte\Api\LegalDocument($domainConfig->getApiKey(), $documentType, $domainConfig->getDomain());
        $document = $documentClient->importDocument();

        if($document->isSuccess() === false) {
            $errors[] = HelperUtility::getBestFittingApiErrorMessage($document);
            if($document->getCode() === 400) {
                HelperUtility::removeDocument($domainConfig, $documentType);
            }
        } else {
            HelperUtility::assignDocumentToDomainConfig($document, $domainConfig, $documentType);
            $successes[] = LocalizationUtility::translate($documentType . '_imported', 'er24_rechtstexte');
        }

        return [$errors, $successes];
    }

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @param string $newApiKey
     * @return array
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException
     */
    public function handleDomainConfigUpdate(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig, string $newApiKey = '') {

        $errors = $successes = [];

        if($newApiKey !== $domainConfig->getApiKey()) {
            $domainConfig->setApiKey($newApiKey);
        }

        if($domainConfig->getApiKey() === '') {
            $errors[] = LocalizationUtility::translate('no-api-key', 'er24_rechtstexte');
        }

        if($newApiKey !== $domainConfig->getApiKey() || $domainConfig->_isDirty('apiKey')) {

            // Api Key has been modified
            $oldApiKey = $domainConfig->_getCleanProperty('apiKey');

            $client = new \ERecht24\Er24Rechtstexte\Api\Client($domainConfig->getApiKey(), $domainConfig->getDomain());
            $apiResponse = $client->listClients();

            if($apiResponse->isSuccess() === false && $domainConfig->getApiKey() !== '') {
                $errors[] = LocalizationUtility::translate('invalid-api-key', 'er24_rechtstexte');
                $domainConfig->setApiKey($oldApiKey);
            } else if($domainConfig->getClientId() !== '' && $oldApiKey !== '') {
                $handlerResponse = self::deleteDomainConfigClient($domainConfig, $oldApiKey);
                $errors = array_merge($handlerResponse[0], $errors);
                $successes = array_merge($handlerResponse[1], $successes);
            }
        }

        if($domainConfig->getApiKey() === '') {
            // At least yet there is nothing more to do
            return [$errors, $successes];
        }


        if($domainConfig->getClientId() === '') {

            $client = new \ERecht24\Er24Rechtstexte\Api\Client($domainConfig->getApiKey(), $domainConfig->getDomain());
            $clientResult = $client->addClient();

            if($clientResult->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
            } else {
                $successes[] = LocalizationUtility::translate('connection-established', 'er24_rechtstexte');
                $domainConfig->setClientId($clientResult->getData('client_id'));
                $domainConfig->setClientSecret($clientResult->getData('secret'));
            }
        }

        if($domainConfig->getClientId() === '') {
            $errors[] = LocalizationUtility::translate('client-creation-failed', 'er24_rechtstexte');
        }

        return [$errors, $successes];

    }

}
