<?php

return [
    'er24_changeSiteConfig' => [
        'path' => '/erecht24/changeSiteConfig',
        'target' => \ERecht24\Er24Rechtstexte\Controller\AjaxController::class . '::changeSiteConfigAction',
    ],
    'er24_syncAllDocuments' => [
        'path' => '/erecht24/syncAllDocuments',
        'target' => \ERecht24\Er24Rechtstexte\Controller\AjaxController::class . '::syncAllDocumentsAction',
    ],
    'er24_syncDocument' => [
        'path' => '/erecht24/syncDocument',
        'target' => \ERecht24\Er24Rechtstexte\Controller\AjaxController::class . '::syncDocumentAction',
    ],
    'er24_saveDomainConfig' => [
        'path' => '/erecht24/saveDomainConfig',
        'target' => \ERecht24\Er24Rechtstexte\Controller\AjaxController::class . '::saveDomainConfigAction',
    ],
];
