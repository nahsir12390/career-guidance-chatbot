FROM php:8.4-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm \
    zip \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl gd zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN mkdir -p /var/data \
    && touch /var/data/database.sqlite \
    && composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader --no-dev \
    && npm install \
    && npm run build \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force

EXPOSE 8000

CMD ["sh", "-c", "mkdir -p /var/data && touch /var/data/database.sqlite && (php artisan key:generate --force --no-interaction || true) && php artisan migrate --force && php artisan serve --host 0.0.0.0 --port ${PORT:-8000}"]
