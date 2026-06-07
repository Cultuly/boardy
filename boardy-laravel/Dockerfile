FROM php:8.3-fpm

# 1. Системные пакеты + Node.js 20.x (для Vite/React)
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev libssl-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2. PHP-расширения
RUN docker-php-ext-install pdo_mysql mbstring zip opcache bcmath
RUN pecl install redis && docker-php-ext-enable redis

# 3. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. СНАЧАЛА манифесты зависимостей (кеширование слоёв — Задание 2)
COPY composer.json composer.lock package.json package-lock.json* ./
RUN composer install --no-dev --no-scripts --no-autoloader
RUN npm ci

# 5. ПОТОМ весь код
COPY . .

# 6. Сборка фронтенда и оптимизация
RUN npm run build
RUN composer dump-autoload --optimize

# 7. Права (включая public/build — туда Vite складывает результат!)
RUN chown -R www-data:www-data storage bootstrap/cache public/build
RUN chmod -R 775 storage bootstrap/cache public/build

EXPOSE 9000
CMD ["php-fpm"]