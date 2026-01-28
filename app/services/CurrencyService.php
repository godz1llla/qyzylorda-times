<?php

/**
 * CurrencyService - Сервис для получения курсов валют из Национального Банка РК
 */

class CurrencyService
{
    private $db;
    private $apiUrl = 'https://www.nationalbank.kz/rss/get_rates.cfm?fdate=';
    private $cacheTime = 86400; // 24 часа в секундах

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Получить курсы валют (с кешированием)
     */
    public function getRates()
    {
        // Проверяем кеш
        $cached = $this->getCachedRates();
        if ($cached) {
            return $cached;
        }

        // Запрашиваем свежие данные
        $rates = $this->fetchRatesFromAPI();

        if ($rates) {
            $this->cacheRates($rates);
            return $rates;
        }

        // Fallback - возвращаем последние кешированные данные
        return $this->getLastCachedRates();
    }

    /**
     * Получить курсы из кеша (если не истек срок)
     */
    private function getCachedRates()
    {
        $sql = "SELECT * FROM currency_cache 
                WHERE expires_at > NOW()
                ORDER BY cached_at DESC 
                LIMIT 1";

        $result = $this->db->fetchOne($sql);

        if ($result) {
            return [
                'usd_rate' => $result['usd_rate'],
                'eur_rate' => $result['eur_rate'],
                'rub_rate' => $result['rub_rate'],
                'updated_at' => $result['cached_at']
            ];
        }

        return null;
    }

    /**
     * Получить последние кешированные данные (для fallback)
     */
    private function getLastCachedRates()
    {
        $sql = "SELECT * FROM currency_cache 
                ORDER BY cached_at DESC 
                LIMIT 1";

        $result = $this->db->fetchOne($sql);

        if ($result) {
            return [
                'usd_rate' => $result['usd_rate'],
                'eur_rate' => $result['eur_rate'],
                'rub_rate' => $result['rub_rate'],
                'updated_at' => $result['cached_at']
            ];
        }

        // Если вообще нет данных - возвращаем дефолтные значения
        return [
            'usd_rate' => 501.50,
            'eur_rate' => 545.20,
            'rub_rate' => 5.40,
            'updated_at' => null
        ];
    }

    /**
     * Запросить курсы из API Нацбанка РК
     */
    private function fetchRatesFromAPI()
    {
        // Текущая дата в формате DD.MM.YYYY
        $date = date('d.m.Y');
        $url = $this->apiUrl . $date;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        return $this->parseXML($response);
    }

    /**
     * Парсинг XML данных от Нацбанка
     */
    private function parseXML($xml)
    {
        try {
            $data = simplexml_load_string($xml);

            if (!$data) {
                return null;
            }

            $rates = [
                'usd_rate' => null,
                'eur_rate' => null,
                'rub_rate' => null
            ];

            // Парсим данные
            foreach ($data->item as $item) {
                $title = (string) $item->title;
                $description = (string) $item->description;

                // Извлекаем курс из description
                // Формат: "1 USD = 501.50 KZT"
                if (preg_match('/(\d+\.?\d*)\s*KZT/', $description, $matches)) {
                    $rate = (float) $matches[1];

                    if (strpos($title, 'USD') !== false) {
                        $rates['usd_rate'] = $rate;
                    } elseif (strpos($title, 'EUR') !== false) {
                        $rates['eur_rate'] = $rate;
                    } elseif (strpos($title, 'RUB') !== false) {
                        $rates['rub_rate'] = $rate;
                    }
                }
            }

            // Проверяем, что получили хотя бы один курс
            if ($rates['usd_rate'] || $rates['eur_rate'] || $rates['rub_rate']) {
                return $rates;
            }

            return null;

        } catch (Exception $e) {
            error_log('CurrencyService XML parse error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Сохранить курсы в кеш
     */
    private function cacheRates($rates)
    {
        // Удаляем старые записи (оставляем последние 7 дней для истории)
        $this->db->execute(
            "DELETE FROM currency_cache WHERE cached_at < DATE_SUB(NOW(), INTERVAL 7 DAY)"
        );

        // Вставляем новые данные
        $sql = "INSERT INTO currency_cache (usd_rate, eur_rate, rub_rate, cached_at, expires_at) 
                VALUES (:usd_rate, :eur_rate, :rub_rate, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY))";

        $this->db->execute($sql, [
            ':usd_rate' => $rates['usd_rate'],
            ':eur_rate' => $rates['eur_rate'],
            ':rub_rate' => $rates['rub_rate']
        ]);
    }

    /**
     * Принудительно обновить кеш курсов
     */
    public function refreshCache()
    {
        $rates = $this->fetchRatesFromAPI();
        if ($rates) {
            $this->cacheRates($rates);
            return true;
        }
        return false;
    }

    /**
     * Получить историю курсов за последние N дней
     */
    public function getHistory($days = 7)
    {
        $sql = "SELECT * FROM currency_cache 
                WHERE updated_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                ORDER BY updated_at DESC";

        return $this->db->fetchAll($sql, [':days' => $days]);
    }
}
