<?php

use ERecht24\Er24Rechtstexte\Controller\AjaxController;

return [
    'er24_changeSiteConfig' => [
        'path' => '/erecht24/changeSiteConfig',
        'target' => AjaxController::class . '::changeSiteConfigAction',
    ],
    'er24_syncAllDocuments' => [
        'path' => '/erecht24/syncAllDocuments',
        'target' => AjaxController::class . '::syncAllDocumentsAction',
    ],
    'er24_syncDocument' => [
        'path' => '/erecht24/syncDocument',
        'target' => AjaxController::class . '::syncDocumentAction',
    ],
    'er24_saveDomainConfig' => [
        'path' => '/erecht24/saveDomainConfig',
        'target' => AjaxController::class . '::saveDomainConfigAction',
    ],
    'er24_refreshConfig' => [
        'path' => '/erecht24/refreshConfig',
        'target' => AjaxController::class . '::refreshConnectionAction',
    ],
];
