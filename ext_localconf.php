<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        $typo3Version = new \TYPO3\CMS\Core\Information\Typo3Version();

        if(version_compare($typo3Version->getVersion(),'10.4', '<')) {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
                'ERecht24.Er24Rechtstexte',
                'Main',
                [
                    'DomainConfig' => 'show'
                ],
                // non-cacheable actions
                [
                    'DomainConfig' => ''
                ]
            );
        } else {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
                'Er24Rechtstexte',
                'Main',
                [
                    \ERecht24\Er24Rechtstexte\Controller\DomainConfigController::class => 'show'
                ],
                // non-cacheable actions
                [
                    \ERecht24\Er24Rechtstexte\Controller\DomainConfigController::class => ''
                ]
            );
        }


        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        main {
                            iconIdentifier = er24_rechtstexte-plugin-main
                            title = LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24_rechtstexte_main.name
                            description = LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24_rechtstexte_main.description
                            tt_content_defValues {
                                CType = list
                                list_type = er24rechtstexte_main
                            }
                        }
                    }
                    show = *
                }
           }'
        );

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $iconRegistry->registerIcon(
            'er24_rechtstexte-plugin-main',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:er24_rechtstexte/Resources/Public/Icons/Extension.svg']
        );

        $iconRegistry->registerIcon(
            'fa-cog',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'cog']
        );

        $iconRegistry->registerIcon(
            'fa-bullhorn',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'bullhorn']
        );

        $iconRegistry->registerIcon(
            'fa-user',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'user']
        );

        $iconRegistry->registerIcon(
            'fa-facebook-square',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'facebook-square']
        );

        $iconRegistry->registerIcon(
            'fa-google',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'google']
        );

        $iconRegistry->registerIcon(
            'fa-question-circle',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'question-circle']
        );

        $iconRegistry->registerIcon(
            'fa-check-circle',
            \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
            ['name' => 'check-circle']
        );

    }
);
