<?php
/**
 * Base Controller Class
 * Базовый класс для всех контроллеров
 */

class Controller
{
    protected $db;
    protected $lang;

    public function __construct()
    {
        $this->db = Database::getInstance();

        // Получаем язык из глобальной переменной (устанавливается в index.php)
        global $router;
        $this->lang = $router ? $router->getLang() : 'kz';
    }

    /**
     * Загрузить представление
     */
    protected function view($view, $data = [])
    {
        // Извлекаем данные в переменные
        extract($data);

        // Добавляем язык в данные
        $lang = $this->lang;

        // Подключаем представление
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("Представление не найдено: {$view}");
        }
    }

    /**
     * Загрузить модель
     */
    protected function model($model)
    {
        $modelFile = __DIR__ . '/../app/models/' . $model . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Модель не найдена: {$model}");
        }
    }

    /**
     * Редирект
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * JSON ответ
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Проверка авторизации администратора
     */
    protected function requireAuth()
    {
        session_start();

        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            $this->redirect('/admin/login');
        }
    }

    /**
     * Получить POST данные
     */
    protected function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }

        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    /**
     * Получить GET данные
     */
    protected function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }

        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    /**
     * Санитизация строки
     */
    protected function sanitize($string)
    {
        return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Валидация CSRF токена
     */
    protected function validateCSRF($token)
    {
        session_start();

        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }

        return true;
    }

    /**
     * Генерация CSRF токена
     */
    protected function generateCSRF()
    {
        session_start();

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}
