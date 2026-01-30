<!DOCTYPE html>
<html lang="<?= $lang === 'kz' ? 'kk' : 'ru' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $pageTitle ?? 'Qyzylorda Times - Аймақтық ақпарат агенттігі' ?>
    </title>

    <!-- Meta Tags -->
    <?php if (isset($metaDescription)): ?>
        <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php endif; ?>

    <!-- Open Graph -->
    <?php if (isset($ogTitle)): ?>
        <meta property="og:title" content="<?= htmlspecialchars($ogTitle) ?>">
        <meta property="og:description" content="<?= htmlspecialchars($ogDescription ?? '') ?>">
        <meta property="og:image" content="<?= $ogImage ?? '' ?>">
        <meta property="og:url" content="<?= $ogUrl ?? '' ?>">
    <?php endif; ?>

    <!-- Подключение Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Иконки FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Шрифт Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .brand-red {
            background-color: #D60023;
        }

        /* Основной красный цвет */
        .text-brand-red {
            color: #D60023;
        }

        .news-gradient {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- === ТОП-БАР (РЕКЛАМА И ИНФО) === -->
    <div class="bg-white border-b border-gray-200 py-3 hidden md:block">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <!-- Левая часть: Баннер/QR -->
            <div class="flex items-center space-x-4">
                <div
                    class="border-2 border-brand-red rounded-xl p-3 flex items-center gap-2 shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <i class="fas fa-qrcode text-3xl text-brand-red"></i>
                    <div class="text-xs leading-tight">
                        <strong>Kaspi QR</strong><br>
                        <?= $lang === 'kz' ? 'Газетке жазылу' : 'Подписка на газету' ?>
                    </div>
                </div>
            </div>

            <!-- Правая часть: Инфо (Кызылорда) -->
            <div
                class="flex items-center bg-gradient-to-r from-green-900 to-green-800 text-white rounded-xl px-5 py-3 space-x-6 text-sm shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone-alt"></i>
                    <span>8 (7242) 27-01-01</span>
                </div>
                <div class="hidden lg:block text-xs text-green-200">
                    ЖШС "Syrdarya Media"<br>Қызылорда қ., Бейбарыс сұлтан, 1
                </div>
            </div>
        </div>
    </div>

    <!-- === HEADER (ШАПКА) === -->
    <header class="brand-red text-white">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-end">
                <!-- Логотип -->
                <a href="<?= $lang === 'ru' ? '/ru/' : '/' ?>"
                    class="flex items-center gap-3 hover:opacity-90 transition">
                    <img src="/assets/logo.png" alt="Qyzylorda Times Logo" class="w-20 h-20 object-contain">
                    <div>
                        <h1 class="text-3xl font-black tracking-wide uppercase leading-none">Qyzylorda</h1>
                        <span class="text-lg font-light tracking-[0.2em] uppercase">Times.kz</span>
                    </div>
                </a>

                <!-- Погода и Курс -->
                <div class="hidden lg:flex gap-6 text-sm font-medium">
                    <div class="text-right">
                        <div class="opacity-70">Қызылорда</div>
                        <div class="text-xl flex items-center gap-2">
                            <i
                                class="fas fa-<?= isset($weather['icon']) && strpos($weather['icon'], '01') !== false ? 'sun' : 'snowflake' ?>"></i>
                            <?= number_format($weather['temperature'] ?? -8.5, 1) ?>°C
                        </div>
                        <div class="text-xs opacity-70">
                            <?= date('d.m.Y') ?>
                        </div>
                    </div>
                    <div class="h-10 w-[1px] bg-white opacity-30"></div>
                    <div class="text-right">
                        <div>USD:
                            <?= number_format($currency['usd_rate'] ?? 501.50, 2) ?>
                        </div>
                        <div>EUR:
                            <?= number_format($currency['eur_rate'] ?? 545.20, 2) ?>
                        </div>
                        <div>RUB:
                            <?= number_format($currency['rub_rate'] ?? 5.40, 2) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>