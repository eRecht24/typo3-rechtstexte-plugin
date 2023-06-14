<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        $vendorPrefix = '';
        $typo3Version = new \TYPO3\CMS\Core\Information\Typo3Version();
        $backendController = [
            \ERecht24\Er24Rechtstexte\Controller\DomainConfigController::class => 'list, new, create, edit, update, delete, performUpdate',
        ];

        if(version_compare($typo3Version->getVersion(),'10.4', '<')) {
            $vendorPrefix = 'ERecht24.';
            $backendController = [
                'DomainConfig' => 'list, new, create, edit, update, delete, performUpdate',
            ];
        }

        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                $vendorPrefix.'Er24Rechtstexte',
                'tools', // Make module a submodule of 'tools'
                'main', // Submodule key
                '', // Position
                $backendController,
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:er24_rechtstexte/Resources/Public/Icons/Extension.svg',
                    'labels' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_main.xlf',
                ]
            );

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('@import \'EXT:er24_rechtstexte/Configuration/TypoScript/setup.typoscript\'');

        }

    }
);
