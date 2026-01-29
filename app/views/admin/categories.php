<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категории - CMS Qyzylorda Times</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../partials/_admin_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-folder mr-2"></i>Категориялар / Категории
                </h1>
                <button onclick="openCreateModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-plus mr-2"></i>Жаңа категория / Новая категория
                </button>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Categories Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Аты (KZ)</th>
                            <th class="px-6 py-3 text-left">Название (RU)</th>
                            <th class="px-6 py-3 text-left">Slug KZ</th>
                            <th class="px-6 py-3 text-left">Slug RU</th>
                            <th class="px-6 py-3 text-left">Реті</th>
                            <th class="px-6 py-3 text-center">Әрекеттер</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Категориялар табылмады
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <?= $category['id'] ?>
                                    </td>
                                    <td class="px-6 py-4 font-medium">
                                        <?= htmlspecialchars($category['name_kz']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($category['name_ru']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?= htmlspecialchars($category['slug_kz']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?= htmlspecialchars($category['slug_ru']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= $category['sort_order'] ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick='editCategory(<?= json_encode($category) ?>)'
                                            class="text-blue-600 hover:text-blue-800 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="/admin/categories/delete/<?= $category['id'] ?>"
                                            onclick="return confirm('Өшіргіңіз келе ме?')"
                                            class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="categoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
            <h2 id="modalTitle" class="text-2xl font-bold mb-6">Жаңа категория</h2>

            <form id="categoryForm" method="POST" action="/admin/categories/create">
                <input type="hidden" id="categoryId" name="category_id">

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Аты (Қазақша)</label>
                        <input type="text" name="name_kz" id="name_kz" required
                            class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Название (Русский)</label>
                        <input type="text" name="name_ru" id="name_ru"
                            class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Slug (KZ)</label>
                        <input type="text" name="slug_kz" id="slug_kz" required
                            class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Slug (RU)</label>
                        <input type="text" name="slug_ru" id="slug_ru"
                            class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Сипаттама (KZ)</label>
                    <textarea name="description_kz" id="description_kz" rows="2"
                        class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Описание (RU)</label>
                    <textarea name="description_ru" id="description_ru" rows="2"
                        class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Реті / Порядок сортировки</label>
                    <input type="number" name="sort_order" id="sort_order" value="0"
                        class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">
                        Болдырмау / Отмена
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                        Сақтау / Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Жаңа категория';
            document.getElementById('categoryForm').action = '/admin/categories/create';
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function editCategory(category) {
            document.getElementById('modalTitle').textContent = 'Категорияны өзгерту';
            document.getElementById('categoryForm').action = '/admin/categories/update/' + category.id;
            document.getElementById('name_kz').value = category.name_kz;
            document.getElementById('name_ru').value = category.name_ru;
            document.getElementById('slug_kz').value = category.slug_kz;
            document.getElementById('slug_ru').value = category.slug_ru;
            document.getElementById('description_kz').value = category.description_kz || '';
            document.getElementById('description_ru').value = category.description_ru || '';
            document.getElementById('sort_order').value = category.sort_order;
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('categoryModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

</body>

</html>