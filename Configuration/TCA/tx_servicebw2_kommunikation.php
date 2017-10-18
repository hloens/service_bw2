<?php
$localizationFile = 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xml:tx_servicebw2_kommunikation.';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:service_bw2/Resources/Private/Language/locallang_db.xlf:tx_servicebw2_kommunikation',
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'hideTable' => false,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'id,kennung,kennungszusatz,zusatz',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('service_bw2') . 'Resources/Public/Icons/tx_servicebw2_kommunikation.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,id,kanal,reihenfolge,kennung,kennungszusatz,zusatz,gueltigkeit,oeffentlich',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_servicebw2_kommunikation',
                'foreign_table_where' => 'AND tx_servicebw2_kommunikation.pid=###CURRENT_PID### AND tx_servicebw2_kommunikation.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'id' => [
            'exclude' => 1,
            'label' => $localizationFile . 'id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int,trim'
            ],
        ],
        'kanal' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kanal',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'reihenfolge' => [
            'exclude' => 1,
            'label' => $localizationFile . 'reihenfolge',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,int'
            ],
        ],
        'kennung' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kennung',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kennungszusatz' => [
            'exclude' => 1,
            'label' => $localizationFile . 'kennungszusatz',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'zusatz' => [
            'exclude' => 1,
            'label' => $localizationFile . 'zusatz',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'gueltigkeit' => [
            'exclude' => 1,
            'label' => $localizationFile . 'gueltigkeit',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_servicebw2_gueltigkeit'
            ],
        ],
        'oeffentlich' => [
            'exclude' => 1,
            'label' => $localizationFile . 'oeffentlich',
            'config' => [
                'type' => 'check',
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'id,kanal,reihenfolge,kennung,kennungszusatz,zusatz,gueltigkeit,oeffentlich']
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];
