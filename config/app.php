<?php
/**
 * Конфигурация приложения
 * Qyzylorda Times News Portal
 */

return [
    'name' => 'Qyzylorda Times',
    'url' => 'http://qyzylordatimes.kz',
    'default_lang' => 'kz',
    'supported_langs' => ['kz', 'ru'],
    'brand_color' => '#D60023',
    'timezone' => 'Asia/Qyzylorda',
    'pagination' => 15,

    // Настройки изображений
    'image_sizes' => [
        'large' => [800, 600],
        'medium' => [400, 300],
        'thumbnail' => [100, 100]
    ],

    // API ключи (заполнить при необходимости)
    'openweather_api_key' => '', // Получить на https://openweathermap.org/api
    'weather_city' => 'Kyzylorda,KZ',
    'weather_cache_hours' => 1,
    'currency_cache_hours' => 24,

    // Безопасность
    'session_lifetime' => 7200, // 2 часа
    'admin_path' => 'admin',

    // Email настройки (для уведомлений)
    'contact_email' => 'editor@qyzylordatimes.kz',
    'admin_email' => 'admin@qyzylordatimes.kz',
];
