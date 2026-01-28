<?php
/**
 * Конфигурация базы данных
 * Qyzylorda Times News Portal
 */

return [
    'host' => 'localhost',
    'database' => 'qyzylorda_times',
    'username' => 'qyzylorda_user',
    'password' => 'qyzylorda_pass_2026',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
