<?php
defined('TYPO3_MODE') or die();

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

call_user_func(function () {
    /**
     * Extension key
     */
    $extensionKey = 'er24_rechtstexte';

    /**
     * Add default TypoScript (constants and setup)
     */
    ExtensionManagementUtility::addStaticFile($extensionKey, 'Configuration/TypoScript', 'eRecht24 Rechtstexte Extension');
});
