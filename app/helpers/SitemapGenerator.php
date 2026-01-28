<?php

/**
 * SitemapGenerator - Генератор sitemap.xml
 */

class SitemapGenerator
{
    private $db;
    private $baseUrl;
    private $outputPath;

    public function __construct($baseUrl = 'https://qyzylordatimes.kz')
    {
        $this->db = Database::getInstance();
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->outputPath = __DIR__ . '/../../public/sitemap.xml';
    }

    /**
     * Генерировать полный sitemap.xml
     */
    public function generate()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        // Добавляем главную страницу
        $this->addUrl($xml, '/', '1.0', 'daily', date('Y-m-d'));

        // Добавляем категории
        $this->addCategories($xml);

        // Добавляем посты
        $this->addPosts($xml);

        // Сохраняем файл
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        $result = $dom->save($this->outputPath);

        if ($result) {
            chmod($this->outputPath, 0644);
            return true;
        }

        return false;
    }

    /**
     * Добавить URL в sitemap
     */
    private function addUrl($xml, $loc, $priority = '0.5', $changefreq = 'weekly', $lastmod = null)
    {
        $url = $xml->addChild('url');
        $url->addChild('loc', htmlspecialchars($this->baseUrl . $loc));
        $url->addChild('priority', $priority);
        $url->addChild('changefreq', $changefreq);

        if ($lastmod) {
            $url->addChild('lastmod', $lastmod);
        }
    }

    /**
     * Добавить категории в sitemap
     */
    private function addCategories($xml)
    {
        $sql = "SELECT DISTINCT slug_kz, slug_ru FROM categories WHERE 1=1";
        $categories = $this->db->fetchAll($sql);

        foreach ($categories as $category) {
            // Казахская версия
            if (!empty($category['slug_kz'])) {
                $this->addUrl($xml, '/' . $category['slug_kz'], '0.8', 'daily');
            }

            // Русская версия
            if (!empty($category['slug_ru'])) {
                $this->addUrl($xml, '/ru/' . $category['slug_ru'], '0.8', 'daily');
            }
        }
    }

    /**
     * Добавить посты в sitemap
     */
    private function addPosts($xml)
    {
        $sql = "SELECT p.slug_kz, p.slug_ru, p.updated_at, c.slug_kz as category_slug_kz, c.slug_ru as category_slug_ru
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.updated_at DESC";

        $posts = $this->db->fetchAll($sql);

        foreach ($posts as $post) {
            $lastmod = date('Y-m-d', strtotime($post['updated_at']));

            // Казахская версия
            if (!empty($post['slug_kz']) && !empty($post['category_slug_kz'])) {
                $loc = '/' . $post['category_slug_kz'] . '/' . $post['slug_kz'];
                $this->addUrl($xml, $loc, '0.9', 'weekly', $lastmod);
            }

            // Русская версия
            if (!empty($post['slug_ru']) && !empty($post['category_slug_ru'])) {
                $loc = '/ru/' . $post['category_slug_ru'] . '/' . $post['slug_ru'];
                $this->addUrl($xml, $loc, '0.9', 'weekly', $lastmod);
            }
        }
    }

    /**
     * Автоматически обновить sitemap при изменении контента
     */
    public static function autoUpdate()
    {
        $generator = new self();
        return $generator->generate();
    }

    /**
     * Получить путь к файлу sitemap
     */
    public function getSitemapPath()
    {
        return $this->outputPath;
    }

    /**
     * Проверить существование sitemap
     */
    public function exists()
    {
        return file_exists($this->outputPath);
    }

    /**
     * Получить дату последнего обновления sitemap
     */
    public function getLastModified()
    {
        if ($this->exists()) {
            return date('Y-m-d H:i:s', filemtime($this->outputPath));
        }
        return null;
    }
}
