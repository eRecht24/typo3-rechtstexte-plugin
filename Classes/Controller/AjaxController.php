<?php
namespace ERecht24\Er24Rechtstexte\Controller;

use ERecht24\Er24Rechtstexte\Utility\HelperUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Response;

class AjaxController
{

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager = null;

    /**
     * @var \ERecht24\Er24Rechtstexte\Utility\ApiUtility
     */
    protected $apiUtility = null;

    /**
     * @param \ERecht24\Er24Rechtstexte\Utility\ApiUtility $apiUtility
     */
    public function injectApiUtility(\ERecht24\Er24Rechtstexte\Utility\ApiUtility $apiUtility) {
        $this->apiUtility = $apiUtility;
    }

    public function injectPersistenceManager(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager) {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @var \ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository
     */
    protected $domainConfigRepository = null;

    public function injectDomainConfigRepository(\ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository $domainConfigRepository) {
        $this->domainConfigRepository = $domainConfigRepository;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function syncDocumentAction(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface {

        $domainConfigId = (int) $request->getQueryParams()['domainConfigId'];

        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig */
        $domainConfig = $this->domainConfigRepository->findByUid($domainConfigId);

        $errors = $successes = $response = [];
        $documentType = $request->getQueryParams()['documentType'];

        if($domainConfig->getApiKey() === '') {
            $errors[] = 'Kein API Key hinterlegt';
        } else if(false === in_array($documentType, \ERecht24\Er24Rechtstexte\Api\LegalDocument::ALLOWED_DOCUMENT_TYPES)) {
            $errors[] = 'Ungültiges Dokument '. $documentType .' angefordert';
        }else {
            $documentClient = new \ERecht24\Er24Rechtstexte\Api\LegalDocument($domainConfig->getApiKey(), $documentType, $domainConfig->getDomain());
            $document = $documentClient->importDocument();

            if($document->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($document);
                if($document->getCode() === 400) {
                    $domainConfig = HelperUtility::removeDocument($domainConfig, $documentType);
                }
            } else {
                $domainConfig = HelperUtility::assignDocumentToDomainConfig($document, $domainConfig, $documentType);
                $successes[] = $documentType . '_imported';
                $response['html_de'] = $document->getData('html_de');
                $response['html_en'] = $document->getData('html_en');
                $response['modified'] = $document->getData('modified');
            }
        }

        $this->domainConfigRepository->update($domainConfig);
        $this->persistenceManager->persistAll();

        return new \TYPO3\CMS\Core\Http\JsonResponse([
            'errors' => $errors,
            'successes' => $successes,
            'response' => $response
        ]);

    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function saveDomainConfigAction(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface {

        $errors = $successes = [];

        $domainConfigId = (int) $request->getQueryParams()['domainConfigId'];

        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig */
        $domainConfig = $this->domainConfigRepository->findByUid($domainConfigId);

        $reflectionService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Reflection\ReflectionService::class);

        if(true === is_array($request->getQueryParams()['properties'])) {
            foreach ($request->getQueryParams()['properties'] as $propertyName => $propertyValue) {
                $setterName = 'set'.ucfirst($propertyName);
                if(true === method_exists($domainConfig,$setterName)) {
                    $methodReflection = $reflectionService->getClassSchema(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig::class)->getMethod($setterName);
                    $propertyType = null;
                    foreach ($methodReflection->getParameters() as $parameter) {
                        $propertyType = $parameter->getType();
                        break;
                    }
                    if($propertyType !== null) {
                        if(true === settype($propertyValue, $propertyType)) {
                            $domainConfig->$setterName($propertyValue);
                        } else {
                            // TODO: Handle setter not found
                        }
                    } else {
                        // TODO: Handle setter not found
                    }
                } else {
                    $errors[] = 'Unkown property ' . $propertyName;
                }
            }
        }

        if(count($errors) === 0) {
            $successes[] = 'Automatisch gespeichert.';
        }

        $this->domainConfigRepository->update($domainConfig);
        $this->persistenceManager->persistAll();

        //return new \TYPO3\CMS\Core\Http\HtmlResponse('');

        return new \TYPO3\CMS\Core\Http\JsonResponse([
            'errors' => $errors,
            'successes' => $successes
        ]);
    }


    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function syncAllDocumentsAction(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface {

        $domainConfigId = (int) $request->getQueryParams()['domainConfigId'];
        $newApiKey = $request->getQueryParams()['apiKey'];

        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfg */
        $domainConfg = $this->domainConfigRepository->findByUid($domainConfigId);

        $domainConfg->setApiKey($newApiKey);
        $apiHandlerResult = $this->apiUtility->handleDomainConfigUpdate($domainConfg, $newApiKey);

        $errors = $apiHandlerResult[0];
        $successes = $apiHandlerResult[1];

        $this->domainConfigRepository->update($domainConfg);
        $this->persistenceManager->persistAll();

        if($domainConfg->getClientId() !== '') {
            foreach (\ERecht24\Er24Rechtstexte\Api\LegalDocument::ALLOWED_DOCUMENT_TYPES as $documentType) {
                $apiHandlerResult = $this->apiUtility->importDocument($domainConfg, $documentType);
                $errors = array_merge($apiHandlerResult[0], $errors);
                $successes = array_merge($apiHandlerResult[1], $successes);
            }

            $this->domainConfigRepository->update($domainConfg);
            $this->persistenceManager->persistAll();
        }

        return new \TYPO3\CMS\Core\Http\JsonResponse([
            'errors' => $errors,
            'successes' => $successes
        ]);
    }

    protected function handleError($errors) {
        return new \TYPO3\CMS\Core\Http\JsonResponse(['errors' => $errors]);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function refreshConnectionAction(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface {

        $errors = $successes = $fixed = [];
        $domainConfigId = (int) $request->getQueryParams()['domainConfigId'];

        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig */
        $domainConfig = $this->domainConfigRepository->findByUid($domainConfigId);

        $client = new \ERecht24\Er24Rechtstexte\Api\Client($domainConfig->getApiKey(), $domainConfig->getDomain());

        $response = $client->listClients();
        if($response->isSuccess() === false) {
            $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
            return new \TYPO3\CMS\Core\Http\JsonResponse([
                'errors' => $errors,
            ]);
        } else {
            $fixed[] = 'apiConnection';
        }

        if($domainConfig->getClientId() !== '') {
            $response = $client->deleteClient($domainConfig->getClientId());
            if($response->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
            } else {
                $response = $client->addClient();
                if($response->isSuccess() === false) {
                    $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
                } else {
                    $fixed[] = 'clientConfiguration';
                    $successes[] = 'Gültiger API Schlüssel. Verbindung zur eRecht24 API wurde aufgebaut.';
                    $domainConfig->setClientId($response->getData('client_id'));
                    $domainConfig->setClientSecret($response->getData('secret'));

                    $this->domainConfigRepository->update($domainConfig);
                    $this->persistenceManager->persistAll();

                    $response = $client->testPushPing($domainConfig->getClientId());
                    if($response->isSuccess() === true) {
                        $fixed[] = 'push';
                    } else {
                        $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
                    }
                }
            }
        }

        return new \TYPO3\CMS\Core\Http\JsonResponse([
            'errors' => $errors,
            'successes' => $successes,
            'fixed' => $fixed
        ]);

    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @deprecated
     */
    public function changeSiteConfigAction(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface {

        $newSiteConfig = [];

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Site\SiteFinder::class);

        $siteIdentifier = $request->getQueryParams()['siteconfig'];

        try {
            $newSiteConfig = $siteFinder->getSiteByIdentifier($siteIdentifier);
        } catch(\Exception $e) {
        }

        $languageInformations = [];

        /** @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage $language */
        foreach ($newSiteConfig->getAllLanguages() as $language) {
            $languageInformations[] = [
                'languageId' => $language->getLanguageId(),
                'name' => $language->getTitle(),
                'domain' => $language->getBase()->getScheme() . '://' . $language->getBase()->getHost() . '/'
            ];
        }

        //$response = new Response(json_encode($languageInformations), 200, ['Content-Type' => 'application/json; charset=utf-8']);

        return new \TYPO3\CMS\Core\Http\JsonResponse($languageInformations);
    }

}
