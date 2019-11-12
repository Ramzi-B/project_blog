<?php

/**
 * Database configuration
 ******************************************************************************/

return [
    'database' => [
        'dsn' => 'mysql:host=localhost',
        'name' => 'project_blog',
        'username' => 'root',
        'password' => 'rboxer',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ]
];
