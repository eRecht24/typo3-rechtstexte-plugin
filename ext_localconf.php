<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Er24Rechtstexte',
            'Main',
            [
                \ERecht24\Er24Rechtstexte\Controller\DomainConfigController::class => 'list, show, new, create, edit, update, delete'
            ],
            // non-cacheable actions
            [
                \ERecht24\Er24Rechtstexte\Controller\DomainConfigController::class => 'create, update, delete'
            ]
        );

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
				['source' => 'EXT:er24_rechtstexte/Resources/Public/Icons/user_plugin_main.svg']
			);
		
    }
);
