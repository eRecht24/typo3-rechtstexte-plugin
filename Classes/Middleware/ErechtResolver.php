<?php

namespace ERecht24\Er24Rechtstexte\Middleware;

use ERecht24\Er24Rechtstexte\Api\LegalDocument;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository;
use ERecht24\Er24Rechtstexte\Utility\ApiUtility;
use ERecht24\Er24Rechtstexte\Utility\LogUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;


class ErechtResolver implements MiddlewareInterface
{
    public const SECRET_IDENTIFIER = 'erecht24_secret';

    public const TYPE_IDENTIFIER = 'erecht24_type';

    /**
     * @throws \JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!str_contains($request->getUri()->getPath(), '/erecht24/v1/push')) {
            return $handler->handle($request);
        }

        $secret = $request->getQueryParams()[self::SECRET_IDENTIFIER];
        $type = $request->getQueryParams()[self::TYPE_IDENTIFIER];

        if (is_null($secret)) {
            $jsonStr = $request->getBody()->getContents();
            $json = json_decode($jsonStr, true, flags: JSON_THROW_ON_ERROR);
            if (isset($json[self::SECRET_IDENTIFIER])) {
                $secret = $json[self::SECRET_IDENTIFIER];
            }

            if (isset($json[self::TYPE_IDENTIFIER])) {
                $type = $json[self::TYPE_IDENTIFIER];
            }
        }

        if ($type === 'ping') {
            return new JsonResponse(['message' => 'pong']);
        }

        if (in_array($type, LegalDocument::ALLOWED_DOCUMENT_TYPES) === false) {
            return new JsonResponse(['message' => 'Unknown Type'], 400);
        }

        // Use QueryBuilder instead of Extbase Repository to avoid TypoScript initialization requirement
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_er24rechtstexte_domain_model_domainconfig');
        
        $domainConfigRow = $queryBuilder
            ->select('*')
            ->from('tx_er24rechtstexte_domain_model_domainconfig')
            ->where(
                $queryBuilder->expr()->eq(
                    'client_secret',
                    $queryBuilder->createNamedParameter($secret)
                )
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($domainConfigRow === false) {
            LogUtility::writeErrorLog('Push for unknown Client Secret requested' . $secret);
            return new JsonResponse(['message' => 'Client Secret is unknown to the system'], 401);
        }

        // Reconstruct DomainConfig object from database row
        /** @var DomainConfig $domainConfig */
        $domainConfig = GeneralUtility::makeInstance(DomainConfig::class);
        $this->hydrateDomainConfig($domainConfig, $domainConfigRow);

        if ($type === 'imprint' && $domainConfig->getImprintSource() === 0
            || $type === 'privacyPolicy' && $domainConfig->getPrivacySource() === 0
            || $type === 'privacySocialMedia' && $domainConfig->getSocialSource() === 0) {
            return new JsonResponse(['message' => 'Document ' . $type . ' is handled locally'], 422);
        }

        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $apiUtility = new ApiUtility();

        $apiHandlerResult = $apiUtility->importDocument($domainConfig, $type, 'success');

        if (count($apiHandlerResult[0]) > 0) {
            $message = 'Something went wrong: ' . implode(', ', $apiHandlerResult[0]);
            return new JsonResponse(['message' => $message], 400);
        }

        if (count($apiHandlerResult[1]) > 0) {
            // Use repository for update operations as Extbase persistence is needed here
            $domainConfigRepository = GeneralUtility::makeInstance(DomainConfigRepository::class);
            $domainConfigRepository->update($domainConfig);
            $persistenceManager->persistAll();

            return new JsonResponse(['message' => 'Document ' . $type . ' has been stored']);
        }

        return new JsonResponse(['message' => 'Something unknown went wrong.'], 400);

    }

    /**
     * Hydrate a DomainConfig object from database row
     *
     * @param DomainConfig $domainConfig
     * @param array $row
     * @return void
     */
    private function hydrateDomainConfig(DomainConfig $domainConfig, array $row): void
    {
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
    }
}
