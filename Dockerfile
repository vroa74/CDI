FROM dunglas/frankenphp:1-php8.3

WORKDIR /app

RUN install-php-extensions \
    bcmath \
    gd \
    intl \
    opcache \
    pcntl \
    pdo_mysql \
    pdo_sqlite \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

COPY . .

RUN composer dump-autoload --no-dev --no-scripts --optimize \
    && mkdir -p bootstrap/cache storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && php artisan package:discover --ansi \
    && install -m 0644 vendor/laravel/octane/src/Commands/stubs/frankenphp-worker.php public/frankenphp-worker.php \
    && mkdir -p /var/www \
    && chown -R www-data:www-data bootstrap/cache storage /var/www /config/caddy /data/caddy

ENV HOME=/var/www

USER www-data

EXPOSE 8000

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
CMD ["--workers=2", "--max-requests=500"]
