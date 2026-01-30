<!-- === МЕНЮ НАВИГАЦИИ === -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <!-- Ссылки -->
            <ul class="flex space-x-6 py-4 font-bold text-gray-700 text-sm overflow-x-auto whitespace-nowrap">
                <li class="hover:text-brand-red cursor-pointer transition-colors duration-200">
                    <?= $lang === 'kz' ? 'МӘЗІР' : 'МЕНЮ' ?>
                </li>

                <?php if (isset($categories) && !empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <li class="relative group">
                            <?php
                            // Категория "Новости" ведет на главную страницу
                            if ($cat['slug'] === 'zhanalyqtar' || $cat['slug'] === 'novosti') {
                                $catUrl = $lang === 'ru' ? '/ru/' : '/';
                            } else {
                                $catUrl = '/' . ($lang === 'ru' ? 'ru/' : '') . $cat['slug'];
                            }
                            ?>
                            <a href="<?= $catUrl ?>" class="hover:text-brand-red transition-colors duration-200 py-2 block">
                                <?= strtoupper($cat['name']) ?>
                            </a>
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback категории -->
                    <li class="hover:text-brand-red cursor-pointer text-brand-red">
                        <?= $lang === 'kz' ? 'ЖАҢАЛЫҚТАР' : 'НОВОСТИ' ?>
                    </li>
                    <li class="hover:text-brand-red cursor-pointer">
                        <?= $lang === 'kz' ? 'САЯСАТ' : 'ПОЛИТИКА' ?>
                    </li>
                    <li class="hover:text-brand-red cursor-pointer">
                        <?= $lang === 'kz' ? 'ҚОҒАМ' : 'ОБЩЕСТВО' ?>
                    </li>
                    <li class="hover:text-brand-red cursor-pointer">
                        <?= $lang === 'kz' ? 'ЭКОНОМИКА' : 'ЭКОНОМИКА' ?>
                    </li>
                    <li class="hover:text-brand-red cursor-pointer">
                        <?= $lang === 'kz' ? 'БІЛІМ' : 'ОБРАЗОВАНИЕ' ?>
                    </li>
                    <li class="hover:text-brand-red cursor-pointer">
                        <?= $lang === 'kz' ? 'АУЫЛ ШАРУАШЫЛЫҒЫ' : 'СЕЛЬХОЗ' ?>
                    </li>
                    <li class="hover:text-brand-red cursor-pointer">
                        <?= $lang === 'kz' ? 'СПОРТ' : 'СПОРТ' ?>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Иконки справа -->
            <div class="flex space-x-3 items-center">
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-brand-red hover:text-white transition-all duration-200">
                    <i class="fas fa-search"></i>
                </button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-brand-red hover:text-white transition-all duration-200">
                    <i class="fas fa-eye"></i>
                </button>

                <!-- Переключатель языка -->
                <?php
                // Генерация URL для переключения языка
                $switchUrl = '/';

                // Если находимся на странице поста
                if (isset($post) && !empty($post)) {
                    $targetLang = $lang === 'kz' ? 'ru' : 'kz';
                    $categorySlug = $post["slug_{$targetLang}"] ?? $post['category_slug'] ?? '';
                    $postSlug = $post["slug_{$targetLang}"] ?? '';

                    if (!empty($categorySlug) && !empty($postSlug)) {
                        $switchUrl = ($targetLang === 'ru' ? '/ru/' : '/') . $categorySlug . '/' . $postSlug;
                    } else {
                        // Fallback на главную
                        $switchUrl = $targetLang === 'ru' ? '/ru/' : '/';
                    }
                }
                // Если находимся на странице категории
                elseif (isset($category) && !empty($category)) {
                    $targetLang = $lang === 'kz' ? 'ru' : 'kz';
                    // Используем slug_kz или slug_ru из категории
                    $categorySlug = $category["slug_{$targetLang}"] ?? '';

                    if (!empty($categorySlug)) {
                        $switchUrl = ($targetLang === 'ru' ? '/ru/' : '/') . $categorySlug;
                    } else {
                        $switchUrl = $targetLang === 'ru' ? '/ru/' : '/';
                    }
                }
                // Главная страница
                else {
                    $switchUrl = $lang === 'kz' ? '/ru/' : '/';
                }
                ?>
                <a href="<?= $switchUrl ?>"
                    class="px-4 h-10 flex items-center justify-center rounded-lg bg-brand-red text-white font-bold hover:bg-red-700 transition-all duration-200 shadow-md">
                    <?= $lang === 'kz' ? 'РУС' : 'ҚАЗ' ?>
                </a>
            </div>
        </div>
    </div>
</nav>