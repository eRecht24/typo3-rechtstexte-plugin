<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function()
    {
        $typo3Version = new \TYPO3\CMS\Core\Information\Typo3Version();

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

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        main {
                            iconIdentifier = ext-er24-rechtstexte-plugin-main
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

        if(version_compare($typo3Version->getVersion(),'12.1', '<')) {
            $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
            $iconRegistry->registerIcon(
                'ext-er24-rechtstexte-plugin-main',
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:er24_rechtstexte/Resources/Public/Icons/Extension.svg']
            );
        }
    }
);
