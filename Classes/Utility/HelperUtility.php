<?php

namespace ERecht24\Er24Rechtstexte\Utility;

class HelperUtility
{

    const PLUGIN_NAME = 'er24_rechtstexte';

    const PLUGIN_VERSION = '1.0.0';

    const PLUGIN_TEXT_DOMAIN = 'erecht24';

    const API_HOST_URL = 'https://sandbox.api.e-recht24.de';

    const REST_NAMESPACE = 'erecht24/v1';

    /**
     * Function provides best fitting api message
     *
     * @param \ERecht24\Er24Rechtstexte\Api\ApiResponse|null $apiResponse
     * @param string $default
     * @return string
     */
    public static function getBestFittingApiErrorMessage(
        ?\ERecht24\Er24Rechtstexte\Api\ApiResponse $apiResponse = null,
        string $default = ''
    ): string
    {
        if ($GLOBALS['BE_USER']->uc['lang'] === 'de'
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

        return $error_message;
    }


    /**
     * @param \ERecht24\Er24Rechtstexte\Api\ApiResponse $apiResponse
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @param string $documentType
     * @return \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig
     */
    public static function assignDocumentToDomainConfig(\ERecht24\Er24Rechtstexte\Api\ApiResponse $apiResponse,
                                                 \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig,
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

        return $domainConfig;
    }

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @param string $documentType
     * @return \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig
     */
    public static function removeDocument(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig, string $documentType)
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
