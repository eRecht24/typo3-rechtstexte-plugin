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
    'er24_syncImprint' => [
        'path' => '/erecht24/syncImprint',
        'target' => \ERecht24\Er24Rechtstexte\Controller\AjaxController::class . '::syncImprintAction',
    ],
];
