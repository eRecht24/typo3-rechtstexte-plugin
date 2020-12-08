<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig',
        'label' => 'domain',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'rootLevel' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'domain,api_key,imprint_de,imprint_en,privacy_de,privacy_en,social_de,social_en,analytics_id,site_config_name',
        'iconfile' => 'EXT:er24_rechtstexte/Resources/Public/Icons/tx_er24rechtstexte_domain_model_domainconfig.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, domain, api_key, imprint_source, imprint_de, imprint_de_tstamp, imprint_en, imprint_en_tstamp, privacy_source, privacy_de, privacy_de_tstamp, privacy_en, privacy_en_tstamp, social_source, social_de, social_de_tstamp, social_en, social_en_tstamp, analytics_id, flag_embed_tracking, flag_user_centrics_embed, flag_opt_out_code, root_pid, site_config_name',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, domain, api_key, imprint_source, imprint_de, imprint_de_tstamp, imprint_en, imprint_en_tstamp, privacy_source, privacy_de, privacy_de_tstamp, privacy_en, privacy_en_tstamp, social_source, social_de, social_de_tstamp, social_en, social_en_tstamp, analytics_id, flag_embed_tracking, flag_user_centrics_embed, flag_opt_out_code, root_pid, site_config_name'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_er24rechtstexte_domain_model_domainconfig',
                'foreign_table_where' => 'AND {#tx_er24rechtstexte_domain_model_domainconfig}.{#pid}=###CURRENT_PID### AND {#tx_er24rechtstexte_domain_model_domainconfig}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],

        'domain' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.domain',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'api_key' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.api_key',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'imprint_source' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.imprint_source',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'imprint_de' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.imprint_de',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'imprint_de_tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.imprint_de_tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'imprint_en' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.imprint_en',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'imprint_en_tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.imprint_en_tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'privacy_source' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.privacy_source',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'privacy_de' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.privacy_de',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'privacy_de_tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.privacy_de_tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'privacy_en' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.privacy_en',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'privacy_en_tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.privacy_en_tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'social_source' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.social_source',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'social_de' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.social_de',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'social_de_tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.social_de_tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'social_en' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.social_en',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'social_en_tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.social_en_tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'analytics_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.analytics_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'flag_embed_tracking' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.flag_embed_tracking',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'flag_user_centrics_embed' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.flag_user_centrics_embed',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'flag_opt_out_code' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.flag_opt_out_code',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'root_pid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.root_pid',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'site_config_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:er24_rechtstexte/Resources/Private/Language/locallang_db.xlf:tx_er24rechtstexte_domain_model_domainconfig.site_config_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],

    ],
];
