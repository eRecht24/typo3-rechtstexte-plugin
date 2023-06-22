<?php

namespace ERecht24\Er24Rechtstexte\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use ERecht24\Er24Rechtstexte\Api\LegalDocument;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use ERecht24\Er24Rechtstexte\Utility\LogUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use ERecht24\Er24Rechtstexte\Utility\ApiUtility;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class ErechtResolver implements MiddlewareInterface
{
    const SECRET_IDENTIFIER = 'erecht24_secret';
    const TYPE_IDENTIFIER = 'erecht24_type';


    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (false === strpos($request->getUri()->getPath(), '/erecht24/v1/push')) {
            return $handler->handle($request);
        }

        $secret = $request->getQueryParams()[self::SECRET_IDENTIFIER];
        $type = $request->getQueryParams()[self::TYPE_IDENTIFIER];

        if (is_null($secret)) {
            $jsonStr = $request->getBody()->getContents();
            $json = \GuzzleHttp\json_decode($jsonStr, true);
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

        if (false === in_array($type, LegalDocument::ALLOWED_DOCUMENT_TYPES)) {
            return new JsonResponse(['message' => 'Unknown Type'], 400);
        }

        /** @var DomainConfigRepository $domainConfigRepository */
        $domainConfigRepository = GeneralUtility::makeInstance(DomainConfigRepository::class);
        /** @var DomainConfig $domainConfig */
        $domainConfig = $domainConfigRepository->findOneByClientSecret($secret);

        if ($domainConfig === null) {
            LogUtility::writeErrorLog('Push for unknown Client Secret requested' . $secret);
            return new JsonResponse(['message' => 'Client Secret is unknown to the system'], 401);
        }

        if ($type === 'imprint' && $domainConfig->getImprintSource() === 0
            || $type === 'privacyPolicy' && $domainConfig->getPrivacySource() === 0
            || $type === 'privacySocialMedia' && $domainConfig->getSocialSource() === 0) {
            return new JsonResponse(['message' => 'Document ' . $type . ' is handled locally'], 422);
        }

        $persistenceManager = GeneralUtitlity::makeInstance(PersistenceManager::class);
        $apiUtility = new ApiUtility();

        $apiHandlerResult = $apiUtility->importDocument($domainConfig, $type, 'success');

        if (count($apiHandlerResult[0]) > 0) {
            $message = 'Something went wrong: ' . implode(', ', $apiHandlerResult[0]);
            return new JsonResponse(['message' => $message], 400);
        } else if (count($apiHandlerResult[1]) > 0) {
            $domainConfigRepository->update($domainConfig);
            $persistenceManager->persistAll();

            return new JsonResponse(['message' => 'Document ' . $type . ' has been stored']);
        } else {
            return new JsonResponse(['message' => 'Something unknown went wrong.'], 400);
        }
    }
}
