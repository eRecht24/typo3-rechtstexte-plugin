<?php
return [
    'frontend' => [
        'erecht-resolver' => [
            'target' => \ERecht24\Er24Rechtstexte\Middleware\ErechtResolver::class,
            'before' => [
                'typo3/cms-frontend/content-length-headers',
                'typo3/cms-frontend/page-resolver'
            ],
        ]
    ]
];
