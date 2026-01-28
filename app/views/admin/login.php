<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - CMS Qyzylorda Times</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md">
        <!-- Логотип -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-2">
                <div
                    class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center text-white text-3xl font-black shadow-lg">
                    Q
                </div>
            </div>
            <h1 class="text-white text-2xl font-bold">Qyzylorda Times</h1>
            <p class="text-gray-400 text-sm">Админ-панель управления контентом</p>
        </div>

        <!-- Форма входа -->
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Кіру / Вход</h2>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="/admin/login" method="POST">
                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        <i class="fas fa-user mr-1"></i> Пайдаланушы аты / Имя пользователя
                    </label>
                    <input type="text" id="username" name="username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
                        placeholder="admin" required autofocus>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        <i class="fas fa-lock mr-1"></i> Құпия сөз / Пароль
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition"
                        placeholder="••••••••" required>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox h-4 w-4 text-red-600 rounded">
                        <span class="ml-2 text-sm text-gray-600">Мені есте сақта / Запомнить меня</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Кіру / Войти
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>©
                    <?= date('Y') ?> Qyzylorda Times
                </p>
                <p class="mt-1">Барлық құқықтар қорғалған / Все права защищены</p>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-4 bg-gray-800 rounded-lg p-4 text-gray-300 text-xs">
            <div class="font-bold mb-2"><i class="fas fa-info-circle mr-1"></i> Demo тіркелгі / Demo аккаунт:</div>
            <div class="font-mono">
                <div>Логин: <span class="text-white">admin</span></div>
                <div>Пароль: <span class="text-white">admin123</span></div>
            </div>
        </div>
    </div>

</body>

</html>