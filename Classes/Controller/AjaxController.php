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

        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfg */
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
                    // TODO: Handle setter not found
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

        $oldApiKey = $domainConfg->getApiKey();
        $domainConfg->setApiKey($newApiKey);
        $this->domainConfigRepository->update($domainConfg);
        $this->persistenceManager->persistAll();

        $errors = $successes = [];

        if($domainConfg->getApiKey() === '') {

            $errors[] = 'Kein API Key hinterlegt';

            if($domainConfg->getClientId() !== '' && $oldApiKey !== '') {

                $client = new \ERecht24\Er24Rechtstexte\Api\Client($oldApiKey, $domainConfg->getDomain());
                $clientResult = $client->deleteClient($domainConfg->getClientId());

                if($clientResult->isSuccess() === false) {
                    $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
                } else {
                    $domainConfg->setClientId('');
                    $domainConfg->setClientSecret('');
                    $this->domainConfigRepository->update($domainConfg);
                    $this->persistenceManager->persistAll();
                }
            }

            return self::handleError($errors);

        }

        if($domainConfg->getClientId() === '') {
            $client = new \ERecht24\Er24Rechtstexte\Api\Client($domainConfg->getApiKey(), $domainConfg->getDomain());
            $clientResult = $client->addClient();

            if($clientResult->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($clientResult);
            } else {
                $domainConfg->setClientId($clientResult->getData('client_id'));
                $domainConfg->setClientSecret($clientResult->getData('secret'));
                $this->domainConfigRepository->update($domainConfg);
                $this->persistenceManager->persistAll();
            }
        }

        foreach (\ERecht24\Er24Rechtstexte\Api\LegalDocument::ALLOWED_DOCUMENT_TYPES as $documentType) {
            $documentClient = new \ERecht24\Er24Rechtstexte\Api\LegalDocument($domainConfg->getApiKey(), $documentType, $domainConfg->getDomain());
            $document = $documentClient->importDocument();

            if($document->isSuccess() === false) {
                $errors[] = HelperUtility::getBestFittingApiErrorMessage($document);
                if($document->getCode() === 400) {
                    $domainConfg = HelperUtility::removeDocument($domainConfg, $documentType);
                }
            } else {
                $domainConfg = HelperUtility::assignDocumentToDomainConfig($document, $domainConfg, $documentType);
                $successes[] = $documentType . '_imported';
            }
        }

        $this->domainConfigRepository->update($domainConfg);
        $this->persistenceManager->persistAll();

        return new \TYPO3\CMS\Core\Http\JsonResponse([
            'errors' => $errors,
            'successes' => $successes
        ]);
    }

    protected function handleError($errors) {
        return new \TYPO3\CMS\Core\Http\JsonResponse(['errors' => $errors]);
    }

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
