<?php
defined('TYPO3') || die();

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
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'er24rechtstexte_main',
    'FILE:EXT:er24_rechtstexte/Configuration/Flexforms/FlexformMain.xml'
);

