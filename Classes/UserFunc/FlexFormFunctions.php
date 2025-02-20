<?php

namespace ERecht24\Er24Rechtstexte\UserFunc;

use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class FlexFormFunctions
{
    public function filterDomainConfigs(array $config)
    {

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        try {
            $siteConfig = $siteFinder->getSiteByPageId($config['flexParentDatabaseRow']['pid']);
            $domainConfig = GeneralUtility::makeInstance(DomainConfigRepository::class)->findOneBy(['domain' => (string)$siteConfig->getBase()]);
            if ($domainConfig instanceof DomainConfig) {
                $config['items'] = [];
                $config['items'][] = [
                    LocalizationUtility::translate('flex-auto-detect', 'Er24Rechtstexte') . ': ' . $domainConfig->getDomain(), $domainConfig->getUid(), 'tcarecords-tx_er24rechtstexte_domain_model_domainconfig-default', // TODO
                ];
            }
        } catch (\Exception) {
        }

        return $config;

    }
}
