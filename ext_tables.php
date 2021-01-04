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

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $vendorPrefix.'Er24Rechtstexte',
            'Main',
            'eRecht24 Impressum oder Datenschutzerklärung auf dieser Seite einfügen.' // TODO
        );

        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                $vendorPrefix.'Er24Rechtstexte',
                'tools', // Make module a submodule of 'tools'
                'main', // Submodule key
                '', // Position
                $backendController,
                [
                    'access' => 'user,group',
                    'icon'   => 'EXT:er24_rechtstexte/Resources/Public/Icons/Extension.png',
                    'labels' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_main.xlf',
                ]
            );

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('@import \'EXT:er24_rechtstexte/Configuration/TypoScript/setup.typoscript\'');

        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('er24_rechtstexte', 'Configuration/TypoScript', 'eRecht24 Rechtstexte Extension');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_er24rechtstexte_domain_model_domainconfig', 'EXT:er24_rechtstexte/Resources/Private/Language/locallang_csh_tx_er24rechtstexte_domain_model_domainconfig.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_er24rechtstexte_domain_model_domainconfig');

    }
);
