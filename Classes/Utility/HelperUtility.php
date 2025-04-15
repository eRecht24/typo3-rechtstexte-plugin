<?php

namespace ERecht24\Er24Rechtstexte\Utility;

use ERecht24\Er24Rechtstexte\Api\ApiResponse;
use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Cache\CacheManager;

class HelperUtility
{

    const PLUGIN_NAME = 'er24_rechtstexte';

    const PLUGIN_VERSION = '2.0.4';

    const PLUGIN_TEXT_DOMAIN = 'erecht24';

    const API_HOST_URL = 'https://api.e-recht24.de';

    const REST_NAMESPACE = 'erecht24/v1';

    /**
     * Function provides best fitting api message
     *
     * @param ApiResponse|null $apiResponse
     * @param string $default
     * @return string
     */
    public static function getBestFittingApiErrorMessage(
        ?ApiResponse $apiResponse = null,
        string $default = ''
    ): string
    {
        if (isset($GLOBALS['BE_USER']->uc['lang'])
            && $GLOBALS['BE_USER']->uc['lang'] === 'de'
            && $apiResponse
            && $apiResponse->getData('message_de')) {
            $error_message = $apiResponse->getData('message_de');
        } elseif ($apiResponse && $apiResponse->getData('message')) {
            $error_message = $apiResponse->getData('message');
        } elseif ($default) {
            $error_message = $default;
        } else {
            // @todo
            $error_message = 'An Error occurred, Please try again later. If the error persists contact the admin.';
        }

        LogUtility::writeErrorLog('API Error:' . $error_message);

        return $error_message;
    }


    /**
     * @param ApiResponse $apiResponse
     * @param DomainConfig $domainConfig
     * @param string $documentType
     * @return DomainConfig
     */
    public static function assignDocumentToDomainConfig(ApiResponse $apiResponse,
                                                 DomainConfig $domainConfig,
                                                 string $documentType) {
        $contentEn = $apiResponse->getData('html_en');
        $contentDe = $apiResponse->getData('html_de');
        $modified =  strtotime($apiResponse->getData('modified'));

        switch($documentType) {
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

    /**
     * @param DomainConfig $domainConfig
     * @param string $documentType
     * @return DomainConfig
     */
    public static function removeDocument(DomainConfig $domainConfig, string $documentType)
    {
        switch($documentType) {
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
