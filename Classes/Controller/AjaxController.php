<?php

namespace ERecht24\Er24Rechtstexte\Controller;

use ERecht24\Er24Rechtstexte\Api\Client;
use ERecht24\Er24Rechtstexte\Api\LegalDocument;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository;
use ERecht24\Er24Rechtstexte\Utility\ApiUtility;
use ERecht24\Er24Rechtstexte\Utility\HelperUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class AjaxController
{
    /**
     * @var string
     */
    protected $extensionName = 'er24_rechtstexte';

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var ApiUtility
     */
    protected $apiUtility;

    /**
     * @var DomainConfigRepository
     */
    protected $domainConfigRepository;

    public function __construct(ApiUtility $apiUtility, PersistenceManager $persistenceManager, DomainConfigRepository $domainConfigRepository)
    {
        $this->apiUtility = $apiUtility;
        $this->persistenceManager = $persistenceManager;
        $this->domainConfigRepository = $domainConfigRepository;
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function syncDocumentAction(ServerRequestInterface $request): ResponseInterface
    {

        $domainConfigId = (int)$request->getQueryParams()['domainConfigId'];

        /** @var DomainConfig $domainConfig */
        $domainConfig = $this->domainConfigRepository->findByUid($domainConfigId);
        $errors = [];
        $successes = [];
        $response = [];
        $documentType = $request->getQueryParams()['documentType'];

        if ($domainConfig->getApiKey() === '') {
            $errors[] = LocalizationUtility::translate('no-api-key', $this->extensionName);
        } elseif (in_array($documentType, LegalDocument::ALLOWED_DOCUMENT_TYPES) === false) {
            $errors[] = LocalizationUtility::translate('unknown-document-requested', $this->extensionName) . ' ' . $documentType;
        } else {
            $documentClient = new LegalDocument($domainConfig->getApiKey(), $documentType, $domainConfig->getDomain());
            $document = $documentClient->importDocument();

            if ($document->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($document);
                if ($document->getCode() === 400) {
                    $domainConfig = HelperUtility::removeDocument($domainConfig, $documentType);
                }
            } else {
                $domainConfig = HelperUtility::assignDocumentToDomainConfig($document, $domainConfig, $documentType);
                $successes[] = LocalizationUtility::translate($documentType . '_imported', 'Er24Rechtstexte');
                $response['html_de'] = $document->getData('html_de');
                $response['html_en'] = $document->getData('html_en');
                $response['modified'] = $document->getData('modified');
            }
        }

        $this->domainConfigRepository->update($domainConfig);
        $this->persistenceManager->persistAll();

        return new JsonResponse([
            'errors' => $errors,
            'successes' => $successes,
            'response' => $response,
        ]);

    }

    /**
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function saveDomainConfigAction(ServerRequestInterface $request): ResponseInterface
    {

        $errors = [];
        $successes = [];
        $domainConfigId = (int)$request->getQueryParams()['domainConfigId'];

        /** @var DomainConfig $domainConfig */
        $domainConfig = $this->domainConfigRepository->findByUid($domainConfigId);

        $reflectionService = GeneralUtility::makeInstance(ReflectionService::class);

        if (is_array($request->getQueryParams()['properties'])) {
            foreach ($request->getQueryParams()['properties'] as $propertyName => $propertyValue) {
                $setterName = 'set' . ucfirst($propertyName);
                if (method_exists($domainConfig, $setterName)) {
                    $methodReflection = $reflectionService->getClassSchema(DomainConfig::class)->getMethod($setterName);
                    $propertyType = null;
                    foreach ($methodReflection->getParameters() as $parameter) {
                        $propertyType = $parameter->getType();
                        break;
                    }

                    if ($propertyType !== null) {
                        if (settype($propertyValue, $propertyType)) {
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

        if ($errors === []) {
            $successes[] = LocalizationUtility::translate('auto-saved', $this->extensionName);
        }

        if (isset($request->getQueryParams()['flushAnalyticsCache']) && (int)$request->getQueryParams()['flushAnalyticsCache'] === 1) {
            /** @var CacheManager $cacheManager */
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            $cacheManager->flushCachesByTag('er24_analytics_' . $domainConfig->getUid());
        }

        $this->domainConfigRepository->update($domainConfig);
        $this->persistenceManager->persistAll();

        return new JsonResponse([
            'errors' => $errors,
            'successes' => $successes,
        ]);
    }

    /**
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function syncAllDocumentsAction(ServerRequestInterface $request): ResponseInterface
    {

        $domainConfigId = (int)$request->getQueryParams()['domainConfigId'];
        $newApiKey = $request->getQueryParams()['apiKey'];

        /** @var DomainConfig $domainConfg */
        $domainConfg = $this->domainConfigRepository->findByUid($domainConfigId);

        $domainConfg->setApiKey($newApiKey);

        $apiHandlerResult = $this->apiUtility->handleDomainConfigUpdate($domainConfg, $newApiKey);

        $errors = $apiHandlerResult[0];
        $successes = $apiHandlerResult[1];

        $this->domainConfigRepository->update($domainConfg);
        $this->persistenceManager->persistAll();

        if ($domainConfg->getClientId() !== '') {
            foreach (LegalDocument::ALLOWED_DOCUMENT_TYPES as $documentType) {
                $apiHandlerResult = $this->apiUtility->importDocument($domainConfg, $documentType);
                $errors = array_merge($apiHandlerResult[0], $errors);
                $successes = array_merge($apiHandlerResult[1], $successes);
            }

            $this->domainConfigRepository->update($domainConfg);
            $this->persistenceManager->persistAll();
        }

        return new JsonResponse([
            'errors' => $errors,
            'successes' => $successes,
        ]);
    }

    protected function handleError($errors)
    {
        return new JsonResponse(['errors' => $errors]);
    }

    public function refreshConnectionAction(ServerRequestInterface $request): ResponseInterface
    {

        $errors = [];
        $successes = [];
        $fixed = [];
        $domainConfigId = (int)$request->getQueryParams()['domainConfigId'];

        /** @var DomainConfig $domainConfig */
        $domainConfig = $this->domainConfigRepository->findByUid($domainConfigId);

        $client = new Client($domainConfig->getApiKey(), $domainConfig->getDomain());

        $response = $client->listClients();
        if ($response->isSuccess() === false) {
            $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
            return new JsonResponse([
                'errors' => $errors,
            ]);
        }

        $fixed[] = 'apiConnection';

        if ($domainConfig->getClientId() !== '') {
            $response = $client->deleteClient($domainConfig->getClientId());
            if ($response->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
            }

            $response = $client->addClient();
            if ($response->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
            } else {
                $fixed[] = 'clientConfiguration';
                $successes[] = LocalizationUtility::translate('connection-established', $this->extensionName);
                $domainConfig->setClientId($response->getData('client_id'));
                $domainConfig->setClientSecret($response->getData('secret'));

                $this->domainConfigRepository->update($domainConfig);
                $this->persistenceManager->persistAll();

                $response = $client->testPushPing($domainConfig->getClientId());
                if ($response->isSuccess()) {
                    $fixed[] = 'push';
                } else {
                    $errors[] = HelperUtility::getBestFittingApiErrorMessage($response);
                }
            }

        } else {
            $clientResult = $client->addClient();
            if ($clientResult->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
            } else {
                $successes[] = LocalizationUtility::translate('connection-established', 'Er24RechtstexteLts8');
                $domainConfig->setClientId($clientResult->getData('client_id'));
                $domainConfig->setClientSecret($clientResult->getData('secret'));
                $this->domainConfigRepository->update($domainConfig);
                $this->persistenceManager->persistAll();
            }
        }

        return new JsonResponse([
            'errors' => $errors,
            'successes' => $successes,
            'fixed' => $fixed,
        ]);

    }

    /**
     * @deprecated
     */
    public function changeSiteConfigAction(ServerRequestInterface $request): ResponseInterface
    {

        $newSiteConfig = [];

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        $siteIdentifier = $request->getQueryParams()['siteconfig'];

        try {
            $newSiteConfig = $siteFinder->getSiteByIdentifier($siteIdentifier);
        } catch (\Exception) {
        }

        $languageInformations = [];

        /** @var SiteLanguage $language */
        foreach ($newSiteConfig->getAllLanguages() as $language) {
            $languageInformations[] = [
                // @extensionScannerIgnoreLine
                'languageId' => $language->getLanguageId(),
                'name' => $language->getTitle(),
                'domain' => $language->getBase()->getScheme() . '://' . $language->getBase()->getHost() . '/',
            ];
        }

        return new JsonResponse($languageInformations);
    }

}
