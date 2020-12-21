<?php
declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Controller;


/***
 *
 * This file is part of the "eRecht24 Rechtstexte Extension" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * DomainConfigController
 */
class DomainConfigController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * domainConfigRepository
     *
     * @var \ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository
     */
    protected $domainConfigRepository = null;

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

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository $domainConfigRepository
     */
    public function injectDomainConfigRepository(\ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository $domainConfigRepository)
    {
        $this->domainConfigRepository = $domainConfigRepository;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Site\SiteFinder::class);

        $allSiteConfigurations = $siteFinder->getAllSites();
        $domainConfigs = $this->domainConfigRepository->findAll();

        $domainsLeft = $configuredDomains = [];

        /** @var \TYPO3\CMS\Core\Site\Entity\Site $siteConfig */
        foreach ($allSiteConfigurations as $index => $siteConfig) {
            /** @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage $language */
            foreach ($siteConfig->getAllLanguages() as $language) {
                $domainsLeft[$language->getBase()->getScheme() . '://' . $language->getBase()->getHost() . '/'] = $index;
            }
        }

        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $config */
        foreach ($domainConfigs as $config) {
            $urlParts = parse_url($config->getDomain());
            if($urlParts !== false) {
                $configuredDomains[(string) $urlParts['host']] = $urlParts['host'];
            }
            if (true === isset($domainsLeft[$config->getDomain()])) {
                unset($domainsLeft[$config->getDomain()]);
            }
        }

        /** @var \TYPO3\CMS\Core\Site\Entity\Site $siteConfig */
        foreach ($allSiteConfigurations as $index => $siteConfig) {
            $match = false;
            foreach ($domainsLeft as $domain => $siteIdentifier) {
                if($index === $siteIdentifier) {
                    $match = true;
                }
            }
            if($match === false) {
                unset($allSiteConfigurations[$index]);
            }
        }

        $this->view->assignMultiple([
            'domainConfigs' => $domainConfigs,
            'allSiteConfigurations' => $allSiteConfigurations,
            'configuredDomains' => $configuredDomains
        ]);
    }

    /**
     * action show
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @return void
     */
    public function showAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        $this->view->assign('domainConfig', $domainConfig);
    }

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig|null $newDomainConfig
     * @param string $siteconfigIdentifier
     * @param int $languageId
     */
    public function newAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $newDomainConfig = null, string $siteconfigIdentifier = '', int $languageId = 0)
    {

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Site\SiteFinder::class);


        if ($newDomainConfig === null) {
            $newDomainConfig = new \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig();
            $newDomainConfig->setSiteConfigName($siteconfigIdentifier);
            $newDomainConfig->setSiteLanguage($languageId);
        }

        if ($newDomainConfig->getSiteConfigName() !== '') {
            try {
                $siteConfig = $siteFinder->getSiteByIdentifier($newDomainConfig->getSiteConfigName());
                $language = $siteConfig->getLanguageById($newDomainConfig->getSiteLanguage());
                $allLanguages = $siteConfig->getAllLanguages();
                $newDomainConfig->setDomain($language->getBase()->getScheme() . '://' . $language->getBase()->getHost() . '/');
            } catch (\Exception $e) {
                $siteConfig = $language = $allLanguages = null;
            }
        }

        $this->view->assignMultiple([
            'newDomainConfig' => $newDomainConfig,
            'siteConfig' => $siteConfig,
            'allLanguages' => $allLanguages,
            'allSiteConfigurations' => $siteFinder->getAllSites()
        ]);
    }

    /**
     * action create
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $newDomainConfig
     * @return void
     */
    public function createAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $newDomainConfig)
    {

        $this->addFlashMessage('eRecht24 Extension für TYPO3: Die Konfiguration wurde erfolgreich erstellt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

        $now = time();

        $newDomainConfig->setSocialEnTstamp($now);
        $newDomainConfig->setSocialDeTstamp($now);
        $newDomainConfig->setImprintEnTstamp($now);;
        $newDomainConfig->setImprintDeTstamp($now);
        $newDomainConfig->setPrivacyEnTstamp($now);
        $newDomainConfig->setPrivacyDeTstamp($now);

        $this->domainConfigRepository->add($newDomainConfig);
        $this->persistenceManager->persistAll();

        if ($newDomainConfig->getSiteConfigName() !== '') {
            /** @var \TYPO3\CMS\Core\Configuration\SiteConfiguration $siteConfiguration */
            $siteConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\SiteConfiguration::class);
            $configurationArray = $siteConfiguration->load($newDomainConfig->getSiteConfigName());
            if (true === isset($configurationArray['languages'][$newDomainConfig->getSiteLanguage()])) {
                $configurationArray['languages'][$newDomainConfig->getSiteLanguage()]['eRecht24Config'] = $newDomainConfig->getUid();
                $siteConfiguration->write($newDomainConfig->getSiteConfigName(), $configurationArray);
            }
        }

        $this->redirect('edit', null, null, ['domainConfig' => $newDomainConfig->getUid()]);

    }

    /**
     * action edit
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("domainConfig")
     * @return void
     */
    public function editAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        $this->view->assign('domainConfig', $domainConfig);
    }

    /**
     * action update
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @return void
     */
    public function updateAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        //$this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);

        $apiHandlerResult = $this->apiUtility->handleDomainConfigUpdate($domainConfig);
        self::handleApiHandlerResults($apiHandlerResult);

        $this->domainConfigRepository->update($domainConfig);
        $this->persistenceManager->persistAll();

        $this->redirect('edit', null, null, ['domainConfig' => $domainConfig->getUid()]);
    }

    protected function handleApiHandlerResults($apiHandlerResult) {
        if(count($apiHandlerResult[0]) > 0) {
            foreach ($apiHandlerResult[0] as $error) {
                $this->addFlashMessage('eRecht24 Extension für TYPO3: ' . $error, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            }
        }
        if(count($apiHandlerResult[1]) > 0) {
            foreach ($apiHandlerResult[1] as $success) {
                $this->addFlashMessage('eRecht24 Extension für TYPO3: ' . $success, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
            }
        }
    }

    /**
     * action delete
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @return void
     */
    public function deleteAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->domainConfigRepository->remove($domainConfig);
        $this->redirect('list');
    }
}
