<?php

use ERecht24\Er24Rechtstexte\Controller\DomainConfigController;

return [
    'tools_Er24Rechtstexte' => [
        'parent' => 'tools',
        'position' => ['after' => 'tools_ExtensionmanagerExtensionmanager'],
        'access' => 'user,group',
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
