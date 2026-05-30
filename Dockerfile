FROM php:8.3-fpm-alpine

ARG APP_ENV=production
ARG APP_DEBUG=false

ENV APP_ENV=${APP_ENV} \
    APP_DEBUG=${APP_DEBUG} \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    linux-headers

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        pcntl \
        exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan storage:link \
    && php artisan optimize \
    && php artisan view:cache \
    && php artisan route:cache \
    && php artisan config:cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache public

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
