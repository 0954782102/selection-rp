# selection-rp

## Netlify Deployment з MySQL підтримкою

У цьому репозиторії додано Netlify-сумісний набір:

### Структура

- `netlify-site/` — статичний фронтенд для публікації на Netlify
- `netlify-functions/` — Node.js серверлес-функції для обробки API запросів
- `personal/` — ваш оригінальний PHP-код (зберігається без змін)

### Як це працює

1. Netlify розгортає статичні файли з папки `netlify-site/`
2. Запити до API йдуть на Netlify Functions (`/.netlify/functions/api`)
3. Функції підключаються до вашої MySQL БД через драйвер `mysql2`

### Налаштування

На платформі Netlify встановіть змінні середовища:

```
DB_HOST = localhost
DB_USER = user43104
DB_PASS = 4wJVPki5EPnA
DB_NAME = user43104
```

### API Endpoints

- `POST /.netlify/functions/api?action=login` — авторизація
- `POST /.netlify/functions/api?action=profile` — профіль користувача
- `POST /.netlify/functions/api?action=donate` — додати донат
- `POST /.netlify/functions/api?action=roulette` — крутити рулетку
- `POST /.netlify/functions/api?action=items` — список предметів рулетки
- `POST /.netlify/functions/api?action=change_password` — змінити пароль

### Обробка запітів

Всі запити приймають JSON:

```json
{
  "action": "login",
  "nickname": "demo",
  "password": "demo123"
}
```

Відповідь:

```json
{
  "user": { ...дані користувача без пароля... }
}
```

### Оригінальні файли

Ваш PHP-код у папці `personal/` залишаються без змін. Вони потрібні для майбутнього перенесення логіки на серверлес-функції.

