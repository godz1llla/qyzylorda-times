<?php
/**
 * SlugGenerator - Генератор SEO-friendly URL slug
 */

class SlugGenerator
{
    /**
     * Транслитерация казахских символов
     */
    private $kazakh_map = [
        'ә' => 'a',
        'Ә' => 'A',
        'ғ' => 'g',
        'Ғ' => 'G',
        'қ' => 'q',
        'Қ' => 'Q',
        'ң' => 'n',
        'Ң' => 'N',
        'ө' => 'o',
        'Ө' => 'O',
        'ұ' => 'u',
        'Ұ' => 'U',
        'ү' => 'u',
        'Ү' => 'U',
        'һ' => 'h',
        'Һ' => 'H',
        'і' => 'i',
        'І' => 'I',
    ];

    /**
     * Транслитерация русских символов
     */
    private $russian_map = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sch',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'Yo',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'Y',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'Ts',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Sch',
        'Ъ' => '',
        'Ы' => 'Y',
        'Ь' => '',
        'Э' => 'E',
        'Ю' => 'Yu',
        'Я' => 'Ya'
    ];

    /**
     * Генерировать slug из текста
     */
    public function generate($text, $lang = 'kz')
    {
        // Приводим к нижнему регистру
        $slug = mb_strtolower($text, 'UTF-8');

        // Транслитерируем
        if ($lang === 'kz') {
            $slug = strtr($slug, $this->kazakh_map);
        }
        $slug = strtr($slug, $this->russian_map);

        // Заменяем пробелы и специальные символы на дефисы
        $slug = preg_replace('/[^a-z0-9]+/u', '-', $slug);

        // Убираем дефисы в начале и конце
        $slug = trim($slug, '-');

        // Убираем множественные дефисы
        $slug = preg_replace('/-+/', '-', $slug);

        return $slug;
    }

    /**
     * Проверить уникальность slug и добавить суффикс если нужно
     */
    public function makeUnique($slug, $table, $column, $excludeId = null)
    {
        $db = Database::getInstance();
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
            $params = [$slug];

            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }

            $result = $db->fetchOne($sql, $params);

            if ($result['count'] == 0) {
                return $slug;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
    }
}
