<?php

$GLOBALS['SiteConfiguration']['site']['columns']['eRecht24Config'] = [
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
$GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= ',--div--;eRecht24, eRecht24Config';
