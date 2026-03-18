<?php

declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Service;

use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use TYPO3\CMS\Core\Database\ConnectionPool;

final class DomainConfigPushStorage
{
    private const TABLE_NAME = 'tx_er24rechtstexte_domain_model_domainconfig';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {}

    public function findOneByClientSecret(string $clientSecret): ?DomainConfig
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_NAME);
        $row = $queryBuilder
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq(
                    'client_secret',
                    $queryBuilder->createNamedParameter($clientSecret)
                )
            )
            ->orderBy('uid', 'ASC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return $this->mapRowToDomainConfig($row);
    }

    public function updateImportedDocument(DomainConfig $domainConfig, string $documentType): void
    {
        $data = match ($documentType) {
            'imprint' => [
                'imprint_de' => $domainConfig->getImprintDe(),
                'imprint_en' => $domainConfig->getImprintEn(),
                'imprint_de_tstamp' => $domainConfig->getImprintDeTstamp(),
                'imprint_en_tstamp' => $domainConfig->getImprintEnTstamp(),
            ],
            'privacyPolicy' => [
                'privacy_de' => $domainConfig->getPrivacyDe(),
                'privacy_en' => $domainConfig->getPrivacyEn(),
                'privacy_de_tstamp' => $domainConfig->getPrivacyDeTstamp(),
                'privacy_en_tstamp' => $domainConfig->getPrivacyEnTstamp(),
            ],
            'privacyPolicySocialMedia' => [
                'social_de' => $domainConfig->getSocialDe(),
                'social_en' => $domainConfig->getSocialEn(),
                'social_de_tstamp' => $domainConfig->getSocialDeTstamp(),
                'social_en_tstamp' => $domainConfig->getSocialEnTstamp(),
            ],
            default => [],
        };

        if ($data === [] || $domainConfig->getUid() === null) {
            return;
        }

        $data['tstamp'] = time();

        $this->connectionPool
            ->getConnectionForTable(self::TABLE_NAME)
            ->update(
                self::TABLE_NAME,
                $data,
                ['uid' => $domainConfig->getUid()]
            );
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToDomainConfig(array $row): DomainConfig
    {
        $domainConfig = new DomainConfig();
        $domainConfig->_setProperty('uid', isset($row['uid']) ? (int)$row['uid'] : null);
        $domainConfig->_setProperty('pid', isset($row['pid']) ? (int)$row['pid'] : 0);
        $domainConfig->setDomain((string)($row['domain'] ?? ''));
        $domainConfig->setApiKey((string)($row['api_key'] ?? ''));
        $domainConfig->setImprintSource((int)($row['imprint_source'] ?? 0));
        $domainConfig->setPrivacySource((int)($row['privacy_source'] ?? 0));
        $domainConfig->setSocialSource((int)($row['social_source'] ?? 0));
        $domainConfig->setImprintDe((string)($row['imprint_de'] ?? ''));
        $domainConfig->setImprintEn($this->nullableString($row['imprint_en'] ?? null));
        $domainConfig->setImprintDeTstamp((int)($row['imprint_de_tstamp'] ?? 0));
        $domainConfig->setImprintEnTstamp((int)($row['imprint_en_tstamp'] ?? 0));
        $domainConfig->setPrivacyDe((string)($row['privacy_de'] ?? ''));
        $domainConfig->setPrivacyEn($this->nullableString($row['privacy_en'] ?? null));
        $domainConfig->setPrivacyDeTstamp((int)($row['privacy_de_tstamp'] ?? 0));
        $domainConfig->setPrivacyEnTstamp((int)($row['privacy_en_tstamp'] ?? 0));
        $domainConfig->setSocialDe((string)($row['social_de'] ?? ''));
        $domainConfig->setSocialEn($this->nullableString($row['social_en'] ?? null));
        $domainConfig->setSocialDeTstamp((int)($row['social_de_tstamp'] ?? 0));
        $domainConfig->setSocialEnTstamp((int)($row['social_en_tstamp'] ?? 0));
        $domainConfig->setClientId((string)($row['client_id'] ?? ''));
        $domainConfig->setClientSecret((string)($row['client_secret'] ?? ''));

        return $domainConfig;
    }

    private function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return (string)$value;
    }
}
