<?php

namespace ERecht24\Er24Rechtstexte\Middleware;

use ERecht24\Er24Rechtstexte\Api\LegalDocument;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use ERecht24\Er24Rechtstexte\Utility\ApiUtility;
use ERecht24\Er24Rechtstexte\Utility\LogUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ErechtResolver implements MiddlewareInterface
{
    private const TABLE_NAME = 'tx_er24rechtstexte_domain_model_domainconfig';

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

        $domainConfig = $this->findDomainConfigBySecret($secret);

        if ($domainConfig === null) {
            LogUtility::writeErrorLog('Push for unknown Client Secret requested' . $secret);
            return new JsonResponse(['message' => 'Client Secret is unknown to the system'], 401);
        }

        if ($type === 'imprint' && $domainConfig->getImprintSource() === 0
            || $type === 'privacyPolicy' && $domainConfig->getPrivacySource() === 0
            || $type === 'privacySocialMedia' && $domainConfig->getSocialSource() === 0) {
            return new JsonResponse(['message' => 'Document ' . $type . ' is handled locally'], 422);
        }

        $apiUtility = new ApiUtility();

        $apiHandlerResult = $apiUtility->importDocument($domainConfig, $type, 'success');

        if (count($apiHandlerResult[0]) > 0) {
            $message = 'Something went wrong: ' . implode(', ', $apiHandlerResult[0]);
            return new JsonResponse(['message' => $message], 400);
        }

        if (count($apiHandlerResult[1]) > 0) {
            $this->persistDocument($domainConfig, $type);

            return new JsonResponse(['message' => 'Document ' . $type . ' has been stored']);
        }

        return new JsonResponse(['message' => 'Something unknown went wrong.'], 400);

    }

    private function findDomainConfigBySecret(?string $secret): ?DomainConfig
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE_NAME);
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select(
                'uid',
                'pid',
                'domain',
                'api_key',
                'imprint_source',
                'privacy_source',
                'social_source',
                'client_id',
                'client_secret'
            )
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq(
                    'client_secret',
                    $queryBuilder->createNamedParameter($secret)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        if (!is_array($record)) {
            return null;
        }

        $domainConfig = GeneralUtility::makeInstance(DomainConfig::class);
        $domainConfig->_setProperty('uid', (int)$record['uid']);
        $domainConfig->setPid((int)$record['pid']);
        $domainConfig->setDomain((string)$record['domain']);
        $domainConfig->setApiKey((string)$record['api_key']);
        $domainConfig->setImprintSource((int)$record['imprint_source']);
        $domainConfig->setPrivacySource((int)$record['privacy_source']);
        $domainConfig->setSocialSource((int)$record['social_source']);
        $domainConfig->setClientId((string)$record['client_id']);
        $domainConfig->setClientSecret((string)$record['client_secret']);

        return $domainConfig;
    }

    private function persistDocument(DomainConfig $domainConfig, string $type): void
    {
        $data = match ($type) {
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

        if ($data === []) {
            return;
        }

        GeneralUtility::makeInstance(CacheManager::class)
            ->flushCachesByTag('er24_document_' . $domainConfig->getUid());

        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable(self::TABLE_NAME)
            ->update(
                self::TABLE_NAME,
                $data,
                ['uid' => $domainConfig->getUid()]
            );
    }
}
