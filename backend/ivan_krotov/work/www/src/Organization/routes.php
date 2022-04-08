<?php
return [
    'post' => [
        'api/organization/add' => [
            'controller' => 'Organization',
            'method' => 'addNew'
        ]
    ],
    'get' => [
        'api/organization' => [
            'controller' => 'Organization',
            'method' => 'get'
        ],
        'api/organization/relations' => [
            'controller' => 'Organization',
            'method' => 'getRelations'
        ]
    ]
];