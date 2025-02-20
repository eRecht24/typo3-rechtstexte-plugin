<?php

use ERecht24\Er24Rechtstexte\Middleware\ErechtResolver;

return [
    'frontend' => [
        'erecht-resolver' => [
            'target' => ErechtResolver::class,
            'before' => [
                'typo3/cms-frontend/content-length-headers',
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
