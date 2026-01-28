<?php

/**
 * AdminPostController - Управление постами в админке
 */

namespace admin;

require_once __DIR__ . '/../../../core/Controller.php';

class AdminPostController extends \Controller
{
    private $postModel;
    private $categoryModel;
    private $userModel;
    private $imageProcessor;
    private $slugGenerator;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(); // Требуем авторизацию
        $this->postModel = $this->model('PostModel');
        $this->categoryModel = $this->model('CategoryModel');
        $this->userModel = $this->model('UserModel');
        $this->imageProcessor = new \ImageProcessor();
        $this->slugGenerator = new \SlugGenerator();
    }

    /**
     * Список всех постов
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        // Получаем все посты (включая черновики)
        $posts = $this->postModel->getAllForAdmin($perPage, $offset);

        // Передаем данные в представление
        $this->view('admin/posts-list', [
            'activeTab' => 'posts',
            'posts' => $posts,
            'currentPage' => $page
        ]);
    }

    /**
     * Форма создания нового поста
     */
    public function create()
    {
        // Получаем все категории
        $categories = $this->categoryModel->getAllCategories('kz');

        $this->view('admin/post-editor', [
            'activeTab' => 'posts',
            'categories' => $categories
        ]);
    }

    /**
     * Сохранение нового поста
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/posts');
            return;
        }

        // Валидация
        $titleKz = $this->post('title_kz');
        $titleRu = $this->post('title_ru');

        if (empty($titleKz) && empty($titleRu)) {
            $_SESSION['error'] = 'Кем дегенде бір тілде тақырып міндетті / Минимум один заголовок обязателен';
            $this->redirect('/admin/posts/create');
            return;
        }

        // Подготовка данных
        $data = [
            'title_kz' => $titleKz,
            'title_ru' => $titleRu,
            'content_kz' => $this->post('content_kz'),
            'content_ru' => $this->post('content_ru'),
            'category_id' => $this->post('category_id'),
            'author_id' => $_SESSION['admin_id'],
            'status' => $this->post('action') === 'publish' ? 'published' : 'draft',
            'is_featured' => $this->post('is_featured') ? 1 : 0,
            'is_announcement' => $this->post('is_announcement') ? 1 : 0
        ];

        // Генерация slug
        if (empty($this->post('slug_kz')) && !empty($titleKz)) {
            $data['slug_kz'] = $this->slugGenerator->generate($titleKz, 'kz', 'posts', 'slug_kz');
        } else {
            $data['slug_kz'] = $this->post('slug_kz');
        }

        if (empty($this->post('slug_ru')) && !empty($titleRu)) {
            $data['slug_ru'] = $this->slugGenerator->generate($titleRu, 'ru', 'posts', 'slug_ru');
        } else {
            $data['slug_ru'] = $this->post('slug_ru');
        }

        // Генерация excerpt из контента (первые 200 символов)
        if (!empty($data['content_kz'])) {
            $data['excerpt_kz'] = mb_substr(strip_tags($data['content_kz']), 0, 200, 'UTF-8') . '...';
        }
        if (!empty($data['content_ru'])) {
            $data['excerpt_ru'] = mb_substr(strip_tags($data['content_ru']), 0, 200, 'UTF-8') . '...';
        }

        // Обработка загрузки изображения
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            try {
                $imageName = $this->imageProcessor->upload($_FILES['image']);
                $data['image'] = $imageName;
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Сурет жүктелмеді: ' . $e->getMessage();
                $this->redirect('/admin/posts/create');
                return;
            }
        }

        try {
            $postId = $this->postModel->createPost($data);
            $_SESSION['success'] = 'Жаңалық сәтті қосылды / Новость успешно добавлена!';
            $this->redirect('/admin/posts/edit/' . $postId);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Қате орын алды: ' . $e->getMessage();
            $this->redirect('/admin/posts/create');
        }
    }

    /**
     * Форма редактирования поста
     */
    public function edit($id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            $_SESSION['error'] = 'Жаңалық табылмады / Новость не найдена';
            $this->redirect('/admin/posts');
            return;
        }

        // Получаем все категории
        $categories = $this->categoryModel->getAllCategories('kz');

        $this->view('admin/post-editor', [
            'activeTab' => 'posts',
            'post' => $post,
            'categories' => $categories
        ]);
    }

    /**
     * Обновление поста
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/posts/edit/' . $id);
            return;
        }

        $post = $this->postModel->find($id);
        if (!$post) {
            $_SESSION['error'] = 'Жаңалық табылмады';
            $this->redirect('/admin/posts');
            return;
        }

        // Подготовка данных
        $data = [
            'title_kz' => $this->post('title_kz'),
            'title_ru' => $this->post('title_ru'),
            'content_kz' => $this->post('content_kz'),
            'content_ru' => $this->post('content_ru'),
            'category_id' => $this->post('category_id'),
            'status' => $this->post('action') === 'publish' ? 'published' : 'draft',
            'is_featured' => $this->post('is_featured') ? 1 : 0,
            'is_announcement' => $this->post('is_announcement') ? 1 : 0
        ];

        // Обновление slug если изменился
        $newSlugKz = $this->post('slug_kz');
        $newSlugRu = $this->post('slug_ru');

        if (!empty($newSlugKz) && $newSlugKz !== $post['slug_kz']) {
            $data['slug_kz'] = $this->slugGenerator->generate($newSlugKz, 'kz', 'posts', 'slug_kz', $id);
        }
        if (!empty($newSlugRu) && $newSlugRu !== $post['slug_ru']) {
            $data['slug_ru'] = $this->slugGenerator->generate($newSlugRu, 'ru', 'posts', 'slug_ru', $id);
        }

        // Обновление excerpt
        if (!empty($data['content_kz'])) {
            $data['excerpt_kz'] = mb_substr(strip_tags($data['content_kz']), 0, 200, 'UTF-8') . '...';
        }
        if (!empty($data['content_ru'])) {
            $data['excerpt_ru'] = mb_substr(strip_tags($data['content_ru']), 0, 200, 'UTF-8') . '...';
        }

        // Обработка загрузки нового изображения
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            try {
                // Удаляем старое изображение
                if (!empty($post['image'])) {
                    $this->imageProcessor->delete($post['image']);
                }
                // Загружаем новое
                $imageName = $this->imageProcessor->upload($_FILES['image']);
                $data['image'] = $imageName;
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Сурет жүктелмеді: ' . $e->getMessage();
            }
        }

        try {
            $this->postModel->update($id, $data);
            $_SESSION['success'] = 'Жаңалық сәтті өзгертілді / Новость успешно обновлена!';
            $this->redirect('/admin/posts/edit/' . $id);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Қате орын алды: ' . $e->getMessage();
            $this->redirect('/admin/posts/edit/' . $id);
        }
    }

    /**
     * Удаление поста
     */
    public function delete($id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            $_SESSION['error'] = 'Жаңалық табылмады';
            $this->redirect('/admin/posts');
            return;
        }

        try {
            // Удаляем изображение если есть
            if (!empty($post['image'])) {
                $this->imageProcessor->delete($post['image']);
            }

            // Удаляем пост
            $this->postModel->delete($id);
            $_SESSION['success'] = 'Жаңалық сәтті өшірілді / Новость удалена';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Қате орын алды: ' . $e->getMessage();
        }

        $this->redirect('/admin/posts');
    }

    /**
     * Изменение статуса публикации
     */
    public function toggleStatus($id)
    {
        $post = $this->postModel->find($id);

        if (!$post) {
            $this->json(['success' => false, 'message' => 'Пост не найден']);
            return;
        }

        $newStatus = $post['status'] === 'published' ? 'draft' : 'published';
        $this->postModel->update($id, ['status' => $newStatus]);

        $this->json(['success' => true, 'status' => $newStatus]);
    }
}
