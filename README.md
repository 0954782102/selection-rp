# selection-rp

## Netlify deployment

У цьому репозиторії додано новий Netlify-сумісний фронтенд у папці `netlify-site/` та серверлес-функції у `netlify-functions/`.

1. У Netlify встановіть `publish` директорію: `netlify-site`
2. Налаштуйте `functions` директорію: `netlify-functions`
3. Додайте змінні середовища в налаштуваннях сайту:
   - `SUPABASE_URL`
   - `SUPABASE_KEY`
   - `SUPABASE_TABLE_USERS`
   - `SUPABASE_TABLE_ITEMS`
   - `SUPABASE_TABLE_DONATIONS` (опціонально)
   - `PASSWORD_HASH` = `plain` або `sha256`
   - `ROULETTE_COST` = `25`

Якщо змінні не встановлені, сайт працюватиме з локальними тестовими даними (демо-користувач: `demo/demo123`).
