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
            && $apiResponse->getData()['message_de']) {
            $error_message = $apiResponse->getData()['message_de'];
        } elseif ($apiResponse && $apiResponse->getData()['message']) {
            $error_message = $apiResponse->getData()['message'];
        } elseif ($default) {
            $error_message = $default;
        } else {
            // @todo
            $error_message = 'An Error occurred, Please try again later. If the error persists contact the admin.';
        }

        //self::erecht24_log_error($error_message);

        return $error_message;
    }

}
