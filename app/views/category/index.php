<?php
// Страница категории - Qyzylorda Times
// Подключаем header
require_once __DIR__ . '/../partials/_header.php';
?>

<!-- Подключаем навигацию -->
<?php require_once __DIR__ . '/../partials/_navigation.php'; ?>

<!-- === BREADCRUMBS === -->
<div class="bg-white border-b">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center text-sm text-gray-600">
            <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?>" class="hover:text-brand-red">
                <?= $lang === 'kz' ? 'Басты бет' : 'Главная' ?>
            </a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900 font-semibold">
                <?= htmlspecialchars($category['name'] ?? '') ?>
            </span>
        </div>
    </div>
</div>

<!-- === CATEGORY HEADER === -->
<div class="brand-red text-white py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-2">
            <?= htmlspecialchars($category['name'] ?? '') ?>
        </h1>
        <?php if (!empty($category['description'])): ?>
            <p class="text-lg opacity-90">
                <?= htmlspecialchars($category['description']) ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<!-- === POSTS GRID === -->
<main class="container mx-auto px-4 py-8">
    <?php if (isset($posts) && !empty($posts)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($posts as $post): ?>
                <?php
                $postTitle = $lang === 'kz' ? $post['title_kz'] : $post['title_ru'];
                $postExcerpt = $lang === 'kz' ? $post['excerpt_kz'] : $post['excerpt_ru'];
                $postSlug = $lang === 'kz' ? $post['slug_kz'] : $post['slug_ru'];
                $postCategory = $category['slug'] ?? 'aqparat';
                $postImage = !empty($post['image'])
                    ? '/uploads/medium/' . $post['image']
                    : 'https://placehold.co/400x300/666/FFF?text=News';
                $postDate = date('d.m.Y H:i', strtotime($post['published_at'] ?? 'now'));
                ?>
                <div class="bg-white group cursor-pointer shadow-sm hover:shadow-lg transition rounded overflow-hidden">
                    <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $postCategory ?>/<?= $postSlug ?>">
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?= $postImage ?>" class="w-full h-full object-cover group-hover:scale-105 transition"
                                alt="<?= htmlspecialchars($postTitle) ?>">
                            <span class="absolute top-2 right-2 bg-brand-red text-white text-xs px-2 py-1 rounded">
                                <i class="fas fa-eye"></i>
                                <?= number_format($post['views'] ?? 0) ?>
                            </span>
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-2">
                                <i class="far fa-clock"></i>
                                <?= $postDate ?>
                            </div>
                            <h3
                                class="text-lg font-bold leading-snug text-gray-800 group-hover:text-brand-red mb-2 line-clamp-2">
                                <?= htmlspecialchars($postTitle) ?>
                            </h3>
                            <p class="text-sm text-gray-600 line-clamp-3">
                                <?= htmlspecialchars($postExcerpt ?? '') ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- === PAGINATION === -->
        <?php
        $totalPages = 5; // TODO: Вычислять из количества постов
        $hasNext = $currentPage < $totalPages;
        $hasPrev = $currentPage > 1;
        ?>
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center gap-2 mt-8">
                <?php if ($hasPrev): ?>
                    <a href="?page=<?= $currentPage - 1 ?>"
                        class="px-4 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-left"></i>
                        <?= $lang === 'kz' ? 'Артқа' : 'Назад' ?>
                    </a>
                <?php endif; ?>

                <div class="flex gap-1">
                    <?php for ($i = 1; $i <= min($totalPages, 5); $i++): ?>
                        <a href="?page=<?= $i ?>"
                            class="px-4 py-2 rounded transition <?= $i === $currentPage ? 'bg-brand-red text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <?php if ($hasNext): ?>
                    <a href="?page=<?= $currentPage + 1 ?>"
                        class="px-4 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50 transition">
                        <?= $lang === 'kz' ? 'Алға' : 'Вперед' ?>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Пустая категория -->
        <div class="text-center py-16">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-600 mb-2">
                <?= $lang === 'kz' ? 'Жаңалықтар жоқ' : 'Нет новостей' ?>
            </h3>
            <p class="text-gray-500">
                <?= $lang === 'kz' ? 'Бұл санатта әлі ешқандай жаңалық жарияланбаған' : 'В этой категории еще нет опубликованных новостей' ?>
            </p>
        </div>
    <?php endif; ?>
</main>

<!-- Подключаем footer -->
<?php require_once __DIR__ . '/../partials/_footer.php'; ?>