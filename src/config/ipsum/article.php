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
            'is_guarded' => false,
            'publication' => [
                'has_date' => true,
                'has_etat' => true,
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

        /* EXAMPLE DE CONFIG
        // Nom du groupe (non visible)
        'example' => [
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
                ]
            ],
            'categorie' => [
                // Nom du type de catégorie à utiliser
                'type' => 'XXXXXX'
            ],
            'medias' => [
                ['groupe' => ''],
                // Pour afficher plusieurs module de média
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
