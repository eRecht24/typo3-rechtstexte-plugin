<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('er24_rechtstexte', 'Configuration/TypoScript', 'eRecht24 Rechtstexte Extension');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('@import \'EXT:er24_rechtstexte/Configuration/TypoScript/setup.typoscript\'');
