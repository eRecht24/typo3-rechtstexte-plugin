<?php
defined('TYPO3_MODE') or die();

$pluginSignature = 'er24rechtstexte_main';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:er24_rechtstexte/Configuration/Flexforms/FlexformMain.xml');
