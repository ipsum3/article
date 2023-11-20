<?php

return [

    'types' => [
        'page',
        'post',
        /*'XXXXXXXX'*/
    ],

    'groupes' => [
        'default' => [
            'conditions' => [

            ],
            'categorie' => false,
            'medias' => [
                ['groupe' => '']
            ],
            'custom_fields' => [],
            'custom_blocs' => [],
            'is_guarded' => false,
            'publication' => [
                'has_date' => true,
                'has_etat' => true,
                'required_date' => false,
            ],
            'has_extrait' => true,
            'has_texte' => true,
        ],

        'page' => [
            'conditions' => [
                'article_types' => ['page']
            ],
            'is_guarded' => true
        ],

        'post' => [
            'conditions' => [
                'article_types' => ['post']
            ],
            'categorie' => [
                'type' => 'post'
            ]
        ],

        // EXAMPLE DE CONFIG
        // Nom du groupe (non visible)
        /*'example' => [
            // Conditions pour affecter ce groupe à un article (type/nom)
            'conditions' => [
                'article_types' => ['solution'],
                'article_noms' => ['Accueil']
            ],
            'custom_fields' => [
                [
                    'name' => 'url',
                    'label' => 'champXXXX',
                    'description' => '',
                    'defaut' => '',
                    'type' => 'url',
                    'rules' => 'nullable|url',
                ],
                [
                    'name' => 'categorie',
                    'label' => 'Catégorie',
                    'description' => '',
                    'defaut' => '',
                    'model' => Ipsum\Article\app\Models\Categorie::class,
                    'filtre' => [
                        [
                            'method' => 'orderBy',
                            'args' => ['id', 'desc'],
                        ]
                    ],
                    'type' => 'relation',
                    'rules' => 'nullable',
                ],
            ],
            'custom_blocs' => [
                [
                    'name' => 'texte',
                    'label' => 'Texte',
                    'fields' => [
                        [
                            'name' => 'texte_custom',
                            'label' => 'Texte 1',
                            'description' => '',
                            'defaut' => '',
                            'type' => 'input',
                            'rules' => 'nullable',
                        ],
                        [
                            'name' => 'texte_custom_bis',
                            'label' => 'Texte 2',
                            'description' => '',
                            'defaut' => '',
                            'type' => 'html',
                            'rules' => 'nullable',
                        ],
                    ],
                ],
                [
                    'name' => 'repeater',
                    'label' => 'Répétiteur',
                    'fields' => [
                        [
                            'name' => 'repeater_field',
                            'label' => 'Repeater Field',
                            'description' => 'This is a repeater field for demonstration purposes.',
                            'type' => 'repeater',
                            'rules' => 'nullable',
                            'fields' => [
                                [
                                    'name' => 'sub_field_1',
                                    'label' => 'Sub Field 1',
                                    'description' => '',
                                    'defaut' => '',
                                    'type' => 'input',
                                    'rules' => 'nullable',
                                ],
                                [
                                    'name' => 'sub_field_2',
                                    'label' => 'Sub Field 2',
                                    'description' => '',
                                    'defaut' => '',
                                    'type' => 'input',
                                    'rules' => 'nullable',
                                ],
                                [
                                    'name' => 'sub_field_3',
                                    'label' => 'Sub Field 2',
                                    'description' => '',
                                    'defaut' => '',
                                    'type' => 'input',
                                    'rules' => 'nullable',
                                ],
                                // Ajoutez d'autres sous-champs au besoin
                            ],
                        ],
                    ],
                ],
            ],
            'categorie' => [
                // Nom du type de catégorie à utiliser
                'type' => 'XXXXXX'
            ],
            'medias' => [
                ['groupe' => ''],
                // Pour afficher plusieurs modules de média
                ['groupe' => 'XXXXXXXXX']
            ],
            // Protège de la supression
            'is_guarded' => false,
            // Gestion de l'affichage de la date et l'état dans publication
            'publication' => [
                'has_date' => true,
                'has_etat' => true,
            ],
            'has_extrait' => true,
            'has_texte' => true,
        ],*/
    ],

    'etats' => [
        'publie' => 'Publié',
        'brouillon' => 'Brouillon'
    ],

    'translatable_attributes_adds' => [],
];
