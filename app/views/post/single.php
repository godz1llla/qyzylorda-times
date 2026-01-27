<?php
// Страница статьи - Qyzylorda Times
$pageTitle = ($lang === 'kz' ? $post['title_kz'] : $post['title_ru']) . ' - Qyzylorda Times';
$metaDescription = $lang === 'kz' ? $post['excerpt_kz'] : $post['excerpt_ru'];

// Open Graph
$ogTitle = $lang === 'kz' ? $post['title_kz'] : $post['title_ru'];
$ogDescription = $lang === 'kz' ? $post['excerpt_kz'] : $post['excerpt_ru'];
$ogImage = !empty($post['image']) ? 'https://qyzylordatimes.kz/uploads/large/' . $post['image'] : '';
$ogUrl = 'https://qyzylordatimes.kz' . $_SERVER['REQUEST_URI'];

// Подключаем header
require_once __DIR__ . '/../partials/_header.php';
?>

<style>
    /* Стили для типографики статьи */
    .article-content p {
        margin-bottom: 1rem;
        line-height: 1.7;
        font-size: 1.05rem;
        color: #1f2937;
    }

    .article-content h2 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .article-content blockquote {
        border-left: 4px solid #3b82f6;
        background-color: #eff6ff;
        padding: 1rem;
        margin: 1rem 0;
        font-style: italic;
        color: #374151;
    }
</style>

<!-- Подключаем навигацию -->
<?php require_once __DIR__ . '/../partials/_navigation.php'; ?>

