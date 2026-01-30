<?php
// Главная страница - Qyzylorda Times
// Подключаем header
require_once __DIR__ . '/../partials/_header.php';
?>

<!-- Подключаем навигацию -->
<?php require_once __DIR__ . '/../partials/_navigation.php'; ?>

<!-- === MAIN CONTENT (HERO) === -->
<main class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- Главная новость (Hero Left) -->
    <?php if (isset($heroPost) && $heroPost): ?>
        <div
            class="lg:col-span-3 relative h-96 group cursor-pointer overflow-hidden rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500">
            <?php
            // Путь к изображению
            $heroImage = !empty($heroPost['image'])
                ? '/uploads/large/' . $heroPost['image']
                : 'https://placehold.co/800x600/222/FFF?text=Korkyt+Ata+Memorial';

            // Заголовок и контент в зависимости от языка
            $heroTitle = $lang === 'kz' ? $heroPost['title_kz'] : $heroPost['title_ru'];
            $heroSlug = $lang === 'kz' ? $heroPost['slug_kz'] : $heroPost['slug_ru'];
            $heroCategory = $heroPost['category_slug'] ?? 'aqparat';
            ?>

            <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $heroCategory ?>/<?= $heroSlug ?>">
                <img src="<?= $heroImage ?>"
                    class="w-full h-full object-cover transition duration-700 group-hover:scale-105"
                    alt="<?= htmlspecialchars($heroTitle) ?>">

                <div class="absolute bottom-0 left-0 w-full news-gradient p-6 text-white">
                    <span
                        class="bg-brand-red px-3 py-1.5 text-xs font-bold uppercase mb-2 inline-block rounded-lg shadow-md">
                        <?= $lang === 'kz' ? 'Басты жаңалық' : 'Главная новость' ?>
                    </span>
                    <h2 class="text-3xl font-bold leading-tight mb-2">
                        <?= htmlspecialchars($heroTitle) ?>
                    </h2>
                    <div class="flex items-center text-sm opacity-80 gap-4">
                        <span>
                            <i class="far fa-clock"></i>
                            <?= date('H:i', strtotime($heroPost['published_at'] ?? 'now')) ?>,
                            <?= $lang === 'kz' ? 'Бүгін' : 'Сегодня' ?>
                        </span>
                        <?php if (!empty($heroPost['author_name'])): ?>
                            <span>
                                <i class="fas fa-user"></i>
                                <?= htmlspecialchars($heroPost['author_name']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
    <?php else: ?>
        <!-- Fallback если нет hero новости -->
        <div class="lg:col-span-3 relative h-96 bg-gray-300 flex items-center justify-center rounded-sm shadow-md">
            <p class="text-gray-600">
                <?= $lang === 'kz' ? 'Басты жаңалық жоқ' : 'Нет главной новости' ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Сайдбар (Анонсы/Правое меню) -->
    <div class="lg:col-span-1 bg-white shadow-lg border border-gray-100 rounded-2xl overflow-hidden">
        <div class="brand-red text-white p-3 font-bold uppercase flex justify-between items-center">
            <span>
                <?= $lang === 'kz' ? 'Анонс' : 'Анонсы' ?>
            </span>
            <i class="fab fa-whatsapp text-xl"></i>
        </div>

        <!-- Список анонсов -->
        <div class="divide-y divide-gray-100">
            <?php if (isset($announcements) && !empty($announcements)): ?>
                <?php foreach ($announcements as $announcement): ?>
                    <?php
                    $annTitle = $lang === 'kz' ? $announcement['title_kz'] : $announcement['title_ru'];
                    $annSlug = $lang === 'kz' ? $announcement['slug_kz'] : $announcement['slug_ru'];
                    $annCategory = $announcement['category_slug'] ?? 'aqparat';
                    $annImage = !empty($announcement['image'])
                        ? '/uploads/thumbnail/' . $announcement['image']
                        : 'https://placehold.co/100x100/333/FFF?text=News';
                    $annTime = date('H:i', strtotime($announcement['published_at'] ?? 'now'));
                    ?>
                    <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $annCategory ?>/<?= $annSlug ?>"
                        class="p-3 hover:bg-gray-50 cursor-pointer flex gap-3 block transition-all duration-200">
                        <img src="<?= $annImage ?>" class="w-16 h-16 object-cover rounded-xl shadow-sm"
                            alt="<?= htmlspecialchars($annTitle) ?>">
                        <div>
                            <div class="text-[10px] text-gray-500 flex justify-between">
                                <span>
                                    <i class="fas fa-circle text-xs text-green-500"></i>
                                    <?= htmlspecialchars($announcement['category_name'] ?? '') ?>
                                </span>
                                <span>
                                    <?= $annTime ?>
                                </span>
                            </div>
                            <h4 class="text-sm font-semibold leading-snug line-clamp-2">
                                <?= htmlspecialchars($annTitle) ?>
                            </h4>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-3 text-center text-gray-500 text-sm">
                    <?= $lang === 'kz' ? 'Анонстар жоқ' : 'Нет анонсов' ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- === ЛЕНТА НОВОСТЕЙ === -->
<section class="container mx-auto px-4 mb-8">
    <div class="bg-brand-red text-white px-4 py-2 font-bold inline-block rounded-t-sm mb-1">
        <?= $lang === 'kz' ? 'Барлық жаңалықтар' : 'Все новости' ?>
    </div>
    <div class="bg-gray-800 h-0.5 w-full mb-4 opacity-10"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (isset($latestNews) && !empty($latestNews)): ?>
            <?php foreach ($latestNews as $news): ?>
                <?php
                $newsTitle = $lang === 'kz' ? $news['title_kz'] : $news['title_ru'];
                $newsExcerpt = $lang === 'kz' ? $news['excerpt_kz'] : $news['excerpt_ru'];
                $newsSlug = $lang === 'kz' ? $news['slug_kz'] : $news['slug_ru'];
                $newsCategory = $news['category_slug'] ?? 'aqparat';
                $newsCategoryName = $news['category_name'] ?? '';
                $newsImage = !empty($news['image'])
                    ? '/uploads/medium/' . $news['image']
                    : 'https://placehold.co/400x300/eab308/FFF?text=News';
                $newsTime = date('H:i', strtotime($news['published_at'] ?? 'now'));
                ?>
                <div
                    class="bg-white group cursor-pointer shadow-md hover:shadow-2xl transition-all duration-300 rounded-2xl overflow-hidden transform hover:-translate-y-1">
                    <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $newsCategory ?>/<?= $newsSlug ?>">
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?= $newsImage ?>" class="w-full h-full object-cover group-hover:scale-105 transition"
                                alt="<?= htmlspecialchars($newsTitle) ?>">
                            <span
                                class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-3 py-1.5 rounded-lg backdrop-blur-sm">
                                <?= $newsTime ?>
                            </span>
                        </div>
                        <div class="p-4 border border-t-0 border-gray-200">
                            <div class="text-xs text-brand-red font-bold uppercase mb-2">
                                <?= htmlspecialchars($newsCategoryName) ?>
                            </div>
                            <h3
                                class="text-lg font-bold leading-snug text-gray-800 group-hover:text-brand-red mb-3 line-clamp-2">
                                <?= htmlspecialchars($newsTitle) ?>
                            </h3>
                            <p class="text-xs text-gray-400 line-clamp-2">
                                <?= htmlspecialchars($newsExcerpt ?? '') ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-3 text-center text-gray-500 py-8">
                <?= $lang === 'kz' ? 'Жаңалықтар жоқ' : 'Нет новостей' ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- === ПОПУЛЯРНЫЕ НОВОСТИ === -->
<?php if (isset($popularNews) && !empty($popularNews)): ?>
    <section class="container mx-auto px-4 mb-8">
        <div class="bg-gray-900 text-white px-4 py-2 font-bold inline-block rounded-t-sm mb-1">
            <?= $lang === 'kz' ? 'Танымал жаңалықтар' : 'Популярные новости' ?>
        </div>
        <div class="bg-gray-800 h-0.5 w-full mb-4 opacity-10"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($popularNews as $popular): ?>
                <?php
                $popTitle = $lang === 'kz' ? $popular['title_kz'] : $popular['title_ru'];
                $popExcerpt = $lang === 'kz' ? $popular['excerpt_kz'] : $popular['excerpt_ru'];
                $popSlug = $lang === 'kz' ? $popular['slug_kz'] : $popular['slug_ru'];
                $popCategory = $popular['category_slug'] ?? 'aqparat';
                $popImage = !empty($popular['image'])
                    ? '/uploads/medium/' . $popular['image']
                    : 'https://placehold.co/400x300/dc2626/FFF?text=Popular';
                ?>
                <div
                    class="bg-gradient-to-br from-gray-900 to-gray-800 text-white group cursor-pointer hover:from-gray-800 hover:to-gray-700 transition-all duration-300 rounded-2xl overflow-hidden shadow-xl">
                    <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $popCategory ?>/<?= $popSlug ?>" class="flex gap-4 p-4">
                        <img src="<?= $popImage ?>" class="w-32 h-32 object-cover rounded-xl shadow-lg"
                            alt="<?= htmlspecialchars($popTitle) ?>">
                        <div>
                            <h4 class="text-xl font-bold leading-tight mb-2 group-hover:text-brand-red transition">
                                <?= htmlspecialchars($popTitle) ?>
                            </h4>
                            <p class="text-sm text-gray-400 line-clamp-2">
                                <?= htmlspecialchars($popExcerpt ?? '') ?>
                            </p>
                            <div class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-eye"></i>
                                <?= number_format($popular['views'] ?? 0) ?>
                                <?= $lang === 'kz' ? 'көрініс' : 'просмотров' ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<!-- Подключаем footer -->
<?php require_once __DIR__ . '/../partials/_footer.php'; ?>