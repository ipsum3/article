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
            /*'publication' => [

            ],*/
            'has_extrait' => true,
            'has_texte' => true,
        ],
        /*'example' => [
            'conditions' => [
                'article_types' => ['solution']
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
                'type' => 'XXXXXX'
            ],
            'medias' => [
                ['groupe' => ''],
                ['groupe' => 'XXXXXXXXX']
            ],
            'is_guarded' => false,
            'publication' => [

            ],
            'hide_extrait' => true,
            'hide_texte' => true,
        ],*/
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
        ]
    ],

    'etats' => [
        'publie' => 'Publié',
        'brouillon' => 'Brouillon'
    ],

    'translatable_attributes_adds' => [],
];
