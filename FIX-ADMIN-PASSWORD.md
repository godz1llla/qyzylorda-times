# Исправление пароля админ-аккаунта

## Проблема

Пароль в sample_data.sql был неправильный. Хеш `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi` соответствует паролю **password**, а не **admin123**.

## Решение

Выполните в MySQL:

```bash
sudo mysql -u root qyzylorda_times
```

Затем в MySQL консоли:

```sql
-- Обновить пароль на admin123
UPDATE users 
SET password = '$2y$10$IlrAqSFQh/9bYBwP4Cx15OWcE84kYOoFQgv0eMO9cTtHA8igvZwSW' 
WHERE username = 'admin';

-- Проверить
SELECT username, email, role FROM users WHERE username = 'admin';

EXIT;
```

## Теперь вход работает:

**URL:** http://localhost:8000/admin/login  
**Логин:** admin  
**Пароль:** admin123

---

## Или попробуйте старый пароль:

Если не хотите менять, попробуйте войти с:
- Логин: `admin`
- Пароль: `password`
