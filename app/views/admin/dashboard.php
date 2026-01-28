<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CMS Qyzylorda Times</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .nav-item.active {
            background-color: #D60023;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR (Меню) -->
        <?php require_once __DIR__ . '/../partials/_admin_sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header -->
            <header class="h-16 bg-white shadow flex justify-between items-center px-8 z-10">
                <h2 class="text-xl font-semibold text-gray-800">Шолу (Обзор)</h2>
                <div class="flex gap-4">
                    <a href="/" target="_blank" class="flex items-center gap-2 text-gray-600 hover:text-[#D60023]">
                        <i class="fas fa-external-link-alt"></i> Сайтты көру
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Stat Card 1 -->
                    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Жалпы жаңалықтар</p>
                                <h3 class="text-2xl font-bold">
                                    <?= number_format($stats['total_posts'] ?? 0) ?>
                                </h3>
                            </div>
                            <i class="fas fa-newspaper text-gray-300 text-3xl"></i>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Бүгінгі қаралым</p>
                                <h3 class="text-2xl font-bold">
                                    <?= number_format($stats['today_views'] ?? 0) ?>
                                </h3>
                            </div>
                            <i class="fas fa-eye text-gray-300 text-3xl"></i>
                        </div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Күту пікірлер</p>
                                <h3 class="text-2xl font-bold">
                                    <?= number_format($stats['pending_comments'] ?? 0) ?>
                                </h3>
                            </div>
                            <i class="fas fa-comments text-gray-300 text-3xl"></i>
                        </div>
                    </div>

                    <!-- Stat Card 4 -->
                    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">Барлық пікірлер</p>
                                <h3 class="text-2xl font-bold">
                                    <?= number_format($stats['total_comments'] ?? 0) ?>
                                </h3>
                            </div>
                            <i class="fas fa-comment-alt text-gray-300 text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Latest Posts Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Соңғы қосылғандар</h3>
                        <a href="/admin/posts/create"
                            class="bg-[#D60023] hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Жаңа жаңалық
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Тақырып (Title)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Автор</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Статус</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Дата</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Әрекет</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (isset($recentPosts) && !empty($recentPosts)): ?>
                                    <?php foreach ($recentPosts as $post): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">#
                                                <?= $post['id'] ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900 line-clamp-1">
                                                    <?= htmlspecialchars($post['title_kz'] ?? $post['title_ru']) ?>
                                                </div>
                                                <span class="text-xs text-gray-400">
                                                    <?= !empty($post['title_kz']) && !empty($post['title_ru']) ? 'KZ / RU' : (!empty($post['title_kz']) ? 'KZ' : 'RU') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                <?= htmlspecialchars($post['author_name'] ?? 'Редакция') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php
                                                $statusClass = match ($post['status'] ?? 'draft') {
                                                    'published' => 'bg-green-100 text-green-800',
                                                    'draft' => 'bg-yellow-100 text-yellow-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $statusText = match ($post['status'] ?? 'draft') {
                                                    'published' => 'Жарияланды',
                                                    'draft' => 'Черновик',
                                                    default => 'Белгісіз'
                                                };
                                                ?>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                <?= date('d.m.Y', strtotime($post['created_at'] ?? 'now')) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="/admin/posts/edit/<?= $post['id'] ?>"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/admin/posts/delete/<?= $post['id'] ?>"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Жаңалықты өшіруге сенімдісіз бе?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            Жаңалықтар жоқ
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>

</html>