<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

ExtensionUtility::registerPlugin(
    'Er24Rechtstexte',
    'Main',
    'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24_rechtstexte_main.name',
    'ext-er24-rechtstexte-plugin-main',
    'plugins',
    'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24_rechtstexte_main.description',
);

$pluginSignature = 'er24rechtstexte_main';
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', $pluginSignature, 'after:subheader');
ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:er24_rechtstexte/Configuration/Flexforms/FlexformMain.xml', $pluginSignature);
