<?php
/**
 * Router Class
 * Обработка URL маршрутизации с поддержкой двуязычности
 */

class Router
{
    private $lang = 'kz';
    private $routes = [];

    public function __construct()
    {
        $config = require __DIR__ . '/../config/app.php';
        $this->detectLanguage();
    }

    /**
     * Определить язык из URL
     */
    private function detectLanguage()
    {
        $url = $this->getUrl();

        // Проверяем, начинается ли URL с /ru/
        if (strpos($url, 'ru/') === 0 || $url === 'ru') {
            $this->lang = 'ru';
        } else {
            $this->lang = 'kz';
        }
    }

    /**
     * Получить текущий язык
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Получить URL из запроса
     */
    private function getUrl()
    {
        // 1. Если работаем через Apache (.htaccess), берем из GET
        if (isset($_GET['url'])) {
            return trim($_GET['url'], '/');
        }

        // 2. Если работаем на php -S (или Nginx без params), парсим REQUEST_URI
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return trim($uri, '/');
    }

    /**
     * Маршрутизировать запрос
     */
    public function dispatch()
    {
        $url = $this->getUrl();

        // Убираем префикс языка из URL
        if ($this->lang === 'ru') {
            $url = preg_replace('/^ru\/?/', '', $url);
        }

        // Разбиваем URL на сегменты
        $segments = $url ? explode('/', $url) : [];

        // Админ панель
        if (isset($segments[0]) && $segments[0] === 'admin') {
            return $this->handleAdminRoutes($segments);
        }

        // Главная страница
        if (empty($segments) || $segments[0] === '') {
            return ['controller' => 'HomeController', 'method' => 'index', 'params' => []];
        }

        // Отдельный пост: /aqparat/slug или /ru/news/slug
        if (count($segments) >= 2) {
            return ['controller' => 'PostController', 'method' => 'show', 'params' => [$segments[1]]];
        }

        // Категория: /aqparat или /sayasat
        if (count($segments) === 1) {
            return ['controller' => 'CategoryController', 'method' => 'show', 'params' => [$segments[0]]];
        }

        // 404
        return ['controller' => 'ErrorController', 'method' => 'notFound', 'params' => []];
    }

    /**
     * Обработка админ маршрутов
     */
    private function handleAdminRoutes($segments)
    {
        array_shift($segments); // Убираем 'admin'

        // /admin - dashboard
        if (empty($segments)) {
            return ['controller' => 'admin\AdminDashboardController', 'method' => 'index', 'params' => []];
        }

        $action = $segments[0];

        switch ($action) {
            case 'login':
                return ['controller' => 'admin\AuthController', 'method' => 'login', 'params' => []];
            case 'logout':
                return ['controller' => 'admin\AuthController', 'method' => 'logout', 'params' => []];
            case 'posts':
                $method = isset($segments[1]) ? $segments[1] : 'index';
                $params = array_slice($segments, 2);
                return ['controller' => 'admin\AdminPostController', 'method' => $method, 'params' => $params];
            case 'comments':
                $method = isset($segments[1]) ? $segments[1] : 'index';
                $params = array_slice($segments, 2);
                return ['controller' => 'admin\AdminCommentController', 'method' => $method, 'params' => $params];
            case 'categories':
                $method = isset($segments[1]) ? $segments[1] : 'index';
                $params = array_slice($segments, 2);
                return ['controller' => 'admin\AdminCategoryController', 'method' => $method, 'params' => $params];
            default:
                return ['controller' => 'admin\AdminDashboardController', 'method' => 'index', 'params' => []];
        }
    }

    /**
     * Сгенерировать URL с учетом языка
     */
    public function url($path, $lang = null)
    {
        $config = require __DIR__ . '/../config/app.php';
        $lang = $lang ?? $this->lang;

        $base = rtrim($config['url'], '/');
        $path = ltrim($path, '/');

        if ($lang === 'ru') {
            return "{$base}/ru/{$path}";
        }

        return "{$base}/{$path}";
    }
}
