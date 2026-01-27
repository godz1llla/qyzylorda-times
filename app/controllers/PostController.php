<?php
/**
 * PostController - Контроллер одиночной новости
 */

require_once __DIR__ . '/../../core/Controller.php';

class PostController extends Controller
{
    private $postModel;
    private $commentModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = $this->model('PostModel');
        $this->commentModel = $this->model('CommentModel');
        $this->categoryModel = $this->model('CategoryModel');
    }

    /**
     * Показать отдельную новость
     */
    public function show($slug)
    {
        // Получаем пост по slug
        $post = $this->postModel->getBySlug($slug, $this->lang);

        if (!$post) {
            // 404
            http_response_code(404);
            echo "Новость не найдена";
            return;
        }

        // Увеличиваем счетчик просмотров
        $this->postModel->incrementViews($post['id']);

        // Получаем одобренные комментарии
        $comments = $this->commentModel->getApproved($post['id']);

        // Получаем похожие посты из той же категории
        if ($post['category_id']) {
            $relatedPosts = $this->postModel->getByCategory(
                $post['category_slug'],
                $this->lang,
                3,
                0
            );

            // Убираем текущий пост из похожих
            $relatedPosts = array_filter($relatedPosts, function ($p) use ($post) {
                return $p['id'] != $post['id'];
            });
        } else {
            $relatedPosts = [];
        }

        // Получаем популярные посты для сайдбара
        $popularPosts = $this->postModel->getPopular($this->lang, 5);

        // Получаем категории для меню
        $categories = $this->categoryModel->getAllCategories($this->lang);

        // Передаем данные в представление
        $this->view('post/single', [
            'post' => $post,
            'comments' => $comments,
            'commentCount' => count($comments),
            'relatedPosts' => $relatedPosts,
            'popularPosts' => $popularPosts,
            'categories' => $categories
        ]);
    }

    /**
     * Отправка комментария
     */
    public function submitComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        $postId = $this->post('post_id');
        $authorName = $this->post('author_name');
        $commentText = $this->post('comment_text');

        // Валидация
        if (empty($authorName) || empty($commentText)) {
            $_SESSION['error'] = 'Жазба мен аты толтырылуы керек / Имя и текст обязательны';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Простая CAPTCHA проверка (можно улучшить)
        $captcha = $this->post('captcha');
        if ($captcha != '7') { // Пример: "3 + 4 = ?"
            $_SESSION['error'] = 'Қате CAPTCHA / Неверная CAPTCHA';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Получаем IP
        $ip = $_SERVER['REMOTE_ADDR'];

        // Создаем комментарий
        try {
            $this->commentModel->create($postId, $authorName, $commentText, $ip);
            $_SESSION['success'] = 'Рахмет! Пікіріңіз тексерістен соң жарияланады / Спасибо! Ваш комментарий будет опубликован после модерации';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Қате орын алды / Произошла ошибка';
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
