<?php

return [

    'types' => [
        'page' => [
            'nom' => 'Pages',
            'has_categorie' => false
        ],
        'post' => [
            'nom' => 'Post',
            'has_categorie' => true,
            //'categorie_parent_id' => 4,
        ],
    ],

    'etats' => [
        'publie' => 'Publié',
        'brouillon' => 'Brouillon'
    ],

    /*'custom_fields' => [
        [
            'name' => 'champ',
            'label' => 'Nom champ',
            'description' => '',
            'defaut' => '',
            'type' => 'input',
            'rules' => 'nullable|string'
        ]
    ]*/

];
