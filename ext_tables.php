<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Er24Rechtstexte',
            'Main',
            'eRecht24 Impressum oder Datenschutzerklärung auf dieser Seite einfügen.' // TODO
        );

        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'Er24Rechtstexte',
                'tools', // Make module a submodule of 'tools'
                'main', // Submodule key
                '', // Position
                [
                    \ERecht24\Er24Rechtstexte\Controller\DomainConfigController::class => 'list, show, new, create, edit, update, delete',
                ],
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
