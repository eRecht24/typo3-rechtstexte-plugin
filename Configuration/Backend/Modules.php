<?php

use ERecht24\Er24Rechtstexte\Controller\DomainConfigController;
use TYPO3\CMS\Core\Information\Typo3Version;

$typo3MajorVersion = (new Typo3Version())->getMajorVersion();
$access = $typo3MajorVersion >= 14 ? 'admin' : 'user,group';

return [
    'tools_Er24Rechtstexte' => [
        'parent' => 'system',
        'access' => $access,
        'workspaces' => 'live',
        'path' => '/module/tools/erecht24',
        'iconIdentifier' => 'ext-er24-rechtstexte-plugin-main',
        'labels' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_main.xlf',
        'extensionName' => 'Er24Rechtstexte',
        'controllerActions' => [
            DomainConfigController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete', 'performUpdate',
            ],
        ],
    ],
];
