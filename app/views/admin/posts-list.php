<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Барлық жаңалықтар - CMS</title>
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

        <!-- SIDEBAR -->
        <?php
        $activeTab = 'posts';
        require_once __DIR__ . '/../partials/_admin_sidebar.php';
        ?>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header -->
            <header class="h-16 bg-white shadow flex justify-between items-center px-8 z-10">
                <h2 class="text-xl font-semibold text-gray-800">Барлық жаңалықтар / Все новости</h2>
                <div class="flex gap-4">
                    <a href="/admin/posts/create"
                        class="bg-[#D60023] hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Жаңа жаңалық
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- Posts Table -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Сурет</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Тақырып</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Категория</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Автор</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Статус</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Көрініс</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Дата</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Әрекет</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (isset($posts) && !empty($posts)): ?>
                                    <?php foreach ($posts as $post): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">#
                                                <?= $post['id'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if (!empty($post['image'])): ?>
                                                    <img src="/uploads/thumbnail/<?= $post['image'] ?>"
                                                        class="w-12 h-12 object-cover rounded" alt="Thumbnail">
                                                <?php else: ?>
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 max-w-xs">
                                                <div class="font-medium text-gray-900 line-clamp-2">
                                                    <?= htmlspecialchars($post['title_kz'] ?? $post['title_ru']) ?>
                                                </div>
                                                <div class="flex gap-2 mt-1">
                                                    <?php if (!empty($post['title_kz'])): ?>
                                                        <span
                                                            class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">KZ</span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($post['title_ru'])): ?>
                                                        <span
                                                            class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">RU</span>
                                                    <?php endif; ?>
                                                    <?php if ($post['is_featured'] ?? 0): ?>
                                                        <span
                                                            class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">HERO</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                <?= htmlspecialchars($post['category_name'] ?? '-') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                <?= htmlspecialchars($post['author_name'] ?? 'N/A') ?>
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
                                                <i class="fas fa-eye"></i>
                                                <?= number_format($post['views'] ?? 0) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">
                                                <?= date('d.m.Y', strtotime($post['created_at'] ?? 'now')) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="/admin/posts/edit/<?= $post['id'] ?>"
                                                    class="text-blue-600 hover:text-blue-900 mr-3" title="Өзгерту">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/admin/posts/delete/<?= $post['id'] ?>"
                                                    class="text-red-600 hover:text-red-900" title="Өшіру"
                                                    onclick="return confirm('Жаңалықты өшіруге сенімдісіз бе?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                            <p>Жаңалықтар жоқ</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Бет
                                <?= $currentPage ?? 1 ?> /
                                <?= $totalPages ?>
                            </div>
                            <div class="flex gap-2">
                                <?php if ($currentPage > 1): ?>
                                    <a href="?page=<?= $currentPage - 1 ?>"
                                        class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                        Артқа
                                    </a>
                                <?php endif; ?>
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?page=<?= $currentPage + 1 ?>"
                                        class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                        Алға
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </main>
        </div>
    </div>
</body>

</html>