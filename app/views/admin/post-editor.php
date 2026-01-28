<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($post) ? 'Редактировать' : 'Новая' ?> мақала - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .wysiwyg-toolbar {
            background: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .wysiwyg-content {
            min-height: 300px;
            padding: 1rem;
            outline: none;
        }

        .tab-btn.active {
            border-bottom: 2px solid #D60023;
            color: #D60023;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Шапка (упрощенная) -->
    <header class="bg-[#1F1F1F] text-white h-14 flex items-center justify-between px-6">
        <div class="font-bold flex items-center gap-2">
            <a href="/admin" class="text-gray-400 hover:text-white"><i class="fas fa-arrow-left"></i> Артқа</a>
            <span class="mx-2">|</span>
            <span><?= isset($post) ? 'Мақаланы өзгерту' : 'Жаңа мақала қосу' ?></span>
        </div>
        <button type="submit" form="post-form" class="text-sm bg-red-600 hover:bg-red-700 px-4 py-1 rounded">
            Сақтау (Save)
        </button>
    </header>

    <div class="container mx-auto p-6">

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

        <form id="post-form" action="<?= isset($post) ? '/admin/posts/update/' . $post['id'] : '/admin/posts/store' ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Левая колонка (70%) - Контент -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Блок заголовка и контента с Табами -->
                <div class="bg-white shadow rounded-lg overflow-hidden">

                    <!-- Tabs Header -->
                    <div class="flex border-b border-gray-200">
                        <button type="button" id="tab-btn-kz"
                                class="tab-btn active px-6 py-3 w-1/2 text-sm uppercase transition bg-gray-50 hover:bg-white"
                                onclick="switchTab('kz')">
                            🇰🇿 Қазақша (Негізгі)
                        </button>
                        <button type="button" id="tab-btn-ru"
                                class="tab-btn px-6 py-3 w-1/2 text-sm uppercase transition bg-gray-50 hover:bg-white text-gray-500"
                                onclick="switchTab('ru')">
                            🇷🇺 Русский (Перевод)
                        </button>
                    </div>

                    <div class="p-6">

                        <!-- KZ TAB -->
                        <div id="tab-kz" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Тақырып (QAZ)</label>
                                <input type="text" name="title_kz"
                                       value="<?= htmlspecialchars($post['title_kz'] ?? '') ?>"
                                       class="w-full border border-gray-300 rounded p-2 focus:ring-red-500 focus:border-red-500"
                                       placeholder="Жаңалық тақырыбын жазыңыз...">
                            </div>

                            <div class="border border-gray-300 rounded">
                                <div class="wysiwyg-toolbar">
                                    <button type="button" class="p-1 hover:bg-gray-200 rounded"><i class="fas fa-bold"></i></button>
                                    <button type="button" class="p-1 hover:bg-gray-200 rounded"><i class="fas fa-italic"></i></button>
                                    <button type="button" class="p-1 hover:bg-gray-200 rounded"><i class="fas fa-link"></i></button>
                                    <button type="button" class="p-1 hover:bg-gray-200 rounded"><i class="fas fa-image"></i></button>
                                    <div class="flex-grow"></div>
                                    <button type="button" class="text-xs bg-gray-200 px-2 rounded">HTML</button>
                                </div>
                                <textarea name="content_kz" class="w-full wysiwyg-content resize-y"
                                          placeholder="Жаңалықтың мәтіні..."><?= htmlspecialchars($post['content_kz'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- RU TAB (Скрыт по умолчанию) -->
                        <div id="tab-ru" class="space-y-4 hidden">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Заголовок (RU)</label>
                                <input type="text" name="title_ru"
                                       value="<?= htmlspecialchars($post['title_ru'] ?? '') ?>"
                                       class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Введите заголовок...">
                            </div>

                            <div class="border border-gray-300 rounded">
                                <div class="wysiwyg-toolbar">
                                    <button type="button" class="p-1 hover:bg-gray-200 rounded"><i class="fas fa-bold"></i></button>
                                    <button type="button" class="p-1 hover:bg-gray-200 rounded"><i class="fas fa-italic"></i></button>
                                    <button type="button" class="p-1 hover:bg-gray-200 rounded"><i class="fas fa-link"></i></button>
                                </div>
                                <textarea name="content_ru" class="w-full wysiwyg-content resize-y"
                                          placeholder="Текст новости..."><?= htmlspecialchars($post['content_ru'] ?? '') ?></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SEO Section -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">SEO параметрлер</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="slug_kz" value="<?= htmlspecialchars($post['slug_kz'] ?? '') ?>"
                               class="border rounded p-2 text-sm" placeholder="URL-сілтеме KZ (автоматты)">
                        <input type="text" name="slug_ru" value="<?= htmlspecialchars($post['slug_ru'] ?? '') ?>"
                               class="border rounded p-2 text-sm" placeholder="URL-сілтеме RU (автоматты)">
                    </div>
                </div>

            </div>

            <!-- Правая колонка (30%) - Свойства -->
            <div class="space-y-6">

                <!-- Publish Box -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-bold text-sm uppercase text-gray-500 mb-3">Жариялау</h3>

                    <div class="mb-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status" value="published"
                                   <?= (!isset($post) || $post['status'] === 'published') ? 'checked' : '' ?>
                                   class="text-red-600 focus:ring-red-500 h-4 w-4">
                            <span class="text-sm">Сайтқа шығару (Published)</span>
                        </label>
                    </div>

                    <button type="submit" name="action" value="publish"
                            class="w-full bg-[#D60023] hover:bg-red-700 text-white font-bold py-2 rounded shadow">
                        Жариялау
                    </button>
                    <button type="submit" name="action" value="draft"
                            class="w-full mt-2 bg-gray-200 hover:bg-gray-300 text-gray-700 py-1 text-sm rounded">
                        Черновикке сақтау
                    </button>
                </div>

                <!-- Категории -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-bold text-sm uppercase text-gray-500 mb-3">Категория</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-100 p-2 text-sm">
                        <?php if (isset($categories) && !empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="category_id" value="<?= $category['id'] ?>"
                                           <?= (isset($post) && $post['category_id'] == $category['id']) ? 'checked' : '' ?>>
                                    <span><?= htmlspecialchars($category['name_kz']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Main Image -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-bold text-sm uppercase text-gray-500 mb-3">Басты сурет</h3>
                    <?php if (isset($post) && !empty($post['image'])): ?>
                        <img src="/uploads/medium/<?= $post['image'] ?>" class="w-full mb-2 rounded" alt="Current">
                    <?php endif; ?>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg h-32 flex items-center justify-center cursor-pointer hover:border-red-400 bg-gray-50"
                         onclick="document.getElementById('image-upload').click()">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                            <p class="text-xs mt-1">Жүктеу үшін басыңыз</p>
                        </div>
                    </div>
                    <input type="file" id="image-upload" name="image" accept="image/*" class="hidden">
                </div>

                <!-- Attr Checkboxes -->
                <div class="bg-white shadow rounded-lg p-4 text-sm">
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer border-b pb-2">
                        <input type="checkbox" name="is_featured" value="1"
                               <?= (isset($post) && $post['is_featured']) ? 'checked' : '' ?>
                               class="text-red-600 h-4 w-4">
                        <span>Басты жаңалық (Hero)</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer pt-2">
                        <input type="checkbox" name="is_announcement" value="1"
                               <?= (isset($post) && $post['is_announcement']) ? 'checked' : '' ?>
                               class="text-blue-600 h-4 w-4">
                        <span>"Анонс" блогына қосу</span>
                    </label>
                </div>

            </div>
        </form>
    </div>

    <!-- JS для переключения вкладок -->
    <script>
        function switchTab(lang) {
            document.getElementById('tab-kz').classList.add('hidden');
            document.getElementById('tab-ru').classList.add('hidden');

            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active', 'text-[#D60023]', 'font-bold');
                btn.classList.add('text-gray-500');
            });

            document.getElementById('tab-' + lang).classList.remove('hidden');

            const activeBtn = document.getElementById('tab-btn-' + lang);
            activeBtn.classList.add('active', 'text-[#D60023]', 'font-bold');
            activeBtn.classList.remove('text-gray-500');
        }
    </script>
</body>

</html>
