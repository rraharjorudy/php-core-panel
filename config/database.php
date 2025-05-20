<?php

return [
    'driver'   => $_ENV['DB_DRIVER']   ?? 'mysql',
    'host'     => $_ENV['DB_HOST']     ?? 'localhost',
    'database' => $_ENV['DB_DATABASE'] ?? 'db_skeleton_user',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset'  => $_ENV['DB_CHARSET']  ?? 'utf8mb4',
];
