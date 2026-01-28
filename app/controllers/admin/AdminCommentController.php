<?php
/**
 * AdminCommentController - Контроллер управления комментариями
 */

namespace admin;

require_once __DIR__ . '/../../../core/Controller.php';

class AdminCommentController extends \Controller
{
    private $commentModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->commentModel = $this->model('CommentModel');
    }

    /**
     * Список комментариев
     */
    public function index()
    {
        $pendingComments = $this->commentModel->getPending();

        $this->view('admin/comments', [
            'comments' => $pendingComments
        ]);
    }

    /**
     * Одобрить комментарий
     */
    public function approve($id)
    {
        try {
            $this->commentModel->approve($id, $_SESSION['admin_user_id']);
            $_SESSION['success'] = 'Комментарий одобрен';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Ошибка при одобрении комментария';
        }

        $this->redirect('/admin/comments');
    }

    /**
     * Отклонить комментарий
     */
    public function reject($id)
    {
        try {
            $this->commentModel->reject($id, $_SESSION['admin_user_id']);
            $_SESSION['success'] = 'Комментарий отклонен';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Ошибка при отклонении комментария';
        }

        $this->redirect('/admin/comments');
    }

    /**
     * Удалить комментарий
     */
    public function delete($id)
    {
        try {
            $this->commentModel->delete($id);
            $_SESSION['success'] = 'Комментарий удален';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Ошибка при удалении комментария';
        }

        $this->redirect('/admin/comments');
    }
}
