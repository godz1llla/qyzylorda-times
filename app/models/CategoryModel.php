<?php
/**
 * CategoryModel - Модель для работы с категориями
 */

require_once __DIR__ . '/../../core/Model.php';

class CategoryModel extends Model
{
    protected $table = 'categories';

    /**
     * Получить все категории
     */
    public function getAllCategories($lang)
    {
        $sql = "SELECT id, name_{$lang} as name, slug_{$lang} as slug, description_{$lang} as description
                FROM {$this->table}
                ORDER BY sort_order ASC";

        return $this->db->fetchAll($sql);
    }

    /**
     * Получить категорию по slug
     */
    public function getBySlug($slug, $lang)
    {
        $slugColumn = "slug_{$lang}";

        $sql = "SELECT id, 
                       name_{$lang} as name, 
                       slug_{$lang} as slug, 
                       slug_kz, slug_ru,
                       description_{$lang} as description
                FROM {$this->table}
                WHERE {$slugColumn} = ?
                LIMIT 1";

        return $this->db->fetchOne($sql, [$slug]);
    }

    /**
     * Получить ID категории по slug
     */
    public function getIdBySlug($slug, $lang)
    {
        $category = $this->getBySlug($slug, $lang);
        return $category ? $category['id'] : null;
    }

    /**
     * Получить все категории со всеми языками (для админки)
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql);
    }
}
