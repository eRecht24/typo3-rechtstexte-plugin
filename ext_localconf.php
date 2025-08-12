<?php

use ERecht24\Er24Rechtstexte\Controller\DomainConfigController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

(static function () {
    ExtensionUtility::configurePlugin(
        'Er24Rechtstexte',
        'Main',
        [
            DomainConfigController::class => 'show',
        ],
        // non-cacheable actions
        [
            DomainConfigController::class => '',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionManagementUtility::addTypoScript(
        'er24_rechtstexte',
        'constants',
        "@import 'EXT:er24_rechtstexte/Configuration/TypoScript/constants.typoscript'"
    );

    ExtensionManagementUtility::addTypoScript(
        'er24_rechtstexte',
        'setup',
        "@import 'EXT:er24_rechtstexte/Configuration/TypoScript/setup.typoscript'"
    );

    ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24_rechtstexte_main.name',
            'er24rechtstexte_main',
            'ext-er24-rechtstexte-plugin-main'
        ],
        'CType',
        'er24_rechtstexte'
    );
})();
