<?php

return [
    'types' => [
        'post',
    ],

    'groupes' => [
        'default' => [
            'conditions' => [],
            'is_guarded' => false,
        ],
        /*'exampleXXXX' => [
            'conditions' => [
                'categorie_ids' => [X, XX, XXX],
                'categorie_types' => [XXXXXX],
            ],
            'is_guarded' => true,
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
        ],*/
    ],

    'translatable_attributes_adds' => [],
];
