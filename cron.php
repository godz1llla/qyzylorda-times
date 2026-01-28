#!/usr/bin/env php
<?php

/**
 * Cron Job - Автообновление данных
 * 
 * Инструкция по установке:
 * 1. Сделать файл исполняемым: chmod +x cron.php
 * 2. Добавить в crontab: crontab -e
 * 
 * Примеры расписания:
 * Обновление погоды каждый час:
 * 0 * * * * /usr/bin/php /path/to/qyzylorda-times/cron.php weather
 * 
 * Обновление валют каждый день в 9:00:
 * 0 9 * * * /usr/bin/php /path/to/qyzylorda-times/cron.php currency
 * 
 * Обновление sitemap каждые 6 часов:
 * 0 STAR/6 * * * /usr/bin/php /path/to/qyzylorda-times/cron.php sitemap
 * 
 * Обновить всё (погода + валюты + sitemap):
 * 0 9 * * * /usr/bin/php /path/to/qyzylorda-times/cron.php all
 */

// Загрузка конфигурации и классов
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/app/services/WeatherService.php';
require_once __DIR__ . '/app/services/CurrencyService.php';
require_once __DIR__ . '/app/helpers/SitemapGenerator.php';

// Проверяем аргументы командной строки
$task = $argv[1] ?? 'all';

// Логирование
function logMessage($message)
{
    $timestamp = date('Y-m-d H:i:s');
    $logFile = __DIR__ . '/logs/cron.log';

    // Создаем директорию если не существует
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }

    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    echo "[$timestamp] $message\n";
}

// Обновление погоды
function updateWeather()
{
    try {
        $weatherService = new WeatherService();
        $result = $weatherService->refreshCache();

        if ($result) {
            logMessage('✅ Weather updated successfully');
            return true;
        } else {
            logMessage('⚠️ Weather update failed (API might be down or key missing)');
            return false;
        }
    } catch (Exception $e) {
        logMessage('❌ Weather update error: ' . $e->getMessage());
        return false;
    }
}

// Обновление валют
function updateCurrency()
{
    try {
        $currencyService = new CurrencyService();
        $result = $currencyService->refreshCache();

        if ($result) {
            logMessage('✅ Currency rates updated successfully');
            return true;
        } else {
            logMessage('⚠️ Currency update failed (API might be down)');
            return false;
        }
    } catch (Exception $e) {
        logMessage('❌ Currency update error: ' . $e->getMessage());
        return false;
    }
}

// Обновление sitemap
function updateSitemap()
{
    try {
        $generator = new SitemapGenerator();
        $result = $generator->generate();

        if ($result) {
            logMessage('✅ Sitemap generated successfully');
            return true;
        } else {
            logMessage('❌ Sitemap generation failed');
            return false;
        }
    } catch (Exception $e) {
        logMessage('❌ Sitemap generation error: ' . $e->getMessage());
        return false;
    }
}

// Выполнение задачи
logMessage("=== CRON JOB START: Task '$task' ===");

switch ($task) {
    case 'weather':
        updateWeather();
        break;

    case 'currency':
        updateCurrency();
        break;

    case 'sitemap':
        updateSitemap();
        break;

    case 'all':
        updateWeather();
        updateCurrency();
        updateSitemap();
        break;

    default:
        logMessage("❌ Unknown task: $task");
        logMessage("Available tasks: weather, currency, sitemap, all");
        exit(1);
}

logMessage("=== CRON JOB END ===\n");
exit(0);
