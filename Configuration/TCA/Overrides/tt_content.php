<?php
defined('TYPO3_MODE') or die();

$vendorPrefix = '';
$typo3Version = new \TYPO3\CMS\Core\Information\Typo3Version();

if(version_compare($typo3Version->getVersion(),'10.4', '<')) {
    $vendorPrefix = 'ERecht24.';
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $vendorPrefix.'Er24Rechtstexte',
    'Main',
    'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang.xlf:plugin-title'
);

$pluginSignature = 'er24rechtstexte_main';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:er24_rechtstexte/Configuration/Flexforms/FlexformMain.xml');
