<?php
/**
 * UserModel - Модель для работы с пользователями
 */

require_once __DIR__ . '/../../core/Model.php';

class UserModel extends Model
{
    protected $table = 'users';

    /**
     * Аутентификация пользователя
     */
    public function authenticate($username, $password)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        $user = $this->db->fetchOne($sql, [$username]);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Получить пользователя по ID
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Проверить существование username
     */
    public function usernameExists($username)
    {
        $count = $this->count("username = ?", [$username]);
        return $count > 0;
    }

    /**
     * Создать нового пользователя
     */
    public function createUser($data)
    {
        // Хешируем пароль
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->insert($data);
    }
}
