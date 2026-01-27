<!-- === МЕНЮ НАВИГАЦИИ === -->
<nav class="bg-white shadow sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <!-- Ссылки -->
            <ul class="flex space-x-6 py-4 font-bold text-gray-700 text-sm overflow-x-auto whitespace-nowrap">
                <li class="hover:text-brand-red cursor-pointer">
                    <i class="fas fa-bars mr-2"></i>
                    <?= $lang === 'kz' ? 'МӘЗІР' : 'МЕНЮ' ?>
                </li>

                <?php if (isset($categories) && !empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <li class="hover:text-brand-red cursor-pointer">
                            <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $category['slug'] ?>">
                                <?= strtoupper($category['name']) ?>
                            </a>
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
            <div class="flex space-x-4 text-gray-500 pl-4 border-l items-center">
                <i class="fas fa-search cursor-pointer hover:text-black"></i>
                <i class="fas fa-eye cursor-pointer hover:text-black"></i>

                <!-- Переключатель языка -->
                <?php
                // Генерация URL для переключения языка
                $currentUrl = $_SERVER['REQUEST_URI'];
                if ($lang === 'kz') {
                    // Переключаем на русский: добавляем /ru/
                    $switchUrl = '/ru' . $currentUrl;
                } else {
                    // Переключаем на казахский: убираем /ru/
                    $switchUrl = str_replace('/ru/', '/', $currentUrl);
                    $switchUrl = str_replace('/ru', '/', $switchUrl);
                }
                ?>
                <a href="<?= $switchUrl ?>" class="cursor-pointer hover:text-black font-bold transition">
                    <?= $lang === 'kz' ? 'ҚАЗ' : 'РУС' ?>
                </a>
            </div>
        </div>
    </div>
</nav>