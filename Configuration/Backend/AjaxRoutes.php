<?php

return [
    'er24_changeSiteConfig' => [
        'path' => '/erecht24/changeSiteConfig',
        'target' => \ERecht24\Er24Rechtstexte\Controller\AjaxController::class . '::changeSiteConfigAction',
    ],
];
