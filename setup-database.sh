#!/bin/bash

# Скрипт настройки базы данных для Qyzylorda Times

echo "=== Настройка базы данных Qyzylorda Times ==="
echo ""

# Цвета для вывода
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Функция для вывода ошибок
error() {
    echo -e "${RED}❌ Ошибка: $1${NC}"
    exit 1
}

# Функция для вывода успеха
success() {
    echo -e "${GREEN}✅ $1${NC}"
}

# Функция для вывода предупреждений
warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Проверка наличия MySQL
if ! command -v mysql &> /dev/null; then
    error "MySQL не установлен. Установите MySQL: sudo apt install mysql-server"
fi

success "MySQL найден: $(mysql --version)"
echo ""

# Переменные
DB_NAME="qyzylorda_times"
DB_USER="root"

# Запрос пароля MySQL
echo "Введите пароль MySQL для пользователя root (оставьте пустым если нет пароля):"
read -s DB_PASS
echo ""

# Создание БД
echo "1. Создание базы данных '$DB_NAME'..."

if [ -z "$DB_PASS" ]; then
    # Без пароля
    mysql -u "$DB_USER" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
    MYSQL_CMD="mysql -u $DB_USER"
else
    # С паролем
    mysql -u "$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
    MYSQL_CMD="mysql -u $DB_USER -p$DB_PASS"
fi

if [ $? -eq 0 ]; then
    success "База данных создана"
else
    # Попробуем через sudo
    warning "Попытка через sudo..."
    sudo mysql -u root -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        success "База данных создана через sudo"
        MYSQL_CMD="sudo mysql -u root"
    else
        error "Не удалось создать базу данных. Проверьте права доступа к MySQL."
    fi
fi

echo ""

# Импорт схемы
echo "2. Импорт схемы базы данных..."

if [ -f "database/schema.sql" ]; then
    $MYSQL_CMD "$DB_NAME" < database/schema.sql 2>/dev/null
    
    if [ $? -eq 0 ]; then
        success "Схема импортирована"
    else
        error "Не удалось импортировать схему"
    fi
else
    error "Файл database/schema.sql не найден"
fi

echo ""

# Импорт демо-данных
echo "3. Импорт демо-данных..."

if [ -f "database/sample_data.sql" ]; then
    $MYSQL_CMD "$DB_NAME" < database/sample_data.sql 2>/dev/null
    
    if [ $? -eq 0 ]; then
        success "Демо-данные импортированы"
    else
        warning "Не удалось импортировать демо-данные (возможно уже существуют)"
    fi
else
    warning "Файл database/sample_data.sql не найден (пропускаем)"
fi

echo ""

# Проверка таблиц
echo "4. Проверка созданных таблиц..."
TABLE_COUNT=$($MYSQL_CMD -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NAME';" 2>/dev/null)

if [ $? -eq 0 ]; then
    success "Создано таблиц: $TABLE_COUNT"
else
    warning "Не удалось проверить таблицы"
fi

echo ""
echo "=== Настройка завершена! ==="
echo ""
echo "База данных: $DB_NAME"
echo "Пользователь: $DB_USER"
echo ""
echo "Следующие шаги:"
echo "1. Обновите config/database.php с вашими данными MySQL"
echo "2. Запустите сервер: cd public && php -S localhost:8000"
echo "3. Откройте в браузере: http://localhost:8000"
echo ""
success "Готово!"
