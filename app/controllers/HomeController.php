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
        // Определяем текущий язык из URL
        $lang = $this->getLang();

        // Получаем данные для главной страницы
        $postModel = $this->model('PostModel');

        // Hero новость (is_featured = 1)
        $heroPost = $postModel->getFeatured($lang);

        // Анонсы (is_announcement = 1)
        $announcements = $postModel->getAnnouncements($lang, 5);

        // Последние новости
        $latestNews = $postModel->getPublished($lang, 6, 0);

        // Популярные новости
        $popularNews = $postModel->getPopular($lang, 4);

        // Получаем погоду
        $weatherService = new WeatherService();
        $weather = $weatherService->getCurrentWeather();

        // Получаем курсы валют
        $currencyService = new CurrencyService();
        $currency = $currencyService->getRates();

        // Передаем данные в представление
        $this->view('home/index', [
            'lang' => $lang,
            'heroPost' => $heroPost,
            'announcements' => $announcements,
            'latestNews' => $latestNews,
            'popularNews' => $popularNews,
            'weather' => $weather,
            'currency' => $currency
        ]);
    }
}
