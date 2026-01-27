<?php
/**
 * AdminDashboardController - Контроллер панели управления
 */

require_once __DIR__ . '/../../../core/Controller.php';

class AdminDashboardController extends Controller
{
    private $postModel;
    private $commentModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(); // Требуем авторизацию
        $this->postModel = $this->model('PostModel');
        $this->commentModel = $this->model('CommentModel');
    }

    /**
     * Главная страница админки
     */
    public function index()
    {
        // Статистика
        $stats = [
            'total_posts' => $this->postModel->countPublished(),
            'today_views' => $this->postModel->getTodayViews(),
            'pending_comments' => $this->commentModel->countPending(),
            'total_comments' => $this->commentModel->count()
        ];

        // Последние посты
        $recentPosts = $this->postModel->getLatestForAdmin(10);

        // Передаем данные в представление
        $this->view('admin/dashboard', [
            'stats' => $stats,
            'recentPosts' => $recentPosts
        ]);
    }
}
