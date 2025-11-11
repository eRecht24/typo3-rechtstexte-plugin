<?php

declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Service;

use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for accessing DomainConfig entities without Extbase/TypoScript initialization.
 * This is needed in contexts like middleware where full Extbase framework is not available.
 */
class DomainConfigService
{
    private const TABLE_NAME = 'tx_er24rechtstexte_domain_model_domainconfig';

    /**
     * Find domain config by client secret
     *
     * @param string $secret The client secret to search for
     * @return DomainConfig|null The domain config or null if not found
     */
    public function findByClientSecret(string $secret): ?DomainConfig
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE_NAME);

        $row = $queryBuilder
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq(
                    'client_secret',
                    $queryBuilder->createNamedParameter($secret)
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return $this->hydrateFromRow($row);
    }

    /**
     * Hydrate a DomainConfig object from database row
     *
     * @param array $row Database row
     * @return DomainConfig Hydrated domain config object
     */
    private function hydrateFromRow(array $row): DomainConfig
    {
        $domainConfig = GeneralUtility::makeInstance(DomainConfig::class);
        $domainConfig->_setProperty('uid', (int)$row['uid']);
        $domainConfig->_setProperty('pid', (int)$row['pid']);
        $domainConfig->setDomain($row['domain'] ?? '');
        $domainConfig->setApiKey($row['api_key'] ?? '');
        $domainConfig->setImprintSource((int)($row['imprint_source'] ?? 1));
        $domainConfig->setSiteLanguage((int)($row['site_language'] ?? 0));
        $domainConfig->setClientId($row['client_id'] ?? '');
        $domainConfig->setClientSecret($row['client_secret'] ?? '');
        $domainConfig->setImprintDe($row['imprint_de'] ?? '');
        $domainConfig->setImprintDeLocal($row['imprint_de_local'] ?? '');
        $domainConfig->setImprintDeTstamp((int)($row['imprint_de_tstamp'] ?? 0));
        $domainConfig->setImprintEn($row['imprint_en'] ?? null);
        $domainConfig->setImprintEnLocal($row['imprint_en_local'] ?? null);
        $domainConfig->setImprintEnTstamp((int)($row['imprint_en_tstamp'] ?? 0));
        $domainConfig->setPrivacySource((int)($row['privacy_source'] ?? 1));
        $domainConfig->setPrivacyDe($row['privacy_de'] ?? '');
        $domainConfig->setPrivacyDeLocal($row['privacy_de_local'] ?? '');
        $domainConfig->setPrivacyDeTstamp((int)($row['privacy_de_tstamp'] ?? 0));
        $domainConfig->setPrivacyEn($row['privacy_en'] ?? null);
        $domainConfig->setPrivacyEnLocal($row['privacy_en_local'] ?? null);
        $domainConfig->setPrivacyEnTstamp((int)($row['privacy_en_tstamp'] ?? 0));
        $domainConfig->setSocialSource((int)($row['social_source'] ?? 1));
        $domainConfig->setSocialDe($row['social_de'] ?? '');
        $domainConfig->setSocialDeLocal($row['social_de_local'] ?? '');
        $domainConfig->setSocialDeTstamp((int)($row['social_de_tstamp'] ?? 0));
        $domainConfig->setSocialEn($row['social_en'] ?? null);
        $domainConfig->setSocialEnLocal($row['social_en_local'] ?? null);
        $domainConfig->setSocialEnTstamp((int)($row['social_en_tstamp'] ?? 0));
        $domainConfig->setAnalyticsId($row['analytics_id'] ?? '');
        $domainConfig->setFlagEmbedTracking((bool)($row['flag_embed_tracking'] ?? false));
        $domainConfig->setFlagUserCentricsEmbed((bool)($row['flag_user_centrics_embed'] ?? false));
        $domainConfig->setFlagOptOutCode((bool)($row['flag_opt_out_code'] ?? false));
        $domainConfig->setRootPid((int)($row['root_pid'] ?? 0));
        $domainConfig->setSiteConfigName($row['site_config_name'] ?? '');

        return $domainConfig;
    }
}
