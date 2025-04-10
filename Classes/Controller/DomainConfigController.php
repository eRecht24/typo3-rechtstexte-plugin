<?php
declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Controller;


use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use ERecht24\Er24Rechtstexte\Utility\ApiUtility;
use ERecht24\Er24Rechtstexte\Utility\UpdateUtility;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Site\Entity\Site;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use ERecht24\Er24Rechtstexte\Api\Client;
use TYPO3\CMS\Core\Core\Environment;
use ERecht24\Er24Rechtstexte\Utility\HelperUtility;
use ERecht24\Er24Rechtstexte\Utility\LogUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Frontend\Typolink\EmailLinkBuilder;

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
class DomainConfigController extends ActionController
{

    /**
     * @var string
     */
    protected $extensionName = 'er24_rechtstexte';

    /**
     * domainConfigRepository
     *
     * @var DomainConfigRepository
     */
    protected $domainConfigRepository = null;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager = null;

    /**
     * @var ApiUtility
     */
    protected $apiUtility = null;

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    )
    {
    }

    protected function defaultActionHandling()
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->registerDocheaderButtons($moduleTemplate);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    protected function registerDocheaderButtons(ModuleTemplate $moduleTemplate)
    {

    }

    /**
     * @param ApiUtility $apiUtility
     */
    public function injectApiUtility(ApiUtility $apiUtility)
    {
        $this->apiUtility = $apiUtility;
    }

    /**
     * @param PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param DomainConfigRepository $domainConfigRepository
     */
    public function injectDomainConfigRepository(DomainConfigRepository $domainConfigRepository)
    {
        $this->domainConfigRepository = $domainConfigRepository;
    }

    /**
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function performUpdateAction()
    {
        $updateUtility = new UpdateUtility();
        if (true === $updateUtility->performSelfUpdate()) {
            $this->addFlashMessage(LocalizationUtility::translate('message-prefix', $this->request->getControllerExtensionName()) . LocalizationUtility::translate('update-success', $this->request->getControllerExtensionName()), '', AbstractMessage::OK);
        } else {
            $this->addFlashMessage(LocalizationUtility::translate('message-prefix', $this->request->getControllerExtensionName()) . LocalizationUtility::translate('update-failed', $this->request->getControllerExtensionName()), '', AbstractMessage::WARNING);
        }

        return (new ForwardResponse('list'));
    }

    /**
     * action list
     *
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $jsRequiredLanguageKeys = [
            'attention',
            'delete-confirm',
            'abort'
        ];

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

        foreach ($jsRequiredLanguageKeys as $key) {
            $label = LocalizationUtility::translate($key, 'er24_rechtstexte');
            $pageRenderer->addInlineLanguageLabel(str_replace('-', '_', $key), $label);
        }

        $updateUtility = new UpdateUtility();

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        $allSiteConfigurations = $siteFinder->getAllSites();
        $domainConfigs = $this->domainConfigRepository->findAll();

        $domainsLeft = $configuredDomains = [];

        /** @var Site $siteConfig */
        foreach ($allSiteConfigurations as $index => $siteConfig) {
            $domainsLeft[(string)$siteConfig->getBase()] = $index;
        }

        /** @var DomainConfig $config */
        foreach ($domainConfigs as $config) {
            $configuredDomains[$config->getDomain()] = $config->getDomain();

            if (true === isset($domainsLeft[$config->getDomain()])) {
                unset($domainsLeft[$config->getDomain()]);
            }
        }

        /** @var Site $siteConfig */
        foreach ($allSiteConfigurations as $index => $siteConfig) {
            $match = false;
            foreach ($domainsLeft as $domain => $siteIdentifier) {
                if ($index === $siteIdentifier) {
                    $match = true;
                }
            }
            if ($match === false) {
                unset($allSiteConfigurations[$index]);
            }
        }

        $this->view->assignMultiple([
            'domainConfigs' => $domainConfigs,
            'allSiteConfigurations' => $allSiteConfigurations,
            'configuredDomains' => $configuredDomains,
            'updateAvailable' => $updateUtility->updateAvailable,
            'latestVersion' => $updateUtility->latestVersion,
            'composerMode' => $updateUtility->composeMode
        ]);

        return $this->defaultActionHandling();
    }

    /**
     * action show
     * @return ResponseInterface
     */
    public function showAction(): ResponseInterface
    {
        /** @var DomainConfig $domainConfig */
        $domainConfig = $this->domainConfigRepository->findByUid($this->settings['configId']);
        if ($domainConfig === null) {
            // TODO
            $this->addFlashMessage(LocalizationUtility::translate('configuration-not-found', $this->request->getControllerExtensionName()), '', AbstractMessage::WARNING);
            return $this->htmlResponse($this->view->render());
        }

        $language = $this->settings['documentLanguage'];

        switch ($this->settings['documentType']) {
            case 'imprint':
                $source = $domainConfig->getImprintSource() === 0 ? 'Local' : '';
                break;
            case 'privacy':
                $source = $domainConfig->getPrivacySource() === 0 ? 'Local' : '';
                break;
            case 'social':
                $source = $domainConfig->getSocialSource() === 0 ? 'Local' : '';
        }

        $getterFunctionName = 'get' . ucfirst($this->settings['documentType']) . $language . $source;

        if (method_exists($domainConfig, $getterFunctionName)) {
            $outputText = $domainConfig->$getterFunctionName();

            if (true === (bool)$this->settings['removeHeadline'] && strpos($outputText, '</h1>') !== false) {
                $outputText = substr($outputText, strpos($outputText, '</h1>') + 5);
            }

            // check if outputText isn't empty
            if (strlen(trim($outputText)) > 0) {
                // replace emails with TYPO3 spambot safe links
                // try to get it working with not normalized domain names
                // please use idn syntax: https://de.wikipedia.org/wiki/Internationalisierter_Domainname
                $mailRegex = "/([-0-9a-zA-Z.+_äöüßÄÖÜéèê]+@[-0-9a-zA-Z.+_äöüßÄÖÜéèê]+\.[a-zA-Z]+)/";
                preg_match_all($mailRegex, $outputText, $matches);

                if(is_array($matches[0]))
                  $matches[0] = array_unique($matches[0]);

                foreach ($matches[0] as $match) {
                    $outputText = str_replace($match, $this->createEmailLink($match), $outputText);
                }
            }

            $GLOBALS['TSFE']->addCacheTags(['er24_document_' . $domainConfig->getUid()]);

            $this->view->assignMultiple([
                'outputText' => $outputText
            ]);
        } else {
            $this->addFlashMessage(LocalizationUtility::translate('document-not-found', $this->request->getControllerExtensionName()), '', AbstractMessage::WARNING);
        }
        return $this->htmlResponse();

    }

    private function createEmailLink(string $email): string
    {
        if (version_compare(VersionNumberUtility::getNumericTypo3Version(), "12.0.0", "<")) {
            [$linkHref, $linkText] = $GLOBALS['TSFE']->cObj->getMailTo($email, '');
            return sprintf("<a href='%s'>%s</a>", $linkHref, $linkText);
        } else {
            $typoScriptFrontendController = $this->request->getAttribute('frontend.controller');
            $emailLinkBuilder = GeneralUtility::makeInstance(EmailLinkBuilder::class, $typoScriptFrontendController->cObj, $typoScriptFrontendController);
            [$mailToUrl, $linkText, $attributes] = $emailLinkBuilder->processEmailLink($email, $email);
            $linkAttributesString = "";
            if (!empty($attributes)) {
                foreach ($attributes as $attributeKey => $attributeValue) {
                    $linkAttributesString .= " " . $attributeKey . '="' . $attributeValue . '"';
                }
            }
            return sprintf("<a href=\"%s\"%s>%s</a>", $mailToUrl, $linkAttributesString, $linkText);
        }
    }

    /**
     * @param DomainConfig|null $newDomainConfig
     * @param string $siteconfigIdentifier
     */
    public function newAction(DomainConfig $newDomainConfig = null, string $siteconfigIdentifier = ''): ResponseInterface
    {
        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $siteConfig = null;

        if ($newDomainConfig === null) {
            $newDomainConfig = new DomainConfig();
            $newDomainConfig->setSiteConfigName($siteconfigIdentifier);
        }

        if ($newDomainConfig->getSiteConfigName() !== '') {
            try {
                $siteConfig = $siteFinder->getSiteByIdentifier($newDomainConfig->getSiteConfigName());
                $newDomainConfig->setDomain((string)$siteConfig->getBase());
            } catch (\Exception $e) {
            }
        }

        $allSites = $siteFinder->getAllSites();
        $allDomainConfigs = $this->domainConfigRepository->findAll();

        // Remove already used Siteconfigs
        /** @var DomainConfig $domainConfig */
        foreach ($allDomainConfigs as $domainConfig) {
            if (true === array_key_exists($domainConfig->getSiteConfigName(), $allSites)) {
                unset($allSites[$domainConfig->getSiteConfigName()]);
            }
        }

        $this->view->assignMultiple([
            'newDomainConfig' => $newDomainConfig,
            'siteConfig' => $siteConfig,
            'allSiteConfigurations' => $allSites
        ]);

        return $this->defaultActionHandling();
    }

    /**
     * action create
     *
     * @param DomainConfig $newDomainConfig
     * @return void
     */
    public function createAction(DomainConfig $newDomainConfig)
    {
        $this->addFlashMessage(LocalizationUtility::translate('message-prefix', $this->request->getControllerExtensionName()) . ' ' . LocalizationUtility::translate('config-was-created', $this->request->getControllerExtensionName()), '', AbstractMessage::OK);

        $now = time();
        $newDomainConfig->setSocialEnTstamp($now);
        $newDomainConfig->setSocialDeTstamp($now);
        $newDomainConfig->setImprintEnTstamp($now);
        $newDomainConfig->setImprintDeTstamp($now);
        $newDomainConfig->setPrivacyEnTstamp($now);
        $newDomainConfig->setPrivacyDeTstamp($now);

        $this->domainConfigRepository->add($newDomainConfig);
        $this->persistenceManager->persistAll();

        return (new ForwardResponse('edit'))
            ->withArguments(['domainConfig' => $newDomainConfig->getUid()]);
    }

    /**
     * action edit
     *
     * @param DomainConfig $domainConfig
     * @return ResponseInterface
     */
    #[IgnoreValidation(['value' => 'domainConfig'])]
    public function editAction(DomainConfig $domainConfig): ResponseInterface
    {
        $jsRequiredLanguageKeys = [
            'connection_error_detected',
            'message-prefix',
            'attention',
            'delete-confirm',
            'delete',
            'abort',
            'debug-was-copied',
            'error'
        ];

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

        foreach ($jsRequiredLanguageKeys as $key) {
            $label = LocalizationUtility::translate($key, 'er24_rechtstexte');
            $pageRenderer->addInlineLanguageLabel(str_replace('-', '_', $key), $label);
        }

        $errors = $pushError = $configError = $erechtServerError = $curlError = false;
        $configErrorMessages = [];

        if ($domainConfig->getClientId() !== '') {
            $client = new Client($domainConfig->getApiKey(), $domainConfig->getDomain());
            $apiResponse = $client->testPushPing((int)$domainConfig->getClientId());
            if ($apiResponse->isSuccess() === false) {
                $pushError = true;
                $errors = true;
            }

        } else {
            $errors = $configError = $pushError = true;
            $configErrorMessages[] = 'noclient_exists';
        }

        if ($domainConfig->getApiKey() === '') {
            $configErrorMessages[] = 'noapikey_exists';
        } else {
            $client = new Client($domainConfig->getApiKey(), $domainConfig->getDomain());
            $apiResponse = $client->listClients();
            if ($apiResponse->isSuccess() === false) {
                $erechtServerError = true;
            }
        }

        $curlError = function_exists('curl_version') ? false : true;

        $debugInformations = 'PHP Version: ' . phpversion() . PHP_EOL;
        $debugInformations .= 'TYPO3 Composer Mode: ' . (int)Environment::isComposerMode() . PHP_EOL;
        $debugInformations .= 'cURL Error: ' . (int)$curlError . PHP_EOL;
        $debugInformations .= 'Push Error: ' . (int)$pushError . PHP_EOL;
        $debugInformations .= 'API Connection Error: ' . (int)$erechtServerError . PHP_EOL;
        $debugInformations .= 'Configuration Errors: ' . (int)$configError . PHP_EOL;

        if (count($configErrorMessages) > 0) {
            $debugInformations .= 'Configuration Error Details: ' . PHP_EOL;
            foreach ($configErrorMessages as $error) {
                $debugInformations .= $error . PHP_EOL;
            }
        }

        $debugInformations .= PHP_EOL;
        $debugInformations .= 'API Key: ' . substr($domainConfig->getApiKey(), 0, 30) . '...' . PHP_EOL;
        $debugInformations .= 'Client ID: ' . $domainConfig->getClientId() . PHP_EOL;
        $debugInformations .= 'Client Secret: ' . substr($domainConfig->getClientSecret(), 0, 30) . '...' . PHP_EOL;
        $debugInformations .= 'API Host: ' . HelperUtility::API_HOST_URL . PHP_EOL;
        $debugInformations .= 'API Push URI: ' . $domainConfig->getDomain() . '/erecht24/v1/push' . PHP_EOL;
        $debugInformations .= PHP_EOL;
        $debugInformations .= 'Error Log:' . PHP_EOL;
        $debugInformations .= LogUtility::getErrorLog();
        $debugInformations .= PHP_EOL;
        $debugInformations .= 'Extension informations:' . PHP_EOL;


        /** @var PackageManager $packageManager */
        $packageManager = GeneralUtility::makeInstance(PackageManager::class);
        foreach ($packageManager->getActivePackages() as $extension) {
            $debugInformations .= $extension->getPackageKey() . ' (' . $extension->getPackageMetaData()->getVersion() . ')' . PHP_EOL;
        }

        // The Docs //
        require_once(ExtensionManagementUtility::extPath('er24_rechtstexte') . 'Resources/Private/Vendor/Erusev/Parsedown/Parsedown.php');

        $parseDown = new \Parsedown();
        if (isset($GLOBALS['BE_USER']->uc['lang']) && $GLOBALS['BE_USER']->uc['lang'] === 'de') {
            $documentation = (string)$parseDown->text(file_get_contents(ExtensionManagementUtility::extPath('er24_rechtstexte') . 'Documentation/Documentation_de.md'));
        } else {
            $documentation = (string)$parseDown->text(file_get_contents(ExtensionManagementUtility::extPath('er24_rechtstexte') . 'Documentation/Documentation_en.md'));
        }

        $this->view->assignMultiple([
            'domainConfig' => $domainConfig,
            'errors' => $errors,
            'pushError' => $pushError ? 1 : 0,
            'erechtServerError' => $erechtServerError ? 1 : 0,
            'configError' => $configError ? 1 : 0,
            'configErrorMessages' => $configErrorMessages,
            'curlError' => $curlError ? 1 : 0,
            'debugInformations' => $debugInformations,
            'documentation' => $documentation,
            't3version' => VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version())
        ]);

        return $this->defaultActionHandling();
    }

    /**
     * action update
     *
     * @param DomainConfig $domainConfig
     * @return ForwardResponse
     */
    public function updateAction(DomainConfig $domainConfig)
    {
        $apiHandlerResult = $this->apiUtility->handleDomainConfigUpdate($domainConfig, $domainConfig->getApiKey());
        self::handleApiHandlerResults($apiHandlerResult);

        if ($domainConfig->getImprintSource() === null) {
            $domainConfig->setImprintSource(0);
        }
        if ($domainConfig->getSocialSource() === null) {
            $domainConfig->setSocialSource(0);
        }
        if ($domainConfig->getPrivacySource() === null) {
            $domainConfig->setPrivacySource(0);
        }

        $this->domainConfigRepository->update($domainConfig);
        $this->persistenceManager->persistAll();

        /** @var CacheManager $cacheManager */
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->flushCachesByTag('er24_document_' . $domainConfig->getUid());

        return (new ForwardResponse('edit'))
            ->withArguments(['domainConfig' => $domainConfig->getUid()]);
    }

    protected function handleApiHandlerResults($apiHandlerResult)
    {
        if (count($apiHandlerResult[0]) > 0) {
            foreach ($apiHandlerResult[0] as $error) {
                $this->addFlashMessage(LocalizationUtility::translate('message-prefix', $this->request->getControllerExtensionName()) . ' ' . $error, '', AbstractMessage::WARNING);
            }
        }
        if (count($apiHandlerResult[1]) > 0) {
            foreach ($apiHandlerResult[1] as $success) {
                $this->addFlashMessage(LocalizationUtility::translate('message-prefix', $this->request->getControllerExtensionName()) . ' ' . $success, '', AbstractMessage::OK);
            }
        }
    }

    /**
     * action delete
     * @param DomainConfig $domainConfig
     * @return ForwardResponse
     */
    public function deleteAction(DomainConfig $domainConfig)
    {
        if ($domainConfig->getClientId() !== '' && $domainConfig->getApiKey() !== '') {
            $apiHandlerResult = $this->apiUtility->deleteDomainConfigClient($domainConfig, $domainConfig->getApiKey());
            self::handleApiHandlerResults($apiHandlerResult);
        }

        $this->addFlashMessage(LocalizationUtility::translate('message-prefix', $this->request->getControllerExtensionName()) . ' ' . LocalizationUtility::translate('config-was-deleted', $this->request->getControllerExtensionName()), '', AbstractMessage::OK);
        $this->domainConfigRepository->remove($domainConfig);
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $persistenceManager->persistAll();

        return new ForwardResponse('list');
    }
}
