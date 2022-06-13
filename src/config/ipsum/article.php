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
            //'categorie_parent_id'     => 4,
        ],
    ],
    'categories' => [
        'guard_id'=>[
        
        ]
    ],

    'etats' => [
        'publie' => 'Publié',
        'brouillon' => 'Brouillon'
    ],

];
