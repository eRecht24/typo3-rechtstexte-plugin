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

    public function syncAllDocumentsAction(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface {

        $domainConfigId = (int) $request->getQueryParams()['domainConfigId'];
        $newApiKey = $request->getQueryParams()['apiKey'];

        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfg */
        $domainConfg = $this->domainConfigRepository->findByUid($domainConfigId);

        $oldApiKey = $domainConfg->getApiKey();
        $domainConfg->setApiKey($newApiKey);
        $this->domainConfigRepository->update($domainConfg);
        $this->persistenceManager->persistAll();

        $errors = [];

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
            }

        }

        if(count($errors) > 0) {
            return self::handleError($errors);
        }

        return new \TYPO3\CMS\Core\Http\Response('');

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
