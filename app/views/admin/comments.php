<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пікірлерді басқару - CMS</title>
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
        $activeTab = 'comments';
        require_once __DIR__ . '/../partials/_admin_sidebar.php';
        ?>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header -->
            <header class="h-16 bg-white shadow flex justify-between items-center px-8 z-10">
                <h2 class="text-xl font-semibold text-gray-800">Пікірлерді басқару / Управление комментариями</h2>
                <div class="flex gap-3 text-sm">
                    <a href="?filter=pending"
                        class="<?= (!isset($_GET['filter']) || $_GET['filter'] === 'pending') ? 'bg-yellow-500 text-white' : 'bg-gray-200' ?> px-4 py-2 rounded">
                        Күту <span class="font-bold">(
                            <?= $pendingCount ?? 0 ?>)
                        </span>
                    </a>
                    <a href="?filter=approved"
                        class="<?= (isset($_GET['filter']) && $_GET['filter'] === 'approved') ? 'bg-green-500 text-white' : 'bg-gray-200' ?> px-4 py-2 rounded">
                        Қабылданды
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

                <!-- Comments List -->
                <div class="space-y-4">
                    <?php if (isset($comments) && !empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div
                                class="bg-white shadow rounded-lg p-6 <?= $comment['status'] === 'approved' ? 'border-l-4 border-green-500' : 'border-l-4 border-yellow-500' ?>">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center gap-3 mb-2">
                                            <div
                                                class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                                                <?= mb_substr($comment['author_name'], 0, 1, 'UTF-8') ?>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800">
                                                    <?= htmlspecialchars($comment['author_name']) ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <i class="fas fa-clock"></i>
                                                    <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
                                                    <span class="ml-3">
                                                        <i class="fas fa-globe"></i>
                                                        IP:
                                                        <?= htmlspecialchars($comment['ip_address']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml-13">
                                            <a href="/<?= $comment['post_slug_kz'] ?? '' ?>" target="_blank"
                                                class="text-sm text-blue-600 hover:underline">
                                                <i class="fas fa-link"></i>
                                                <?= htmlspecialchars($comment['post_title'] ?? 'Пост') ?>
                                            </a>
                                        </div>
                                    </div>

                                    <div>
                                        <?php if ($comment['status'] === 'pending'): ?>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">
                                                Күту
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                Қабылданды
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="ml-13 bg-gray-50 rounded p-4 mb-4">
                                    <p class="text-gray-700 leading-relaxed">
                                        <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
                                    </p>
                                </div>

                                <div class="ml-13 flex gap-2">
                                    <?php if ($comment['status'] === 'pending'): ?>
                                        <a href="/admin/comments/approve/<?= $comment['id'] ?>"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm flex items-center gap-1">
                                            <i class="fas fa-check"></i>
                                            Қабылдау
                                        </a>
                                    <?php endif; ?>
                                    <a href="/admin/comments/reject/<?= $comment['id'] ?>"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm flex items-center gap-1"
                                        onclick="return confirm('Пікірді өшіруге сенімдісіз бе?')">
                                        <i class="fas fa-trash"></i>
                                        Өшіру
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-white shadow rounded-lg p-12 text-center">
                            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">Пікірлер жоқ</h3>
                            <p class="text-gray-500">Бұл санатта пікірлер жоқ</p>
                        </div>
                    <?php endif; ?>
                </div>

            </main>
        </div>
    </div>
</body>

</html>