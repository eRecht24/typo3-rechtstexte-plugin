<?php

namespace ERecht24\Er24Rechtstexte\Utility;

use ERecht24\Er24Rechtstexte\Api\ApiResponse;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class HelperUtility
{
    public const PLUGIN_NAME = 'er24_rechtstexte';

    public const PLUGIN_VERSION = '3.0.2';

    public const PLUGIN_TEXT_DOMAIN = 'erecht24';

    public const API_HOST_URL = 'https://api.e-recht24.de';

    public const REST_NAMESPACE = 'erecht24/v1';

    /**
     * Function provides best fitting api message
     */
    public static function getBestFittingApiErrorMessage(
        ?ApiResponse $apiResponse = null,
        string $default = ''
    ): string {
        if (isset($GLOBALS['BE_USER']->uc['lang'])
            && $GLOBALS['BE_USER']->uc['lang'] === 'de'
            && $apiResponse instanceof ApiResponse
            && $apiResponse->getData('message_de')) {
            $error_message = $apiResponse->getData('message_de');
        } elseif ($apiResponse instanceof ApiResponse && $apiResponse->getData('message')) {
            $error_message = $apiResponse->getData('message');
        } elseif ($default !== '' && $default !== '0') {
            $error_message = $default;
        } else {
            // @todo
            $error_message = 'An Error occurred, Please try again later. If the error persists contact the admin.';
        }

        LogUtility::writeErrorLog('API Error:' . $error_message);

        return $error_message;
    }

    public static function assignDocumentToDomainConfig(
        ApiResponse $apiResponse,
        DomainConfig $domainConfig,
        string $documentType
    ): DomainConfig {
        $contentEn = $apiResponse->getData('html_en');
        $contentDe = $apiResponse->getData('html_de');
        $modified =  strtotime((string)$apiResponse->getData('modified'));

        switch ($documentType) {
            case 'imprint':
                $domainConfig->setImprintDe($contentDe);
                $domainConfig->setImprintEn($contentEn);
                $domainConfig->setImprintDeTstamp($modified);
                $domainConfig->setImprintEnTstamp($modified);
                break;
            case 'privacyPolicy':
                $domainConfig->setPrivacyDe($contentDe);
                $domainConfig->setPrivacyEn($contentEn);
                $domainConfig->setPrivacyDeTstamp($modified);
                $domainConfig->setPrivacyEnTstamp($modified);
                break;
            case 'privacyPolicySocialMedia':
                $domainConfig->setSocialDe($contentDe);
                $domainConfig->setSocialEn($contentEn);
                $domainConfig->setSocialDeTstamp($modified);
                $domainConfig->setSocialEnTstamp($modified);
        }

        /** @var CacheManager $cacheManager */
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->flushCachesByTag('er24_document_' . $domainConfig->getUid());

        return $domainConfig;
    }

    public static function removeDocument(DomainConfig $domainConfig, string $documentType): DomainConfig
    {
        switch ($documentType) {
            case 'imprint':
                $domainConfig->setImprintDe('');
                $domainConfig->setImprintEn('');
                break;
            case 'privacyPolicy':
                $domainConfig->setPrivacyDe('');
                $domainConfig->setPrivacyEn('');
                break;
            case 'privacyPolicySocialMedia':
                $domainConfig->setSocialDe('');
                $domainConfig->setSocialEn('');
        }

        return $domainConfig;
    }

}
