<?php
/**
 * AuthController - Контроллер аутентификации для админ-панели
 */

require_once __DIR__ . '/../../../core/Controller.php';

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('UserModel');
    }

    /**
     * Страница входа
     */
    public function login()
    {
        // Если уже авторизован, перенаправляем в dashboard
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            $this->redirect('/admin');
            return;
        }

        // Обработка POST запроса
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->post('username');
            $password = $this->post('password');

            // Аутентификация
            $user = $this->userModel->authenticate($username, $password);

            if ($user) {
                // Устанавливаем сессию
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_full_name'] = $user['full_name'];
                $_SESSION['admin_role'] = $user['role'];

                $this->redirect('/admin');
            } else {
                $error = 'Неверное имя пользователя или пароль';
            }
        }

        // Показываем форму входа
        $this->view('admin/login', [
            'error' => $error ?? null
        ]);
    }

    /**
     * Выход
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('/admin/login');
    }
}
