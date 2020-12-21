<?php
namespace ERecht24\Er24Rechtstexte\Utility;


class ApiUtility
{

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @param string $newApiKey
     * @return array
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException
     */
    public function handleDomainConfigUpdate(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig, string $newApiKey = '') {

        $errors = $successes = [];

        if($domainConfig->getApiKey() === '') {
            $errors[] = 'Kein API Key hinterlegt';
            return [$errors, $successes];
        }

        if($newApiKey !== $domainConfig->getApiKey() || $domainConfig->_isDirty('apiKey')) {
            // Api Key has been modified
            $oldApiKey = $domainConfig->_getCleanProperty('apiKey');

            if($domainConfig->getClientId() !== '' && $oldApiKey !== '') {

                $client = new \ERecht24\Er24Rechtstexte\Api\Client($oldApiKey, $domainConfig->getDomain());
                $clientResult = $client->deleteClient($domainConfig->getClientId());

                if($clientResult->isSuccess() === false) {
                    $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
                } else {
                    $domainConfig->setClientId('');
                    $domainConfig->setClientSecret('');
                }

                return [$errors, $successes];

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
                $successes[] = 'Gültiger API Schlüssel. Verbindung zur eRecht24 API wurde aufgebaut.';
                $domainConfig->setClientId($clientResult->getData('client_id'));
                $domainConfig->setClientSecret($clientResult->getData('secret'));
            }
        }

        return [$errors, $successes];

    }

}
