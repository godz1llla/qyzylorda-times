<?php
/**
 * CategoryController - Контроллер категорий
 */

require_once __DIR__ . '/../../core/Controller.php';

class CategoryController extends Controller
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
     * Показать посты категории
     */
    public function show($categorySlug)
    {
        // Получаем категорию
        $category = $this->categoryModel->getBySlug($categorySlug, $this->lang);

        if (!$category) {
            http_response_code(404);
            echo "Категория не найдена";
            return;
        }

        // Пагинация
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        // Получаем посты категории
        $posts = $this->postModel->getByCategory($categorySlug, $this->lang, $perPage, $offset);

        // Получаем все категории для меню
        $categories = $this->categoryModel->getAllCategories($this->lang);

        // Передаем данные в представление
        $this->view('category/index', [
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
            'currentPage' => $page
        ]);
    }
}
