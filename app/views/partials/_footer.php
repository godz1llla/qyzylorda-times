<!-- === ПОДВАЛ (FOOTER) === -->
<footer class="bg-gray-900 text-white mt-8">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Колонка 1: О нас -->
            <div>
                <h4 class="font-bold mb-3 uppercase border-b border-white/20 pb-2">
                    <?= $lang === 'kz' ? 'Біз туралы' : 'О нас' ?>
                </h4>
                <p class="text-sm text-gray-400 leading-relaxed">
                    <?php if ($lang === 'kz'): ?>
                        Қызылорда облысының ресми ақпарат агенттігі. Аймақ өміріндегі ең соңғы оқиғалар мен жаңалықтар.
                    <?php else: ?>
                        Официальное информационное агентство Кызылординской области. Последние события и новости региона.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Колонка 2: Навигация -->
            <div>
                <h4 class="font-bold mb-3 uppercase border-b border-white/20 pb-2">
                    <?= $lang === 'kz' ? 'Навигация' : 'Навигация' ?>
                </h4>
                <ul class="text-sm text-gray-400 space-y-2">
                    <?php if (isset($categories) && !empty($categories)): ?>
                        <?php foreach (array_slice($categories, 0, 5) as $category): ?>
                            <li>
                                <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $category['slug'] ?>"
                                    class="hover:text-white transition">
                                    <?= $category['name'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a href="/" class="hover:text-white transition">
                                <?= $lang === 'kz' ? 'Басты бет' : 'Главная' ?>
                            </a></li>
                        <li><a href="#" class="hover:text-white transition">
                                <?= $lang === 'kz' ? 'Жаңалықтар' : 'Новости' ?>
                            </a></li>
                        <li><a href="#" class="hover:text-white transition">
                                <?= $lang === 'kz' ? 'Саясат' : 'Политика' ?>
                            </a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Колонка 3: Контакты -->
            <div>
                <h4 class="font-bold mb-3 uppercase border-b border-white/20 pb-2">
                    <?= $lang === 'kz' ? 'Байланыс' : 'Контакты' ?>
                </h4>
                <ul class="text-sm text-gray-400 space-y-2">
                    <li>
                        <i class="fas fa-phone-alt text-brand-red mr-2"></i>
                        8 (7242) 27-01-01
                    </li>
                    <li>
                        <i class="fas fa-envelope text-brand-red mr-2"></i>
                        editor@qyzylordatimes.kz
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt text-brand-red mr-2"></i>
                        <?= $lang === 'kz' ? 'Қызылорда қ., Бейбарыс сұлтан, 1' : 'г. Кызылорда, ул. Бейбарыс султан, 1' ?>
                    </li>
                </ul>
            </div>

            <!-- Колонка 4: Социальные сети -->
            <div>
                <h4 class="font-bold mb-3 uppercase border-b border-white/20 pb-2">
                    <?= $lang === 'kz' ? 'Әлеуметтік желілер' : 'Социальные сети' ?>
                </h4>
                <div class="flex space-x-3">
                    <a href="#"
                        class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-blue-400 hover:bg-blue-500 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-500 hover:opacity-90 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Копирайт -->
        <div class="text-center text-gray-500 text-sm mt-8 pt-6 border-t border-white/10">
            ©
            <?= date('Y') ?> ЖШС "Syrdarya Media".
            <?= $lang === 'kz' ? 'Барлық құқықтар қорғалған' : 'Все права защищены' ?>.
        </div>
    </div>
</footer>

</body>

</html>