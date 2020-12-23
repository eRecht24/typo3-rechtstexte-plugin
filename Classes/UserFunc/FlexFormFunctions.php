<?php
namespace ERecht24\Er24Rechtstexte\UserFunc;

class FlexFormFunctions
{
    /**
     * @param array $config
     */
    public function filterDomainConfigs(array $config) {

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Site\SiteFinder::class);

        try {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
            $siteConfig = $siteFinder->getSiteByPageId($config['flexParentDatabaseRow']['pid']);
            $domainConfig = $objectManager->get(\ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository::class)
                ->findOneByDomain((string)$siteConfig->getBase());
            if($domainConfig instanceof \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig) {
                $config['items'] = [];
                $config['items'][] = [
                  'Automatisch ermittelt: ' . $domainConfig->getDomain(), $domainConfig->getUid(), 'tcarecords-tx_er24rechtstexte_domain_model_domainconfig-default' // TODO
                ];
            }
        } catch(\Exception $e) {}

        return $config;

    }
}
