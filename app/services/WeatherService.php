<?php

/**
 * WeatherService - Сервис для получения погоды через OpenWeatherMap API
 */

class WeatherService
{
    private $db;
    private $apiKey;
    private $city = 'Kyzylorda';
    private $cacheTime = 3600; // 1 час в секундах

    public function __construct()
    {
        $this->db = Database::getInstance();
        // API ключ из конфига или переменной окружения
        $this->apiKey = $_ENV['OPENWEATHER_API_KEY'] ?? 'YOUR_API_KEY_HERE';
    }

    /**
     * Получить текущую погоду (с кешированием)
     */
    public function getCurrentWeather()
    {
        // Проверяем кеш
        $cached = $this->getCachedWeather();
        if ($cached) {
            return $cached;
        }

        // Запрашиваем свежие данные
        $weather = $this->fetchWeatherFromAPI();

        if ($weather) {
            $this->cacheWeather($weather);
            return $weather;
        }

        // Fallback - возвращаем последние кешированные данные
        return $this->getLastCachedWeather();
    }

    /**
     * Получить погоду из кеша (если не истек срок)
     */
    private function getCachedWeather()
    {
        $sql = "SELECT * FROM weather_cache 
                WHERE expires_at > NOW()
                ORDER BY cached_at DESC 
                LIMIT 1";

        $result = $this->db->fetchOne($sql);

        if ($result) {
            return [
                'temperature' => $result['temperature'],
                'description' => $result['condition'],
                'icon' => $result['icon'],
                'updated_at' => $result['cached_at']
            ];
        }

        return null;
    }

    /**
     * Получить последние кешированные данные (для fallback)
     */
    private function getLastCachedWeather()
    {
        $sql = "SELECT * FROM weather_cache 
                ORDER BY cached_at DESC 
                LIMIT 1";

        $result = $this->db->fetchOne($sql);

        if ($result) {
            return [
                'temperature' => $result['temperature'],
                'description' => $result['condition'],
                'icon' => $result['icon'],
                'updated_at' => $result['cached_at']
            ];
        }

        // Если вообще нет данных
        return [
            'temperature' => -8.5,
            'description' => 'N/A',
            'icon' => '01d',
            'updated_at' => null
        ];
    }

    /**
     * Запросить погоду из API
     */
    private function fetchWeatherFromAPI()
    {
        if ($this->apiKey === 'YOUR_API_KEY_HERE') {
            return null; // API ключ не настроен
        }

        $url = sprintf(
            'https://api.openweathermap.org/data/2.5/weather?q=%s&appid=%s&units=metric&lang=ru',
            urlencode($this->city),
            $this->apiKey
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        $data = json_decode($response, true);

        if (!isset($data['main']['temp'])) {
            return null;
        }

        return [
            'temperature' => round($data['main']['temp'], 1),
            'description' => $data['weather'][0]['description'] ?? 'N/A',
            'icon' => $data['weather'][0]['icon'] ?? '01d'
        ];
    }

    /**
     * Сохранить погоду в кеш
     */
    private function cacheWeather($weather)
    {
        // Удаляем старые записи (старше 7 дней)
        $this->db->execute(
            "DELETE FROM weather_cache WHERE cached_at < DATE_SUB(NOW(), INTERVAL 7 DAY)"
        );

        // Вставляем новые данные
        $sql = "INSERT INTO weather_cache (temperature, `condition`, icon, cached_at, expires_at) 
                VALUES (:temperature, :condition, :icon, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))";

        $this->db->execute($sql, [
            ':temperature' => $weather['temperature'],
            ':condition' => $weather['description'],
            ':icon' => $weather['icon']
        ]);
    }

    /**
     * Принудительно обновить кеш погоды
     */
    public function refreshCache()
    {
        $weather = $this->fetchWeatherFromAPI();
        if ($weather) {
            $this->cacheWeather($weather);
            return true;
        }
        return false;
    }
}
