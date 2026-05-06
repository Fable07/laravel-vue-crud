FROM php:8.2-cli

# Install system dependencies + Node.js 20
RUN apt-get update && apt-get install -y curl git zip unzip libzip-dev libpng-dev libxml2-dev libonig-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for layer caching
COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/root/.composer \
    composer install --no-dev --optimize-autoloader --no-scripts

# Copy package files and install
COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm \
    npm ci

# Copy rest of application
COPY . .

# Build frontend assets
RUN npm run build

EXPOSE 8000

CMD ["sh", "-c", "php artisan migrate --force && php artisan storage:link && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT"]
