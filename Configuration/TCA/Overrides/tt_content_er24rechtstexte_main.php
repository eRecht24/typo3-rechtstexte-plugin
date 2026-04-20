<?php

use TYPO3\CMS\Core\Information\Typo3Version;

defined('TYPO3') || die();

$typo3MajorVersion = (new Typo3Version())->getMajorVersion();


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24_rechtstexte_main.name',
        'value' => 'er24rechtstexte_main',
        'icon' => 'ext-er24-rechtstexte-plugin-main'
    ]
);

$GLOBALS['TCA']['tt_content']['types']['er24rechtstexte_main'] = [
    'showitem' => '--palette--;;general, --palette--;;headers, pi_flexform, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, --palette--;;hidden, --palette--;;access, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
    'columnsOverrides' => [
        'pi_flexform' => [
            'config' => [
                'ds' => [
                    'default' => 'FILE:EXT:er24_rechtstexte/Configuration/Flexforms/FlexformMain.xml',
                ],
            ],
        ],
    ],
];

// <=13
if ($typo3MajorVersion <= 13) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Configuration,pi_flexform,',
        'er24rechtstexte_main',
        'after:subheader'
    );
} else {
    // >=14
    $GLOBALS['TCA']['tt_content']['types']['er24rechtstexte_main']['columnsOverrides']['pi_flexform']['config']['ds']
        = 'FILE:EXT:er24_rechtstexte/Configuration/Flexforms/FlexformMain.xml';
}

