<?php
defined('TYPO3') or die();

$vendorPrefix = '';
$typo3Version = new \TYPO3\CMS\Core\Information\Typo3Version();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $vendorPrefix.'Er24Rechtstexte',
    'Main',
    'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang.xlf:plugin-title',
    'ext-er24-rechtstexte-plugin-main'
);

$pluginSignature = 'er24rechtstexte_main';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:er24_rechtstexte/Configuration/Flexforms/FlexformMain.xml');
