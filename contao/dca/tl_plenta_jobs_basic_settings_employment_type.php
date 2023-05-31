<?php

declare(strict_types=1);

/**
 * Plenta Jobs Basic Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

use Contao\DC_Table;
use Plenta\ContaoJobsBasic\EventListener\Contao\DCA\TlPlentaJobsBasicSettingsEmploymentType;

$GLOBALS['TL_DCA']['tl_plenta_jobs_basic_settings_employment_type'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'switchToEdit' => true,
        'markAsCopy' => 'title',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    'list' => [
        'sorting' => [
            'mode' => 1,
            'fields' => ['title'],
            'flag' => 1,
            'panelLayout' => 'filter;search,sort,limit',
        ],
        'label' => [
            'fields' => ['title'],
            'showColumns' => false,
        ],
        'global_operations' => [
            'back' => [
                'route' => 'Plenta\ContaoJobsBasic\Controller\Contao\BackendModule\SettingsController',
                'label' => &$GLOBALS['TL_LANG']['MSC']['backBT'],
                'icon' => 'back.svg',
            ],
            'all' => [
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\'))return false;Backend.getScrollOffset()"',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        'default' => '{settings_legend},title,google_for_jobs_mapping;translation',
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => [
                'type' => 'integer',
                'unsigned' => true,
                'autoincrement' => true,
            ],
        ],
        'tstamp' => [
            'sql' => [
                'type' => 'integer',
                'unsigned' => true,
                'default' => 0,
            ],
        ],
        'title' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => [
                'maxlength' => 255,
                'tl_class' => 'w50',
                'mandatory' => true,
            ],
            'sql' => [
                'type' => 'string',
                'length' => 255,
                'default' => '',
            ],
        ],
        'google_for_jobs_mapping' => [
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => [
                TlPlentaJobsBasicSettingsEmploymentType::class,
                'googleForJobsMappingOptionsCallback',
            ],
            'eval' => [
                'tl_class' => 'w50',
                'mandatory' => true,
            ],
            'sql' => [
                'type' => 'string',
                'length' => 32,
                'default' => 'OTHER',
            ],
        ],
        'translation' => [
            'inputType' => 'metaWizard',
            'eval' => [
                'class' => 'clr',
                'allowHtml' => true,
                'multiple' => true,
                'metaFields' => [
                    'title' => 'maxlength="255"',
                ],
            ],
            'load_callback' => [[
                TlPlentaJobsBasicSettingsEmploymentType::class,
                'translationLoadCallback',
            ]],
            'save_callback' => [[
                TlPlentaJobsBasicSettingsEmploymentType::class,
                'translationSaveCallback',
            ]],
            'sql' => 'JSON NULL default NULL',
        ],
    ],
];
