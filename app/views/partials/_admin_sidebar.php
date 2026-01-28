<!-- SIDEBAR (Меню) -->
<aside class="w-64 bg-[#1F1F1F] text-gray-300 flex flex-col shadow-xl">
    <!-- Branding -->
    <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-black">
        <span class="text-white font-bold tracking-widest uppercase">
            QT CMS <span class="text-red-600 text-xs">v1.0</span>
        </span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="/admin"
                    class="nav-item <?= isset($activeTab) && $activeTab === 'dashboard' ? 'active' : '' ?> flex items-center px-6 py-3 hover:bg-gray-800 transition">
                    <i class="fas fa-chart-line w-6 text-center"></i>
                    <span class="ml-2">Басты бет (Dashboard)</span>
                </a>
            </li>

            <!-- Posts Section -->
            <li class="px-6 py-2 text-xs font-bold uppercase text-gray-500 tracking-wider mt-4">Контент</li>
            <li>
                <a href="/admin/posts"
                    class="nav-item <?= isset($activeTab) && $activeTab === 'posts' ? 'active' : '' ?> flex items-center px-6 py-3 hover:bg-gray-800 transition hover:text-white">
                    <i class="fas fa-newspaper w-6 text-center"></i>
                    <span class="ml-2">Жаңалықтар (Новости)</span>
                </a>
            </li>
            <li>
                <a href="/admin/comments"
                    class="nav-item <?= isset($activeTab) && $activeTab === 'comments' ? 'active' : '' ?> flex items-center px-6 py-3 hover:bg-gray-800 transition hover:text-white">
                    <i class="fas fa-comments w-6 text-center"></i>
                    <span class="ml-2">Пікірлер (Комментарии)</span>
                    <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                        <span class="ml-auto bg-red-600 text-white text-xs rounded-full px-2 py-1">
                            <?= $pendingCount ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="/admin/categories"
                    class="nav-item <?= isset($activeTab) && $activeTab === 'categories' ? 'active' : '' ?> flex items-center px-6 py-3 hover:bg-gray-800 transition hover:text-white">
                    <i class="fas fa-tags w-6 text-center"></i>
                    <span class="ml-2">Категориялар</span>
                </a>
            </li>
            <li>
                <a href="/admin/media"
                    class="nav-item <?= isset($activeTab) && $activeTab === 'media' ? 'active' : '' ?> flex items-center px-6 py-3 hover:bg-gray-800 transition hover:text-white">
                    <i class="fas fa-photo-video w-6 text-center"></i>
                    <span class="ml-2">Медиа-файлдар</span>
                </a>
            </li>

            <!-- System Section -->
            <li class="px-6 py-2 text-xs font-bold uppercase text-gray-500 tracking-wider mt-4">Жүйе</li>
            <li>
                <a href="/admin/users"
                    class="nav-item <?= isset($activeTab) && $activeTab === 'users' ? 'active' : '' ?> flex items-center px-6 py-3 hover:bg-gray-800 transition hover:text-white">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="ml-2">Администраторлар</span>
                </a>
            </li>
            <li>
                <a href="/admin/settings"
                    class="nav-item <?= isset($activeTab) && $activeTab === 'settings' ? 'active' : '' ?> flex items-center px-6 py-3 hover:bg-gray-800 transition hover:text-white">
                    <i class="fas fa-cogs w-6 text-center"></i>
                    <span class="ml-2">Настройки</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Info -->
    <div class="border-t border-gray-700 p-4 bg-gray-900">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                <?= mb_substr($_SESSION['admin_full_name'] ?? 'A', 0, 1, 'UTF-8') ?>
            </div>
            <div>
                <div class="text-white text-sm font-medium">
                    <?= htmlspecialchars($_SESSION['admin_full_name'] ?? 'Admin User') ?>
                </div>
                <a href="/admin/logout" class="text-xs text-red-500 hover:text-red-400">Шығу (Logout)</a>
            </div>
        </div>
    </div>
</aside>