<?php

$GLOBALS['SiteConfiguration']['site_language']['columns']['eRecht24Config'] = [
    'label' => 'eRecht24 Konfiguration',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'foreign_table' => 'tx_er24rechtstexte_domain_model_domainconfig',
        'items' => [
            ['','']
        ],
        'readOnly' => '1'
    ],
];

// And add it to showitem
$GLOBALS['SiteConfiguration']['site_language']['types']['1']['showitem'] .= ',eRecht24Config';
