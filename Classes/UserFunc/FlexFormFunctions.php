<?php

namespace ERecht24\Er24Rechtstexte\UserFunc;

use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class FlexFormFunctions
{
    /**
     * @param array $config
     */
    public function filterDomainConfigs(array $config)
    {

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        try {
            $siteConfig = $siteFinder->getSiteByPageId($config['flexParentDatabaseRow']['pid']);
            $domainConfig = GeneralUtility::makeInstance(DomainConfigRepository::class)
                ->findOneByDomain((string)$siteConfig->getBase());
            if ($domainConfig instanceof DomainConfig) {
                $config['items'] = [];
                $config['items'][] = [
                    LocalizationUtility::translate('flex-auto-detect', 'er24_rechtstexte') . ': ' . $domainConfig->getDomain(), $domainConfig->getUid(), 'tcarecords-tx_er24rechtstexte_domain_model_domainconfig-default' // TODO
                ];
            }
        } catch (\Exception $e) {
        }

        return $config;

    }
}
