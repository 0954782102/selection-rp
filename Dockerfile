# Використовуємо офіційний образ PHP з Apache
FROM php:8.2-apache

# 1. Встановлюємо розширення mysqli та активуємо його
# Це вирішує проблему "Class 'mysqli' not found"
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 2. (Опціонально) Якщо XenForo або ваші скрипти потребують інших розширень (наприклад, для зображень або пошти), 
# можна розкоментувати рядок нижче:
# RUN apt-get update && apt-get install -y libpng-dev && docker-php-ext-install gd

# 3. Копіюємо всі файли вашого проекту в контейнер
COPY . /var/www/html/

# 4. Встановлюємо правильні права доступу, щоб Apache міг читати файли
# Це важливо для запобігання помилок 403 Forbidden або білих екранів
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 5. Відкриваємо порт 80
EXPOSE 80

# Apache запускається автоматично, ніяких додаткових команд не потрібно
