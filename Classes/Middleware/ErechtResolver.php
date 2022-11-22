<?php


namespace ERecht24\Er24Rechtstexte\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class ErechtResolver implements \Psr\Http\Server\MiddlewareInterface
{

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {


        if(false === strpos($request->getUri()->getPath(), '/erecht24/v1/push')) {
            return $handler->handle($request);
        }

        $secret = $request->getQueryParams()['erecht24_secret'];
        $type = $request->getQueryParams()['erecht24_type'];

        if($type === 'ping') {
            return new \TYPO3\CMS\Core\Http\JsonResponse(['message' => 'pong']);
        }

        if(false === in_array($type,\ERecht24\Er24Rechtstexte\Api\LegalDocument::ALLOWED_DOCUMENT_TYPES)) {
            return new \TYPO3\CMS\Core\Http\JsonResponse(['message' => 'Unknown Type'], 400);
        }

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        /** @var \ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository $domainConfigRepository */
        $domainConfigRepository = $objectManager->get(\ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository::class);
        /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig */
        $domainConfig = $domainConfigRepository->findOneByClientSecret($secret);

        if($domainConfig === null) {
            \ERecht24\Er24Rechtstexte\Utility\LogUtility::writeErrorLog('Push for unknown Client Secret requested' . $secret);
            return new \TYPO3\CMS\Core\Http\JsonResponse(['message' => 'Client Secret is unknown to the system'],401);
        }

        if($type === 'imprint' && $domainConfig->getImprintSource() === 0
            || $type === 'privacyPolicy' && $domainConfig->getPrivacySource() === 0
            || $type === 'privacySocialMedia' && $domainConfig->getSocialSource() === 0) {
            return new \TYPO3\CMS\Core\Http\JsonResponse(['message' => 'Document ' . $type . ' is handled locally'],422);
        }

        $persistenceManager = $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
        $apiUtility = new \ERecht24\Er24Rechtstexte\Utility\ApiUtility();

        $apiHandlerResult = $apiUtility->importDocument($domainConfig, $type, 'success');

        if(count($apiHandlerResult[0]) > 0) {
            $message = 'Something went wrong: ' . implode(', ', $apiHandlerResult[0]);
            return new \TYPO3\CMS\Core\Http\JsonResponse(['message' => $message],400);
        } else if(count($apiHandlerResult[1]) > 0) {

            $domainConfigRepository->update($domainConfig);
            $persistenceManager->persistAll();


            return new \TYPO3\CMS\Core\Http\JsonResponse(['message' => 'Document ' . $type . ' has been stored']);
        } else {
            return new \TYPO3\CMS\Core\Http\JsonResponse(['message' => 'Something unknown went wrong.'],400);
        }
    }
}