<!-- === MAIN ARTICLE CONTAINER === -->
<main class="container mx-auto px-4 py-8">

    <!-- Хлебные крошки -->
    <div class="text-xs text-gray-400 mb-4 uppercase">
        <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?>" class="hover:text-brand-red">
            <?= $lang === 'kz' ? 'Басты' : 'Главная' ?>
        </a>
        <span class="mx-1">|</span>
        <?php if (!empty($post['category_name'])): ?>
            <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $post['category_slug'] ?>" class="hover:text-brand-red">
                <?= htmlspecialchars($post['category_name']) ?>
            </a>
            <span class="mx-1">|</span>
        <?php endif; ?>
        <span class="text-gray-600">
            <?= $lang === 'kz' ? 'Мақала' : 'Статья' ?>
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- LEFT COLUMN (ARTICLE CONTENT) - 75% width -->
        <div class="lg:col-span-3">

            <!-- Заголовок -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-6">
                <?= htmlspecialchars($lang === 'kz' ? $post['title_kz'] : $post['title_ru']) ?>
            </h1>

            <!-- Автор и Дата -->
            <div class="flex items-center gap-4 text-gray-500 text-sm mb-6 border-b pb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="font-medium text-gray-800">
                        <?= htmlspecialchars($post['author_name'] ?? 'Редакция') ?>
                    </span>
                </div>
                <span class="text-gray-300">|</span>
                <span>
                    <?= date('d.m.Y H:i', strtotime($post['published_at'] ?? 'now')) ?>
                </span>
                <span class="ml-auto flex items-center gap-1">
                    <i class="far fa-eye"></i>
                    <?= number_format($post['views'] ?? 0) ?>
                </span>
            </div>

            <!-- Главное фото -->
            <?php if (!empty($post['image'])): ?>
                <figure class="mb-8">
                    <img src="/uploads/large/<?= $post['image'] ?>" class="w-full h-auto rounded-lg shadow-sm"
                        alt="<?= htmlspecialchars($lang === 'kz' ? $post['title_kz'] : $post['title_ru']) ?>">
                    <figcaption class="text-center text-xs text-gray-400 mt-2">
                        © "Qyzylorda Times"
                        <?= $lang === 'kz' ? 'редакциясы' : 'редакция' ?>
                    </figcaption>
                </figure>
            <?php endif; ?>

            <div class="flex gap-6">

                <!-- Соцсети (Слева) -->
                <div class="hidden md:flex flex-col gap-3 w-10 flex-shrink-0 pt-2">
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode($ogUrl) ?>" target="_blank"
                        class="text-green-500 hover:scale-110 transition text-2xl">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://t.me/share/url?url=<?= urlencode($ogUrl) ?>&text=<?= urlencode($ogTitle) ?>"
                        target="_blank" class="text-blue-500 hover:scale-110 transition text-2xl">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a href="#" class="text-black hover:scale-110 transition text-2xl">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="#" class="text-pink-600 hover:scale-110 transition text-2xl">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($ogUrl) ?>" target="_blank"
                        class="text-blue-700 hover:scale-110 transition text-2xl">
                        <i class="fab fa-facebook"></i>
                    </a>
                </div>

                <!-- Текст статьи -->
                <div class="article-content flex-1">
                    <?= $lang === 'kz' ? $post['content_kz'] : $post['content_ru'] ?>
                </div>
            </div>

            <!-- Теги (если есть) -->
            <div class="mt-8 flex gap-2 flex-wrap ml-0 md:ml-16">
                <?php if (!empty($post['category_name'])): ?>
                    <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $post['category_slug'] ?>"
                        class="border border-brand-red text-brand-red px-3 py-1 rounded text-sm hover:bg-brand-red hover:text-white transition">
                        <?= htmlspecialchars($post['category_name']) ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Блок Комментарии: Форма -->
            <div class="bg-white p-6 rounded-lg shadow-sm mt-8 border border-gray-100">
                <h3 class="text-xl font-bold mb-4">
                    <?= $lang === 'kz' ? 'Пікір қалдыру' : 'Оставить комментарий' ?>
                </h3>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="/post/submitComment" method="POST">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

                    <div class="mb-4">
                        <input type="text" name="author_name"
                            class="w-full border border-gray-200 rounded p-3 text-sm focus:outline-none focus:border-brand-red transition bg-gray-50"
                            placeholder="<?= $lang === 'kz' ? 'Атыңыз' : 'Ваше имя' ?>" required>
                    </div>

                    <div class="mb-4">
                        <textarea name="comment_text"
                            class="w-full border border-gray-200 rounded p-3 text-sm focus:outline-none focus:border-brand-red transition h-24 bg-gray-50"
                            placeholder="<?= $lang === 'kz' ? 'Пікіріңізді жазыңыз...' : 'Напишите комментарий...' ?>"
                            required></textarea>
                    </div>

                    <!-- Простая CAPTCHA -->
                    <div class="mb-4">
                        <label class="text-sm text-gray-600 mb-1 block">
                            <?= $lang === 'kz' ? '3 + 4 = ?' : '3 + 4 = ?' ?>
                        </label>
                        <input type="text" name="captcha"
                            class="w-32 border border-gray-200 rounded p-2 text-sm focus:outline-none focus:border-brand-red transition"
                            required>
                    </div>

                    <button type="submit"
                        class="bg-brand-red text-white hover:bg-red-700 px-6 py-2 rounded text-sm font-medium transition">
                        <?= $lang === 'kz' ? 'Жіберу' : 'Отправить' ?>
                    </button>
                    <p class="text-xs text-gray-400 mt-2">
                        *
                        <?= $lang === 'kz' ? 'Пікір модератор тексергеннен кейін жарияланады' : 'Комментарий будет опубликован после проверки модератором' ?>
                    </p>
                </form>
            </div>

            <!-- Блок Комментарии: Список -->
            <div class="bg-white p-6 rounded-lg shadow-sm mt-6 border border-gray-100">
                <h4 class="text-lg font-semibold mb-4">
                    <?= $lang === 'kz' ? 'Пікірлер' : 'Комментарии' ?>
                    (
                    <?= $commentCount ?? 0 ?>)
                </h4>

                <?php if (isset($comments) && !empty($comments)): ?>
                    <div class="space-y-4">
                        <?php foreach ($comments as $comment): ?>
                            <div class="border-b border-gray-100 pb-4 last:border-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <div
                                        class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                        <?= mb_substr($comment['author_name'], 0, 1, 'UTF-8') ?>
                                    </div>
                                    <span class="font-semibold text-gray-800">
                                        <?= htmlspecialchars($comment['author_name']) ?>
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
                                    </span>
                                </div>
                                <p class="text-gray-700 ml-10">
                                    <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-gray-400 text-sm text-center py-8">
                        <?= $lang === 'kz' ? 'Пікірлер жоқ' : 'Нет комментариев' ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- RIGHT COLUMN (SIDEBAR) - 25% width -->
        <aside class="lg:col-span-1">

            <!-- Табы: Последние / Популярные -->
            <div class="flex mb-4">
                <button class="flex-1 bg-brand-red text-white py-3 text-xs font-bold uppercase rounded-l">
                    <?= $lang === 'kz' ? 'Басты жаңалықтар' : 'Последние' ?>
                </button>
                <button
                    class="flex-1 bg-white border border-l-0 text-gray-600 py-3 text-xs font-bold uppercase rounded-r hover:bg-gray-50">
                    <?= $lang === 'kz' ? 'Танымал' : 'Популярные' ?>
                </button>
            </div>

            <!-- Лента новостей справа -->
            <div class="space-y-6">
                <?php if (isset($relatedPosts) && !empty($relatedPosts)): ?>
                    <?php foreach (array_slice($relatedPosts, 0, 5) as $related): ?>
                        <?php
                        $relTitle = $lang === 'kz' ? $related['title_kz'] : $related['title_ru'];
                        $relSlug = $lang === 'kz' ? $related['slug_kz'] : $related['slug_ru'];
                        $relCategory = $related['category_slug'] ?? 'aqparat';
                        ?>
                        <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?><?= $relCategory ?>/<?= $relSlug ?>"
                            class="group cursor-pointer block">
                            <h4
                                class="font-medium text-gray-800 text-sm leading-snug group-hover:text-brand-red transition mb-1">
                                <?= htmlspecialchars($relTitle) ?>
                            </h4>
                            <div class="text-xs text-gray-400">
                                <?= date('d.m.Y H:i', strtotime($related['published_at'] ?? 'now')) ?>
                            </div>
                            <div class="border-b border-gray-200 mt-3"></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-gray-400 text-sm text-center py-4">
                        <?= $lang === 'kz' ? 'Ұқсас жаңалықтар жоқ' : 'Нет похожих новостей' ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Кнопка Все новости -->
            <div class="mt-6">
                <a href="/<?= $lang === 'ru' ? 'ru/' : '' ?>"
                    class="block w-full bg-red-50 text-brand-red text-center py-3 rounded font-bold text-sm hover:bg-red-100 transition">
                    <?= $lang === 'kz' ? 'Барлық жаңалықтар' : 'Все новости' ?>
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Рекламный баннер справа -->
            <div class="mt-8">
                <div class="bg-gray-200 w-full h-64 flex items-center justify-center text-gray-400 rounded">
                    <?= $lang === 'kz' ? 'Жарнама орны' : 'Место для рекламы' ?>
                </div>
            </div>

        </aside>
    </div>
</main>

<!-- Подключаем footer -->
<?php require_once __DIR__ . '/../partials/_footer.php'; ?>