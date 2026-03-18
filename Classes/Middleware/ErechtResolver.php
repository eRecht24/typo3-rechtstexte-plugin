<?php

declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Middleware;

use ERecht24\Er24Rechtstexte\Api\LegalDocument;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use ERecht24\Er24Rechtstexte\Service\DomainConfigPushStorage;
use ERecht24\Er24Rechtstexte\Utility\ApiUtility;
use ERecht24\Er24Rechtstexte\Utility\LogUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;

final class ErechtResolver implements MiddlewareInterface
{
    public const SECRET_IDENTIFIER = 'erecht24_secret';

    public const TYPE_IDENTIFIER = 'erecht24_type';

    public function __construct(
        private readonly DomainConfigPushStorage $domainConfigPushStorage,
        private readonly ApiUtility $apiUtility,
    ) {}

    /**
     * @throws \JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!str_contains($request->getUri()->getPath(), '/erecht24/v1/push')) {
            return $handler->handle($request);
        }

        $secret = $request->getQueryParams()[self::SECRET_IDENTIFIER] ?? null;
        $type = $request->getQueryParams()[self::TYPE_IDENTIFIER] ?? null;

        if ($secret === null || $type === null) {
            $jsonStr = $request->getBody()->getContents();
            if (trim($jsonStr) !== '') {
                $json = json_decode($jsonStr, true, flags: JSON_THROW_ON_ERROR);
                $secret ??= $json[self::SECRET_IDENTIFIER] ?? null;
                $type ??= $json[self::TYPE_IDENTIFIER] ?? null;
            }
        }

        if ($secret === null || $type === null) {
            return new JsonResponse(['message' => 'Missing client secret or document type'], 400);
        }

        $type = $this->normalizeDocumentType((string)$type);

        if ($type === 'ping') {
            return new JsonResponse(['message' => 'pong']);
        }

        if (!in_array($type, LegalDocument::ALLOWED_DOCUMENT_TYPES, true)) {
            return new JsonResponse(['message' => 'Unknown Type'], 400);
        }

        /** @var DomainConfig|null $domainConfig */
        $domainConfig = $this->domainConfigPushStorage->findOneByClientSecret((string)$secret);

        if ($domainConfig === null) {
            LogUtility::writeErrorLog('Push for unknown Client Secret requested' . $secret);
            return new JsonResponse(['message' => 'Client Secret is unknown to the system'], 401);
        }

        if (
            ($type === 'imprint' && $domainConfig->getImprintSource() === 0)
            || ($type === 'privacyPolicy' && $domainConfig->getPrivacySource() === 0)
            || ($type === 'privacyPolicySocialMedia' && $domainConfig->getSocialSource() === 0)
        ) {
            return new JsonResponse(['message' => 'Document ' . $type . ' is handled locally'], 422);
        }

        $apiHandlerResult = $this->apiUtility->importDocument($domainConfig, $type, 'success');

        if (count($apiHandlerResult[0]) > 0) {
            $message = 'Something went wrong: ' . implode(', ', $apiHandlerResult[0]);
            return new JsonResponse(['message' => $message], 400);
        }

        if (count($apiHandlerResult[1]) > 0) {
            $this->domainConfigPushStorage->updateImportedDocument($domainConfig, $type);

            return new JsonResponse(['message' => 'Document ' . $type . ' has been stored']);
        }

        return new JsonResponse(['message' => 'Something unknown went wrong.'], 400);
    }

    private function normalizeDocumentType(string $type): string
    {
        return match ($type) {
            'privacySocialMedia' => 'privacyPolicySocialMedia',
            default => $type,
        };
    }
}
