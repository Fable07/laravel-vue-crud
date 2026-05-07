FROM php:8.2-fpm

# Install system dependencies + Node.js 20 + nginx + envsubst
RUN apt-get update && apt-get install -y curl git zip unzip libzip-dev libpng-dev libxml2-dev libonig-dev nginx gettext-base \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath xml opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# OPcache config for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package files and install
COPY package.json package-lock.json ./
RUN npm ci

# Copy rest of application
COPY . .

# Copy docker config files
COPY docker/nginx.conf /etc/nginx/nginx-app.conf.template
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf

# Build frontend assets
RUN npm run build

EXPOSE 8000

CMD ["sh", "-c", \
    "mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache \
    && php artisan migrate --force \
    && php artisan storage:link --force 2>/dev/null || true \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && envsubst '${PORT}' < /etc/nginx/nginx-app.conf.template > /etc/nginx/sites-available/default \
    && php-fpm -D \
    && (php artisan queue:work --sleep=3 --tries=3 --timeout=60 &) \
    && nginx -g 'daemon off;'"]
