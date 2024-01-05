<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'auto_ecoles' => 'c,r,u,d',
        ],
        'gerant' => [
            'users' => 'c,r,u,d',
            'auto_ecoles' => 'r,u',
            'work_process' => 'c,r,u,d'
        ],
        'moniteur' => [
            'profile' => 'r,u',
            'auto_ecoles' => 'r',
        ],
        'condidat' => [
            'auto_ecoles' => 'r',
            'work_process' => 'r'
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
