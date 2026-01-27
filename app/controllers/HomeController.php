<?php
/**
 * HomeController - Контроллер главной страницы
 */

require_once __DIR__ . '/../../core/Controller.php';

class HomeController extends Controller
{
    private $postModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = $this->model('PostModel');
        $this->categoryModel = $this->model('CategoryModel');
    }

    /**
     * Главная страница
     */
    public function index()
    {
        // Получаем избранную новость (hero)
        $heroPost = $this->postModel->getFeatured($this->lang);

        // Получаем анонсы для сайдбара
        $announcements = $this->postModel->getAnnouncements($this->lang, 5);

        // Получаем последние новости
        $latestNews = $this->postModel->getPublished($this->lang, 9, 0);

        // Получаем популярные новости
        $popularNews = $this->postModel->getPopular($this->lang, 2);

        // Получаем категории для меню
        $categories = $this->categoryModel->getAllCategories($this->lang);

        // Получаем погоду и валюты
        $weather = $this->getWeather();
        $currency = $this->getCurrency();

        // Передаем данные в представление
        $this->view('home/index', [
            'heroPost' => $heroPost,
            'announcements' => $announcements,
            'latestNews' => $latestNews,
            'popularNews' => $popularNews,
            'categories' => $categories,
            'weather' => $weather,
            'currency' => $currency
        ]);
    }

    /**
     * Получить данные погоды из кеша
     */
    private function getWeather()
    {
        $sql = "SELECT * FROM weather_cache 
                WHERE expires_at > NOW() 
                ORDER BY id DESC LIMIT 1";

        $weather = $this->db->fetchOne($sql);

        // Если кеш истек или пуст, используем значения по умолчанию
        if (!$weather) {
            $weather = [
                'temperature' => -8.5,
                'condition' => 'Ясно',
                'icon' => '01d'
            ];
        }

        return $weather;
    }

    /**
     * Получить курсы валют из кеша
     */
    private function getCurrency()
    {
        $sql = "SELECT * FROM currency_cache 
                WHERE expires_at > NOW() 
                ORDER BY id DESC LIMIT 1";

        $currency = $this->db->fetchOne($sql);

        // Если кеш истек или пуст, используем значения по умолчанию
        if (!$currency) {
            $currency = [
                'usd_rate' => 501.50,
                'eur_rate' => 545.20,
                'rub_rate' => 5.40
            ];
        }

        return $currency;
    }
}
