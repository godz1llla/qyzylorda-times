<?php
/**
 * Qyzylorda Times - Application Entry Point
 * Точка входа приложения
 */

// Установка часового пояса
date_default_timezone_set('Asia/Qyzylorda');

// Включаем отображение ошибок (отключить в продакшене)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключаем autoloader для классов
spl_autoload_register(function ($className) {
    // Заменяем namespace разделитель на разделитель директорий
    $className = str_replace('\\', '/', $className);

    // DEBUG (удалить в продакшене)
    // error_log("Autoloader: trying to load class: $className");

    // Пути для поиска классов
    $paths = [
        __DIR__ . '/../core/' . $className . '.php',
        __DIR__ . '/../app/models/' . $className . '.php',
        __DIR__ . '/../app/controllers/' . $className . '.php',
        __DIR__ . '/../app/helpers/' . $className . '.php',
        __DIR__ . '/../app/services/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            // error_log("Autoloader: loaded from: $path");
            return;
        }
    }

    // error_log("Autoloader: class not found: $className");
});

// Запускаем сессию
session_start();

// Загружаем конфигурацию
$config = require __DIR__ . '/../config/app.php';

// Инициализируем роутер
$router = new Router();

// Получаем информацию о маршруте
$route = $router->dispatch();

// Извлекаем данные маршрута
$controllerName = $route['controller'];
$method = $route['method'];
$params = $route['params'];

// Проверяем, существует ли контроллер
if (!class_exists($controllerName)) {
    // 404
    http_response_code(404);
    echo "<!DOCTYPE html>
    <html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>404 - Страница не найдена</title>
        <script src='https://cdn.tailwindcss.com'></script>
    </head>
    <body class='bg-gray-100 flex items-center justify-center min-h-screen'>
        <div class='text-center'>
            <h1 class='text-6xl font-bold text-gray-800 mb-4'>404</h1>
            <p class='text-xl text-gray-600 mb-8'>Страница не найдена</p>
            <a href='/' class='bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded'>На главную</a>
        </div>
    </body>
    </html>";
    exit;
}

// Создаем экземпляр контроллера
$controller = new $controllerName();

// Проверяем, существует ли метод
if (!method_exists($controller, $method)) {
    die("Метод {$method} не найден в контроллере {$controllerName}");
}

// Вызываем метод контроллера с параметрами
call_user_func_array([$controller, $method], $params);
