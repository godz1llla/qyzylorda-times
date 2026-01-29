<?php
/**
 * AdminCategoryController - Управление категориями
 */

namespace admin;

require_once __DIR__ . '/../../../core/Controller.php';

class AdminCategoryController extends \Controller
{
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->categoryModel = $this->model('CategoryModel');
    }

    /**
     * Список категорий
     */
    public function index()
    {
        $categories = $this->categoryModel->getAll();

        $this->view('admin/categories', [
            'activeTab' => 'categories',
            'categories' => $categories
        ]);
    }

    /**
     * Создать категорию
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
            return;
        }

        $data = [
            'name_kz' => $this->post('name_kz'),
            'name_ru' => $this->post('name_ru'),
            'slug_kz' => $this->post('slug_kz'),
            'slug_ru' => $this->post('slug_ru'),
            'description_kz' => $this->post('description_kz'),
            'description_ru' => $this->post('description_ru'),
            'sort_order' => $this->post('sort_order', 0)
        ];

        try {
            $this->categoryModel->insert($data);
            $_SESSION['success'] = 'Категория успешно создана';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Ошибка создания категории: ' . $e->getMessage();
        }

        $this->redirect('/admin/categories');
    }

    /**
     * Обновить категорию
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
            return;
        }

        $data = [
            'name_kz' => $this->post('name_kz'),
            'name_ru' => $this->post('name_ru'),
            'slug_kz' => $this->post('slug_kz'),
            'slug_ru' => $this->post('slug_ru'),
            'description_kz' => $this->post('description_kz'),
            'description_ru' => $this->post('description_ru'),
            'sort_order' => $this->post('sort_order', 0)
        ];

        try {
            $this->categoryModel->update($id, $data);
            $_SESSION['success'] = 'Категория успешно обновлена';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Ошибка обновления категории: ' . $e->getMessage();
        }

        $this->redirect('/admin/categories');
    }

    /**
     * Удалить категорию
     */
    public function delete($id)
    {
        try {
            $this->categoryModel->delete($id);
            $_SESSION['success'] = 'Категория удалена';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Ошибка удаления категории: ' . $e->getMessage();
        }

        $this->redirect('/admin/categories');
    }
}
