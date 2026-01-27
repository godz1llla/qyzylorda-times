<?php
/**
 * CommentModel - Модель для работы с комментариями
 */

require_once __DIR__ . '/../../core/Model.php';

class CommentModel extends Model
{
    protected $table = 'comments';

    /**
     * Создать новый комментарий (статус pending по умолчанию)
     */
    public function create($postId, $authorName, $text, $ip)
    {
        $data = [
            'post_id' => $postId,
            'author_name' => htmlspecialchars($authorName, ENT_QUOTES, 'UTF-8'),
            'comment_text' => htmlspecialchars($text, ENT_QUOTES, 'UTF-8'),
            'author_ip' => $ip,
            'status' => 'pending'
        ];

        return $this->insert($data);
    }

    /**
     * Получить одобренные комментарии для поста
     */
    public function getApproved($postId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE post_id = ? AND status = 'approved'
                ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, [$postId]);
    }

    /**
     * Получить ожидающие модерации комментарии
     */
    public function getPending($limit = 50)
    {
        $sql = "SELECT c.*, p.title_kz as post_title
                FROM {$this->table} c
                LEFT JOIN posts p ON c.post_id = p.id
                WHERE c.status = 'pending'
                ORDER BY c.created_at DESC
                LIMIT {$limit}";

        return $this->db->fetchAll($sql);
    }

    /**
     * Одобрить комментарий
     */
    public function approve($commentId, $userId)
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'approved', moderated_at = NOW(), moderated_by = ?
                WHERE id = ?";

        return $this->db->execute($sql, [$userId, $commentId]);
    }

    /**
     * Отклонить комментарий
     */
    public function reject($commentId, $userId)
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'rejected', moderated_at = NOW(), moderated_by = ?
                WHERE id = ?";

        return $this->db->execute($sql, [$userId, $commentId]);
    }

    /**
     * Подсчитать ожидающие комментарии
     */
    public function countPending()
    {
        return $this->count("status = 'pending'");
    }

    /**
     * Получить количество одобренных комментариев для поста
     */
    public function countForPost($postId)
    {
        return $this->count("post_id = ? AND status = 'approved'", [$postId]);
    }
}
