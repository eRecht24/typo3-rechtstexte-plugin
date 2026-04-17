<?php

use ERecht24\Er24Rechtstexte\Controller\DomainConfigController;
use TYPO3\CMS\Core\Information\Typo3Version;

$typo3MajorVersion = (new Typo3Version())->getMajorVersion();
$parentModule = $typo3MajorVersion >= 14 ? 'admin' : 'system';
$iconIdentifier = $typo3MajorVersion >= 14 ? 'ext-er24-rechtstexte-module-main' : 'ext-er24-rechtstexte-plugin-main';

return [
    'tools_Er24Rechtstexte' => [
        'parent' => $parentModule,
        'access' => 'admin',
        'workspaces' => 'live',
        'path' => '/module/tools/erecht24',
        'iconIdentifier' => $iconIdentifier,
        'labels' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_main.xlf',
        'extensionName' => 'Er24Rechtstexte',
        'controllerActions' => [
            DomainConfigController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete', 'performUpdate',
            ],
        ],
    ],
];
