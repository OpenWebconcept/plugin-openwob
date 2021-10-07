<?php declare(strict_types=1);

return [
    'base' => [
        'id'         => 'openwob_metadata',
        'title'      => __('Data', OW_LANGUAGE_DOMAIN),
        'post_types' => ['openwob-item'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'validation' => [
            'rules'  => [
                'wob_ID' => [
                    'required'  => true,
                ],
                'wob_Wobverzoek_informatie[wob_Status]' => [
                    'required'  => true
                ],
                'wob_Wobverzoek_informatie[wob_Tijdstip_laatste_wijziging][formatted]' => [
                    'required'  => true,
                ],
                'wob_Behandelend_bestuursorgaan' => [
                    'required'  => true,
                ],
                'wob_Titel' => [
                    'required'  => true,
                ],
                'wob_Ontvanger_informatieverzoek' => [
                    'required'         => true,
                ],
                'wob_Ontvangstdatum' => [
                    'required'  => true,
                ],
                'wob_Besluitdatum' => [
                    'required'  => true,
                ],
                'wob_Besluit' => [
                    'required'  => true,
                ],
                'wob_URL_informatieverzoek' => [
                    'required'  => true,
                ],
                'wob_URL_besluit' => [
                    'required'  => true,
                ],
            ],
        ],
        'fields'     => [
            [
                'name' => __('ID', OW_LANGUAGE_DOMAIN),
                'id'   => 'wob_ID',
                'type' => 'text',
            ],
            [
                'name' => __('wob_Titel', OW_LANGUAGE_DOMAIN),
                'id'   => 'wob_Titel',
                'type' => 'text',
            ],
            [
                'name'              => __('Wobverzoek informatie', OW_LANGUAGE_DOMAIN),
                'id'                => 'wob_Wobverzoek_informatie',
                'type'              => 'group',
                'clone_as_multiple' => true,
                'fields'            => [
                    [
                        'name'             => __('Status', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Status',
                        'type'             => 'select',
                        'options'          => [
                            ''           => '',
                            'Nieuw'      => 'Nieuw',
                            'Gewijzigd'  => 'Gewijzigd',
                            'Verwijderd' => 'Verwijderd'
                        ],
                    ],
                    [
                        'name'             => __('Tijdstip laatste wijziging', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Tijdstip_laatste_wijziging',
                        'type'             => 'datetime',
                        'timestamp'        => true,
                        'js_options'       => [
                            'dateFormat'       => 'dd-mm-yy',
                            'timeFormat'       => 'HH:mm',
                            'showTimepicker'   => true,
                            'controlType'      => 'select',
                            'showButtonPanel'  => false,
                            'oneLine'          => true,
                        ],
                        'inline'     => false,
                    ]
                ]
            ],
            [
                'name'             => __('Volgnummer', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Volgnummer',
                'type'             => 'text',
            ],
            [
                'name'             => __('Behandelend_bestuursorgaan', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Behandelend_bestuursorgaan',
                'type'             => 'text',
            ],
            [
                'name'             => __('Verzoeker', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Verzoeker',
                'type'             => 'select',
                'options'          => [
                    ''                                      => '',
                    'Rechtspersoon'                         => 'Rechtspersoon',
                    'Rechtsvertegenwoordiger (gemachtigde)' => 'Rechtsvertegenwoordiger (gemachtigde)',
                    'Natuurlijk persoon'                    => 'Natuurlijk persoon'
                ]
            ],
            [
                'name'             => __('Ontvanger_informatieverzoek', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Ontvanger_informatieverzoek',
                'type'             => 'text',
            ],
            [
                'name'             => __('Termijnoverschrijding', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Termijnoverschrijding',
                'type'             => 'select',
                'options'          => [
                    ''                       => '',
                    'Ja'                     => 'Ja',
                    'Nee'                    => 'Nee'
                ]
            ],
            [
                'name'             => __('Behandelstatus', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Behandelstatus',
                'type'             => 'select',
                'options'          => [
                    ''                                      => '',
                    'Rechtspersoon'                         => 'Rechtspersoon',
                    'Rechtsvertegenwoordiger (gemachtigde)' => 'Rechtsvertegenwoordiger (gemachtigde)',
                    'Natuurlijk persoon'                    => 'Natuurlijk persoon'
                ]
            ],
            [
                'name'             => __('Ontvangstdatum', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Ontvangstdatum',
                'type'             => 'date',
                'js_options'       => [
                    'dateFormat'       => 'dd-mm-yy',
                    'timeFormat'       => 'HH:mm',
                    'showTimepicker'   => true,
                    'controlType'      => 'select',
                    'showButtonPanel'  => false,
                    'oneLine'          => true,
                ],
                'inline'     => false
            ],
            [
                'name'             => __('Besluitdatum', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Besluitdatum',
                'type'             => 'date',
                'js_options'       => [
                    'dateFormat'       => 'dd-mm-yy',
                    'timeFormat'       => 'HH:mm',
                    'showTimepicker'   => true,
                    'controlType'      => 'select',
                    'showButtonPanel'  => false,
                    'oneLine'          => true,
                ],
                'inline'     => false
            ],
            [
                'name'             => __('Besluit', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Besluit',
                'type'             => 'select',
                'options'          => [
                    ''                       => '',
                    'Openbaar gemaakt'       => 'Openbaar gemaakt',
                    'Niet openbaar gemaakt'  => 'Niet openbaar gemaakt',
                    'Deels openbaar gemaakt' => 'Deels openbaar gemaakt',
                    'Reeds openbaar'         => 'Reeds openbaar'
                ],
            ],
            [
                'name'             => __('URL_informatieverzoek', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_URL_informatieverzoek',
                'type'             => 'url',
            ],
            [
                'name'             => __('URL_inventarisatielijst', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_URL_inventarisatielijst',
                'type'             => 'url',
            ],
            [
                'name'             => __('URL_besluit', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_URL_besluit',
                'type'             => 'url',
            ],
            [
                'name'             => __('Postcodegebied', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Postcodegebied',
                'type'             => 'text',
            ],
            [
                'name'             => __('BAG_ID', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_BAG_ID',
                'type'             => 'text',
            ],
            [
                'name'             => __('BGT_ID', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_BGT_ID',
                'type'             => 'text',
            ],
            [
                'name'              => __('Themas', OW_LANGUAGE_DOMAIN),
                'id'                => 'wob_Themas',
                'type'              => 'group',
                'clone'             => 'true',
                'fields'            => [
                    [
                        'name'             => __('Hoofdthema', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Hoofdthema',
                        'type'             => 'select',
                        'required'         => true,
                        'options'          => [
                            ''                           => '',
                            'Cultuur en recreatie'       => 'Cultuur en recreatie',
                        ],
                    ],
                    [
                        'name'             => __('Subthema', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Subthema',
                        'type'             => 'select',
                        'options'          => [
                            ''                       => '',
                            'Recreatie'              => 'Recreatie',
                        ],
                    ],
                    [
                        'name'             => __('Aanvullend_thema', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Aanvullend_thema',
                        'type'             => 'select',
                        'options'          => [
                            ''      => '',
                            'Sport' => 'Sport',
                        ],
                    ],
                ]
            ],
            [
                'name'             => __('Geografisch_gebied', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Geografisch_gebied',
                'type'             => 'text',
            ],
            [
                'name'             => __('Geografische_positie', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Geografische_positie',
                'type'             => 'group',
                'fields'           => [
                    [
                        'name'             => __('Longitude', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Longitude',
                        'type'             => 'text',
                    ],
                    [
                        'name'             => __('Lattitude', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Lattitude',
                        'type'             => 'text',
                    ]
                ]
            ],
            [
                'name'             => __('COORDS', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_COORDS',
                'type'             => 'group',
                'fields'           => [
                    [
                        'name'             => __('X', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_X',
                        'type'             => 'text',
                    ],
                    [
                        'name'             => __('Y', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Y',
                        'type'             => 'text',
                    ]
                ]
            ],
            [
                'name'             => __('Bijlagen', OW_LANGUAGE_DOMAIN),
                'id'               => 'wob_Bijlagen',
                'type'             => 'group',
                'clone'            => true,
                'fields'           => [
                    [
                        'name'             => __('Type_Bijlage', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Type_Bijlage',
                        'type'             => 'select',
                        'options'          => [
                            ''            => '',
                            'Procedureel' => 'Procedureel',
                            'Inhoudelijk' => 'Inhoudelijk',
                        ]
                    ],
                    [
                        'name'             => __('Status_Bijlage', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Status_Bijlage',
                        'type'             => 'select',
                        'options'          => [
                            ''           => '',
                            'Nieuw'      => 'Nieuw',
                            'Gewijzigd'  => 'Gewijzigd',
                            'Verwijderd' => 'Verwijderd',
                        ]
                    ],
                    [
                        'name'             => __('Tijdstip_laatste_wijziging_bijlage', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Tijdstip_laatste_wijziging_bijlage',
                        'type'             => 'datetime',
                        'timestamp'        => true,
                        'js_options'       => [
                            'dateFormat'       => 'dd-mm-yy',
                            'timeFormat'       => 'HH:mm',
                            'showTimepicker'   => true,
                            'controlType'      => 'select',
                            'showButtonPanel'  => false,
                            'oneLine'          => true,
                        ],
                        'inline'     => false
                    ],
                    [
                        'name'             => __('Titel_Bijlage', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_Titel_Bijlage',
                        'type'             => 'text',
                    ],
                    [
                        'name'             => __('URL_Bijlage', OW_LANGUAGE_DOMAIN),
                        'id'               => 'wob_URL_Bijlage',
                        'type'             => 'text',
                    ],
                ]
            ],
        ],
    ]
];
