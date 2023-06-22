<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        $vendorPrefix = '';
        $backendController = [
            \ERecht24\Er24Rechtstexte\Controller\DomainConfigController::class => 'list, new, create, edit, update, delete, performUpdate',
        ];

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
    }
);
