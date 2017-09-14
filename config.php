<?php

return[
    'database' => [
        'name' => 'DRS',
        'username' => '',
        'password' => '',
        'connection' => 'sqlsrv:server=localhost\SQLEXPRESS',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ]
];
